<?php

/**
 * Description of GcrCourseListItem
 *
 * @author ron
 */
class GcrCourseListItem extends GcrMdlCourse
{
    protected $course;
    protected $course_collection;
    protected $profile_block;
    
    public function __construct(GcrMdlCourse $course)
    {
        $this->course = $course;
        $this->course_collection = $this->course->getCourseCollection();   
        $this->profile_block = $this->course->getBlockCourseProfile();
        if ((!$this->profile_block) && $this->course_collection)
        {
            $course = $this->course_collection->getRepresentativeCourse();
            $this->profile_block = $course->getBlockCourseProfile();
        }
    }
    public function getInstructor()
    {
        if ($this->profile_block)
        {
            $mdl_user = $this->profile_block->getInstructor();
        }
        else
        {
            $mdl_user = $this->course->getInstructor();
        }
        return $mdl_user;
    }
    public function getActiveUserCount($role_id = 5)
    {
        if ($this->course_collection)
        {
            $count = $this->course_collection->getActiveUserCount($role_id);
        }
        else
        {
            $count = count($this->course->getActiveUsersInCourse($role_id));
        }
        return $count;
    }
    public function getCourseIconUrl()
    {
        if (!$this->profile_block)
        {
            return GcrMdlBlockCourseProfile::getDefaultIconUrl();
        }
        return $this->profile_block->getCourseIconUrl();
    }
    public function getSummary()
    {
        return strip_tags($this->course->getObject()->summary);
    }
}

?>
