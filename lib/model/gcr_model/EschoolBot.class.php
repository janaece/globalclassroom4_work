<?php
/**
 * EschoolBot class.
 *
 * @author     Ron Stewart
 * Last Modified: May 27, 2010
 *
 * This class is for a bot which logs in to any eSchool as the gcadmin in order to
 * execute requests automatically to save administration time. cURL is used to
 * issue these requests. The session cookie is stored in a temporary file called
 * sess_cookies.txt, located at the moodledata root directory. This file is deleted
 * when the close() method is called.
 */
class EschoolBot
{
    private $app;
    private $curl_connection;

    public function getApp()
    {
        return $this->app;
    }

    public function setApp($app, $connection_type = 'admin')
    {
        $this->app = $app;
        if ($connection_type == 'admin')
        {
            $this->initAdminCurlConnection();
        }
    }

    // This function creates a cURL connection as the gcadmin. The session cookies to stay logged
    // in are stored in a temporary file called sess_cookies.txt in the root moodledata directory.
    // This file gets deleted upon completion of upgrades.
    public function initAdminCurlConnection()
    {
        $this->close();
        //set autologin eschool
        $post_string = "eschoolList={$this->app->getShortName()}";
        //create cURL connection
        $this->curl_connection = curl_init($this->app->setupAdminAutoLogin());
        //set options
        //curl_setopt($this->curl_connection, CURLOPT_CONNECTTIMEOUT, 30); //set time untill cURL times out
        curl_setopt($this->curl_connection, CURLOPT_HEADER,0);
        curl_setopt($this->curl_connection, CURLOPT_POST, 1);
        curl_setopt($this->curl_connection, CURLOPT_POSTFIELDS, $post_string);//set data to be posted
        curl_setopt($this->curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl_connection, CURLOPT_FOLLOWLOCATION, 1); // follow redirection if any...
        curl_setopt($this->curl_connection, CURLOPT_SSL_VERIFYPEER, false); //determine if to continue processing code if SSL certificate is verifyed or not...
        curl_setopt($this->curl_connection, CURLOPT_COOKIEJAR, gcr::moodledataDir . 'sess_cookies.txt');
        curl_setopt($this->curl_connection, CURLOPT_COOKIEFILE, gcr::moodledataDir . 'sess_cookies.txt');
        //perform our request (execute request)
        curl_exec($this->curl_connection);
    }
    public function performResetMdlCourseBlocks()
    {
        curl_setopt($this->curl_connection, CURLOPT_URL, $this->app->getUrl() . '/eschool/resetCourseBlocks');
        $result = curl_exec($this->curl_connection);
        /*** a new dom object ***/
        $dom = new domDocument();
        /*** load the html into the object ***/
        $dom->loadHTML($result);
        if ($flag = $dom->getElementById('gcr_reset_course_blocks'))
        {
            return true;
        }
        return false;
    }
    public function performMnetConnectionReplacement($key)
    {
        //$post_string = "replace_mnet={$key}";

        $this->curl_connection = curl_init($this->app->getAppUrl() . '?replace_mnet=' . $key);
        //set options
        curl_setopt($this->curl_connection, CURLOPT_HEADER,0);
        curl_setopt($this->curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl_connection, CURLOPT_FOLLOWLOCATION, 1); // follow redirection if any...
        curl_setopt($this->curl_connection, CURLOPT_SSL_VERIFYPEER, false); //determine if to continue processing code if SSL certificate is verifyed or not...
        //perform our request (execute request)
        $result = curl_exec($this->curl_connection);
        /*** a new dom object ***/
        $dom = new domDocument();
        /*** load the html into the object ***/
        $dom->loadHTML($result);
        if ($flag = $dom->getElementById('mnet_replacement'))
        {
            return true;
        }
        return false;
    }
    public function setDefaultValuesOnAutoUpdateSettings()
    {
        $this->initAdminCurlConnection();
        curl_setopt($this->curl_connection, CURLOPT_URL, $this->app->getAppUrl() . '/admin/index.php');
        
        $result = curl_exec($this->curl_connection);
        /*** a new dom object ***/
        $dom = new domDocument();
        /*** load the html into the object ***/
        $dom->loadHTML($result);
        
        $postdata = null;

        // Attempt to get the form on page which saves settings data. If it is not found, this means
        // that the current page just needs a simple "Continue"
        $adminForm = $dom->getElementById('adminsettings');
        if ($adminForm)
        {
            // If we have a setting form to complete, copy all form fields so we
            // can issue a post request with the default values
            $inputTags = $adminForm->getElementsByTagName('input');
            $selectTags = $adminForm->getElementsByTagName('select');
            $textareaTags = $adminForm->getElementsByTagName('textarea');
        }
        $postString = "";
        // This loop scrapes all of the input tag values
        foreach ($inputTags as $tag)
        {
            if ($tag->hasAttribute('name'))
            {
                $postdata[$tag->getAttribute('name')] = $tag->getAttribute('value');
            }
        }
        foreach ($textareaTags as $tag)
        {
            if ($tag->hasAttribute('name'))
            {
                $value = $tag->getAttribute('value');
                if (empty($value))
                {
                    $value = $this->get_inner_html($tag);
                }
                $postdata[$tag->getAttribute('name')] = $value;
            }
        }
        // This loop scrapes the default selected value in a select tag
        foreach ($selectTags as $tag)
        {
            $optionTags = $tag->getElementsByTagName('option');
            foreach ($optionTags as $oTag)
            {
                // pick the first option
                if (!$postdata[$tag->getAttribute('name')])
                {
                    $postdata[$tag->getAttribute('name')] = $oTag->getAttribute('value');
                }
                // overwrite the first option if a selected attribute is listed
                if ($oTag->hasAttribute('selected'))
                {
                    $postdata[$tag->getAttribute('name')] = $oTag->getAttribute('value');
                }
            }

        }
        // This loop converts the scraped data into a formatted postdata string
        foreach ($postdata as $key => $value)
        {
            $postString .= $seperator . $key . '=' . $value;
            $seperator = '&';
        }
        // Set the post fields and execute the curl request
        curl_setopt($this->curl_connection, CURLOPT_POSTFIELDS, $postString);//set data to be posted
        $result = curl_exec($this->curl_connection);
        /*** a new dom object ***/
        $dom = new domDocument();
        /*** load the html into the object ***/
        $dom->loadHTML($result);

        $formTags = $dom->getElementsByTagName('div');
        foreach ($formTags as $tag)
        {
            if ($tag->getAttribute('class') == 'box copyright')
            {
                return true;
            }
        }
        return false;
    }
    // This function uses cURL to automatically execute moodle upgrades on an eschool.
    public function performAutoUpdate()
    {
        curl_setopt($this->curl_connection, CURLOPT_URL, $this->app->getAppUrl() . '/admin/index.php');
        // set the post data to skip the unneccessary steps
        curl_setopt($this->curl_connection, CURLOPT_POSTFIELDS, "confirmupgrade=1&confirmrelease=1&confirmplugincheck=1&autopilot=1");//set data to be posted
        //perform our request (execute request)
        curl_exec($this->curl_connection);
        
        if (!$this->setDefaultValuesOnAutoUpdateSettings())
        {
            error_log("\n" . date('d/m/Y H:i:s', time()) . ": Auto_Update: {$this->app->getShortName()}: Cannot Save Default Settings" , 3, gcr::rootDir . 'debug/error.log');
                    return false;
        }
        return $this->performPurgeCaches();
    }
    protected function get_inner_html( $node ) 
    {
        $innerHTML= '';
        $children = $node->childNodes;
        foreach ($children as $child) 
        {
            $innerHTML .= $child->ownerDocument->saveXML( $child );
        }

        return $innerHTML;
    } 
    public function performPurgeCaches()
    {
        // first we need the session key
        curl_setopt($this->curl_connection, CURLOPT_URL, $this->app->getAppUrl() . '/admin/purgecaches.php');
        $result = curl_exec($this->curl_connection);
        $dom = new domDocument();
        $dom->loadHTML($result);
        $formTags = $dom->getElementsByTagName('input');
        foreach ($formTags as $tag)
        {
            if ($tag->getAttribute('name') == 'sesskey')
            {
                $sess_key = $tag->getAttribute('value');
            }
        }
        // now we send the request to purge caches
        curl_setopt($this->curl_connection, CURLOPT_POSTFIELDS, "confirm=1&sesskey=$sess_key");
        curl_exec($this->curl_connection);
        return true;
        
    }
    // Close cURL connection and delete the temporary cookie file
    public function close()
    {
        if ($this->curl_connection)
        {
            curl_close($this->curl_connection);
        }
        // delete the temporary cookie file cURL used to stay logged in
        unlink (gcr::moodledataDir . 'sess_cookies.txt');
    }
}
?>