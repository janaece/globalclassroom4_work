<?php

/**
 * Description of GcrCourseCollection
 *
 * @author ron
 */
class GcrCourseCollection 
{
    protected $courses;
    protected $course_category;
    protected $representative_course;
    protected $visible;
    protected $enrollable;
    protected $has_icon;
    
    protected function __construct(GcrMdlCourseCategory $course_category)
    {
        $this->course_category = $course_category;
        foreach ($course_category->getMdlCourses() as $mdl_course)
        {
            $course = new GcrMdlCourse($mdl_course, $course_category->getApp());
            $this->courses[] = $course;
            $this->setRepresentativeCourse($course);
        }
    }
    
    static public function getInstance(GcrMdlCourseCategory $course_category)
    {
        if (empty($course_category->getObject()->idnumber))
        {
            return false;
        }
        return new GcrCourseCollection($course_category);
    }
    protected function setRepresentativeCourse(GcrMdlCourse $course)
    {
        // The represntative course is chosen by these heirarchical criteria:
        // 1. visible
        // 2. enrollable
        // 3. has a course_profile block icon
        // 4. start date is in the future
        // 5. start date is soonest from now
        $flag = 0;
        if (!$this->representative_course)
        {
            $flag = 1;   
        }
        if ($flag == 0)
        {
            $flag = $this->compareVisibility($course);
        }
        if ($flag == 0)
        {
            $flag = $this->compareEnrol($course);
        }
        if ($flag == 0)
        {
            $flag = $this->compareHasIcon($course);
        }
        if ($flag == 0)
        {
            $flag = $this->compareStartDate($course);
        }
        if ($flag > 0)
        {
            $this->representative_course = $course;
        }
        return;
    }
    protected function compareVisibility(GcrMdlCourse $course)
    {
        $result = 0;
        $course_visible = $course->getObject()->visible;
        $visible = $this->representative_course->getObject()->visible;
        if ($visible == 1 && $course_visible != 1)
        {
            $result = -1;
        }
        else if ($course_visible == 1 && $visible != 1)
        {
            $result = 1;
        }
        return $result;
    }
    protected function compareEnrol(GcrMdlCourse $course)
    {
        $result = 0;
        $course_enrol = $course->isSelfEnrollable();
        $enrol = $this->representative_course->isSelfEnrollable();
        if ($enrol && !$course_enrol)
        {
            $result = -1;
        }
        else if (!$enrol && $course_enrol)
        {
            $result = 1;
        }
        return $result;
    }
    protected function compareHasIcon(GcrMdlCourse $course)
    {
        $result = 0;
        $course_block_course_profile = $course->getBlockCourseProfile();
        $block_course_profile = $this->representative_course->getBlockCourseProfile();
        $course_has_icon = ($course_block_course_profile && $course_block_course_profile->getObject()->courseicon != '');
        $has_icon = ($block_course_profile && $block_course_profile->getObject()->courseicon != '');
        if ($has_icon && !$course_has_icon)
        {
            $result = -1;
        }
        else if ($course_has_icon && !$has_icon)
        {
            $result = 1;
        }
        return $result;
    }
    protected function compareStartDate(GcrMdlCourse $course)
    {
        $result = 0;
        $course_start_date = $course->getObject()->startdate;
        $start_date = $this->representative_course->getObject()->startdate;
        $now = time();
        // If one start date is already past, we pick the one in
        // the future.
        if ($start_date > $now && $course_start_date <= $now)
        {
            $result = -1;
        }
        else if ($course_start_date > $now && $start_date <= $now)
        {
            $result = 1;
        }
        if ($result == 0)
        {
            // Both dates are in the future, we pick the soonest one.
            if ($start_date < $course_start_date)
            {
                $result = -1;
            }
            else if ($course_start_date < $start_date)
            {
                $result = 1;
            }
            // Both dates are in past, pick most recent.
            $result = ($start_date < $now) ? ($result * -1) : $result;
        }
        return $result;
    }
    public function getActiveUserCount($role_id = 5)
    {
        $count = 0;
        foreach ($this->courses as $course)
        {
            $count += count($course->getActiveUsersInCourse($role_id));
        }
        return $count;
    }
    
    public function getCourses($include_hidden = true)
    {
        return $this->courses;
    }
    public function getRepresentativeCourse()
    {
        return $this->representative_course;
    }
    public function getCourseCategory()
    {
        return $this->course_category;
    }
}

?>
