<?php
// GCUser class.
// Ron Stewart
// August 19, 2010
//
// This is a wrapper class for the Moodle $USER object. Here, we can include
// any methods which relate to the current user, rather than keeping them in gclib.php.
// The use this class, simply instantiate it, and call whatever methods you need to
// deal with the current Moodle $USER
class GcrCurrentMdlUser extends GcrMdlUser
{
    protected $user_storage;
    
    function __construct()
    {
        global $USER, $CFG;
        parent::__construct($USER, $CFG->current_app);
        $this->getRoleManager()->setPermissionsOnRoles();
        if ($this->getRoleManager()->hasPrivilege('Student'))
        {
            $this->updateProfilePicture();
        }
    }
    public function canEditCourse($course)
    {
        if (has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->getObject()->id)))
        {
            return true;
        }
        return false;
    }
    public function getUserStorage()
    {
        if (!isset($this->user_storage))
        {
            $this->user_storage = new GcrUserStorageAccessS3();
        }
        return $this->user_storage;
    }
    public function isSiteAdmin()
    {
        return is_siteadmin($this->obj->id);
    }
    public function updateProfilePicture()
    {
        global $CFG;
        $has_picture = 0;
        $mhr_user = $this->getUserOnInstitution();
        if (!$mhr_user)
        {   
            return $has_picture;
        }
        $picture_id = $mhr_user->getObject()->profileicon;
        if ((!$picture_id) || $picture_id == '')
        {
            return $has_picture;
        }
        $old_picture = $this->app->selectFromMdlTable('gcr_profile_picture', 'user_id', $this->obj->id, true);
        if ((!$old_picture) || ($old_picture->picture_id != $picture_id))
        {
            $iconfile = gcr::moodledataDir . $mhr_user->getApp()->getShortName() . 
                    '/artefact/file/profileicons/originals/' . ($picture_id % 256) . '/' . $picture_id;
            require_once("$CFG->libdir/gdlib.php");
            $context = get_context_instance(CONTEXT_USER, $this->obj->id, MUST_EXIST);

            if (process_new_icon($context, 'user', 'icon', 0, $iconfile))
            {
                if ($old_picture)
                {
                    $this->app->updateMdlTable('gcr_profile_picture', array('picture_id' => $picture_id), 
                            array('user_id' => $this->obj->id));
                }
                else
                {
                    $this->app->insertIntoMdlTable('gcr_profile_picture', 
                            array('user_id' => $this->obj->id, 'picture_id' => $picture_id));
                }
                $has_picture = 1;
            }
            else
            {
                $fs = get_file_storage();
                $fs->delete_area_files($context->id, 'user', 'icon');
                $this->app->deleteFromMdlTable('gcr_profile_picture', 'user_id', $this->obj->id);
                $has_picture = 0;
            }
        }
        else
        {
            $has_picture = 1;
        }
        if ($this->obj->picture != $has_picture)
        {
            $this->app->updateMdlTable('user', array('picture' => $has_picture), array('id' => $this->obj->id));
        }
        return $has_picture;
    }
}