<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrUserStorageS3
 *
 * @author Ron Stewart
 * Created on Oct. 17, 2011
 */
class GcrUserStorageAccessS3 extends GcrStorageAccessS3
{
    protected $folders;
    protected $existing_folders;
    protected $files;
    protected $has_list;
    protected $key;
    protected $user;
    
    public function __construct($folder = false)
    {
        global $CFG;
        $user = $CFG->current_app->getCurrentUser()->getUserOnInstitution();
        parent::__construct($user->getApp());
        
        if (!$user && $CFG->current_app->hasPrivilege('GCAdmin'))
        {
            $user = $CFG->current_app->getInstitution()->getGCAdminUser();
        }
        $this->user = $user;
        if ((!$folder) || (!$this->authorizeAccess($folder)))
        {
            $folder = $this->getUserFolder();
        }
        $this->setKey($folder);
        $this->setFolders();
        $this->files = array();
        $this->has_list = false;
    }
    public function allowsUpload()
    {
       if ($this->key == 'public' || $this->key == 'shared')
       {
           if (!$this->user->getRoleManager()->hasPrivilege('EschoolAdmin'))
           {
               return false;
           }
       }
       return true;
    }
    public function getFilePath($file)
    {
        return $this->key . '/' . $file;
    }
    public function copyObject($obj, $new_obj, $bucket = false)
    {
        if ($this->authorizeWriteAccess($obj) && $this->authorizeWriteAccess($new_obj))
        {
            parent::copyObject($obj, $new_obj, $bucket);
        }
    }
    protected function setObjectLists($restrict_by_key = true)
    {
        $object_list = $this->api->get_object_list($this->bucket);
        foreach ($object_list as $obj)
        {
            if ($this->authorizeAccess($obj))
            {
                if ($this->endsWith($obj, '/'))
                {
                    $this->existing_folders[] = $obj;
                }
                else
                {
                    $this->files[] = $obj;
                }
            }    
        }
        $this->has_list = true;
    }
    public function setKey($folder = false)
    {
        $this->key = $folder;
        if (!$this->authorizeAccess($this->key))
        {
            $this->app->gcError('User does not have permission to access AWS S3 folder: ' . 
                    $key, 'gcdatabaseerror');
        }
    }
    public function conformsToKey($obj)
    {
        return $this->startsWith($obj, $this->key);
    }
    public function getKey()
    {
        return $this->key;
    }
    public function getFileName($obj)
    {
        $parts = explode('/', $obj);
        return $parts[(count($parts) - 1)];
    }
    public function getFileList($restrict_by_key = true, $refresh = false)
    {
        if (!$this->has_list || $refresh)
        {
            $this->setObjectLists();
        }
        $files = array();
        if ($restrict_by_key)
        {
            foreach ($this->files as $file)
            {
                if ($this->conformsToKey($file))
                {
                    $files[] = $file;
                }
            }
        }
        else
        {
            $files = $this->files;
        }
        return $files;
    }
    public function getObjectKey($obj)
    {
        $key = '';
        if ($this->endsWith($obj, '/'))
        {
            $key = $obj;
        }
        else
        {
            $parts = explode('/', $obj);
            if (count($parts > 1))
            {
                $key = implode('/', array_slice($parts, 0, (count($parts) - 1)));
            }
        }
        return $key;
    }
    public function getExistingFolders($refresh = false)
    {
        if (!$this->has_list || $refresh)
        {
            $this->setObjectLists();
        }
        return $this->existing_folders;
    }
    public function getFolders()
    {
        return $this->folders;
    }
    protected function setFolders()
    {
        $this->folders['public'] = 'Public Files (Public Access)';
        $this->folders['shared'] = 'Shared Files (All Users Access)';
        $user_folders = array();
        if ($this->isAdmin())
        {
            foreach ($this->app->getInstitution()->getUsers() as $user)
            {
                $user_folders['user-' . $user->id] = ucfirst(trim($user->firstname . ' ' . $user->lastname)) .
                        ' (' . $user->username . ')';
            }
        }
        else
        {
            $user_obj = $this->user->getObject();
            $user_folders['user-' . $user_obj->id] = ucfirst($this->user->getFullNameString()) . 
                        ' (' . $user_obj->username . ')';
        }
        asort($user_folders);
        $this->folders = array_merge($this->folders, $user_folders); 
    }
    public function isAdmin()
    {
        return ($this->user->getRoleManager()->hasPrivilege('EschoolAdmin'));
    }
    public function getStaticUrl($obj, $params = array())
    {
        if ($this->authorizeAccess($obj))
        {
            return parent::getStaticUrl($obj, $params);   
        }
        $this->app->gcError('User Does not have authorization to access s3 file: ' . 
                $obj, 'gcdatabaseerror'); 
    }
    public function getUserFolder($user = false)
    {
        if (!$user)
        {
            $user = $this->user;
        }
        return 'user-' . $user->getObject()->id;
    }
    public function deleteObject($obj)
    {
        if ($this->authorizeWriteAccess($obj))
        {
            parent::deleteObject($obj);
        }
        else
        {
            $this->app->gcError('User does not have permission to access AWS S3 resource: ' . 
                    $obj, 'gcdatabaseerror');
        }
    }
    public function authorizeAccess($key)
    {
        return ($this->isAdmin() || 
                $this->startsWith($key, $this->getUserFolder()) || 
                $this->startsWith($key, 'public') ||
                $this->startsWith($key, 'shared'));   
    }
    public function authorizeWriteAccess($obj)
    {
        return ($this->isAdmin() || 
                $this->startsWith($obj, $this->getUserFolder()));
    }
}

?>
