<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrStorageAccessS3:
 * 
 *
 * @author Ron Stewart
 */
class GcrStorageAccessS3 
{
    protected $api;
    protected $bucket;
    protected $app;
    const FILE_GET_PARAMETER = 'gc_s3_file';
    
    public function __construct($app = false)
    {
        $this->app = $app;
        if (!$app)
        {
            global $CFG;
            $this->app = $CFG->current_app;
        }
        
        $account = GcrUserStorageS3Table::getAccount($this->app);
        define('AWS_KEY', $account->getAccessKeyId());
        define('AWS_SECRET_KEY', $account->getSecretAccessKey());
        gcr::loadSdk('aws');
        $this->api = new AmazonS3();
        
        if (!$user_storage_s3 = Doctrine::getTable('GcrUserStorageS3')->
                findOneByAppId($this->app->getInstitution()->getShortName()))
        {
            $user_storage_s3 = $this->createBucket($account);
        }
        else
        {
            $this->bucket = $user_storage_s3->getBucketName();
        }
    }
    public function getApi()
    {
        return $this->api;
    }
    protected function createBucket(GcrUserStorageS3Account $account)
    {
        $app = $this->app->getInstitution();
        $this->bucket = $app->getShortName() . '-' . strtolower(GcrEschoolTable::generateRandomString(25));
        
        $create_bucket_response = $this->api->create_bucket($this->bucket, AmazonS3::REGION_US_E1);
        // Provided that the bucket was created successfully...
        if ($create_bucket_response->isOK())
        {
            /* Since AWS follows an "eventual consistency" model, sleep and poll
               until the bucket is available. */
            $exists = $this->api->if_bucket_exists($this->bucket);
            $counter = 0;
            while (!$exists)
            {
                // Not yet? Sleep for 1 second, then check again
                sleep(1);
                $exists = $this->api->if_bucket_exists($this->bucket);
                if ($counter++ > 15)
                {
                    $this->app->gcError('Bucket Creation Timed Out', 'gcdatabaseerror');
                }
            }
            $user_storage_s3 = new GcrUserStorageS3();
            $user_storage_s3->setAppId($app->getShortName());
            $user_storage_s3->setBucketName($this->bucket);
            $user_storage_s3->setAccountId($account->getId());
            $user_storage_s3->save();
        }
        else
        {
            $this->app->gcError('AWS Bucket Creation Failed', 'gcdatabaseerror');
        }
    }
    public function copyObject($obj, $new_obj, $bucket = false)
    {
        if ($bucket)
        {
            if (!$this->user->getRoleManager()->hasPrivilege('GCUser'))
            {
                $this->app->gcError('Non privileged attempt to copy an s3 file to bucket ' . $bucket, 'gcdatabaseerror');
            }
        }
        else
        {
            $bucket = $this->bucket;
        }
        $this->api->copy_object(array('bucket' => $this->bucket, 'filename' => $obj), 
                array('bucket' => $this->bucket, 'filename' => $new_obj));
    }
    public function isPublicObject($obj)
    {
        return $this->startsWith($obj, 'public');
    }
    public function moveObject($obj, $new_obj, $bucket = false)
    {
        if ($obj != $new_obj)
        {
            $this->copyObject($obj, $new_obj, $bucket);
            $this->deleteObject($obj);
        }
    }
    public function getStaticUrl($obj, $params = array())
    {
        $params['app'] = $this->app->getShortName();
        return self::generateStaticUrl($obj, $params);
    }
    public function getUploadHtml($redirect = false)
    {
        $options = array();
        if (!$redirect)
        {
            $redirect = $this->app->getUrl() . $this->app->getRequestUri();
        }
        $options['success_action_redirect'] = $redirect;
        $options['starts-with'] = $this->key . '/';
        $upload = new S3BrowserUpload();
        $form = $upload->generate_upload_parameters($this->bucket, '1 hour', $options);
        return $form;
    }
    public function deleteObject($obj)
    {
        $this->api->delete_object($this->bucket, $obj);  
    }
    protected function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    public function getBucket()
    {
        return $this->bucket;
    }
    protected function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }
    public function getObjectUrl($obj)
    {
        $metadata = $this->api->get_object_metadata($this->bucket, $obj);
        
        $mimetype = GcrFileLib::mimeinfo($obj);
        if ($metadata['ContentType'] != $mimetype)
        {
            $this->setContentType($obj, $mimetype);
        }
        if (empty($metadata['cache-control']))
        {
            $this->setCacheControl($obj);
        }
        $response = $this->api->get_object_url($this->bucket, $obj, '5 minutes');
        return str_replace('http://', 'https://', $response);
    }
    public function isFolder($file)
    {
        return ($this->endsWith($file, '/'));
    }
    public function setContentType($obj, $content_type)
    {
        $this->api->change_content_type($this->bucket, $obj, $content_type);
    }
    public function setCacheControl($obj)
    {
        $headers = array('Cache-Control' => 'max-age=604800, public');
        $options = array('headers' => $headers);
        $this->api->update_object($this->bucket, $obj, $options);
    }
    // This function is called to update a cloud storage url to have
    // the correct signature and course id in its externalurl field.
    public static function refreshUrl($mdl_mediaelementjs)
    {
        $url = $mdl_mediaelementjs->externalurl;
        if (strpos($url, 'institution/getUserStorageFile?'))
        {
            $file_param = self::FILE_GET_PARAMETER . '=';
            $str_start = strpos($url, $file_param);
            if ($str_start)
            {
                $short_name = GcrEschoolTable::parseShortNameFromUrl($url);
                $app = GcrInstitutionTable::getApp($short_name);
                $institution = $app->getInstitution();
                $short_name = $institution->getShortName();
                $str_start += strlen($file_param);
                $str_end = strpos($url, '&', $str_start);
                $filename = substr($url, $str_start, ($str_end - $str_start));
                $filename = urldecode($filename);
                $str_start = strpos($url, '&app=');
                if ($str_start)
                {
                    $str_start += strlen('&app=');
                    $str_end = strpos($url, '&', $str_start);
                    $short_name_user_app = substr($url, $str_start, ($str_end - $str_start));
                    $user_app = GcrInstitutionTable::getApp($short_name_user_app);
                    if ($user_app)
                    {
                        $short_name = $short_name_user_app;   
                    } 
                }
                $params = array(self::FILE_GET_PARAMETER => $filename,
                                    'course_id' => $mdl_mediaelementjs->course,
                                    'app' => $short_name);
                $mdl_mediaelementjs->externalurl = GcrStorageAccessS3::generateStaticUrl($filename, $params, $app);
            }
        }
        return $mdl_mediaelementjs;
    }
    public static function generateStaticUrl($obj, $params = array(), $app = false)
    {
        if (!$app)
        {
            global $CFG;
            $app = $CFG->current_app;
        }
        if (!isset($params[self::FILE_GET_PARAMETER]))
        {
            $params[self::FILE_GET_PARAMETER] = $obj;
        }
        $signed_request = new GcrSignedRequest($params, $app);
        $signed_request->signParameters();
        $separator = '?';
        $query_string = '';
        foreach($signed_request->getParameters() as $key => $value)
        {
            $query_string .= $separator . urlencode($key) . '=' . urlencode($value);
            $separator = '&';
        }
        return $app->getUrl() . '/institution/getUserStorageFile' . $query_string;
    }
}

?>
