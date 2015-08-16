<?php

/**
 * Description of GcrCourseListModeStudent
 *
 * @author ron
 */
class GcrCourseListModeStudent extends GcrCourseListMode
{
    public function setCourseList()
    {
        $this->course_list->setIncludeClosed(true);
        $enrolments = $this->object->getEnrolments();
        usort($enrolments, function ($a, $b)
        {
            $a_fullname = strtolower(trim($a->getMdlCourse()->fullname));
            $b_fullname = strtolower(trim($b->getMdlCourse()->fullname));
            return strcmp($a_fullname, $b_fullname);
        });
        $this->course_list->buildListFromGcrMdlEnrolmentArray($enrolments);
    }
    public function setIncludeClosed() 
    {
        return true;
    }
    public function setObject()
    {
        global $CFG;
        $user = $CFG->current_app->getCurrentUser();
        if (!$user)
        {
            $CFG->current_app->gcError('Non-logged-in attempt to access my courses', 'gcdatabaseerror');
        }
        $this->object = $user;
    }
}

?>
