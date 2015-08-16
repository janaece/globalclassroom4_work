<?php

/**
 * Description of GcrCourseListModeTeacher
 *
 * @author ron
 */
class GcrCourseListModeTeacher extends GcrCourseListMode
{
    public function setCourseList()
    {
        $enrolment_array = array();
        $enrolments = $this->object->getEnrolments(array(), 3);
        foreach($enrolments as $enrolment)
        {
            if ($this->checkUserAccess($enrolment->getCourse()))
            {
                $enrolment_array[] = $enrolment;
            }
        }
        $this->course_list->buildListFromMdlEnrolmentArray($enrolment_array);
    }
    
    public function setObject()
    {
        $this->object = $this->course_list->getInstitution()->getUserById($this->id);
    }
}

?>
