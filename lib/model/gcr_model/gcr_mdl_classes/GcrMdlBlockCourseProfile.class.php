<?php

/**
 * Description of GcrMdlBlockCourseProfile
 *
 * @author ron
 */
class GcrMdlBlockCourseProfile extends GcrMdlTableRecord
{
    public function getCourseIconUrl()
    {
        if ($this->obj->courseicon != '')
        {
            $context = $this->getContext();
            return $this->app->getAppUrl() . '/pluginfile.php/' . $context->id . 
                    '/block_course_profile/courseicon/0/' . $this->obj->courseid;
        }
        return self::getDefaultIconUrl();
    }
    public function getInstructor()
    {
        return $this->app->getUserById($this->obj->instructorid);
    }
    public function getContext()
    {
        $sql = 'select * from ' . $this->app->getShortName() .
            '.mdl_context where contextlevel = ? and instanceid = ?';
        $mdl_context = $this->app->gcQuery($sql, array(50, $this->obj->courseid), true);
        return $mdl_context;
    }
    public static function getDefaultIconUrl()
    {
        return '/images/icons/gc-default-course.png';
    }   
}

?>
