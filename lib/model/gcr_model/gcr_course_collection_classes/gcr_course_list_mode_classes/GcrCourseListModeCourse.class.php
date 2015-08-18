<?php

/**
 * Description of GcrCourseListModeCourse
 *
 * @author ron
 */
class GcrCourseListModeCourse extends GcrCourseListMode
{
    protected $mdl_course;
    
    public function setCourseList()
    {
        $mdl_courses = $this->getMdlCourses();
        
        $this->course_list->buildListFromMdlCourseArray($mdl_courses);
    }
    public function setObject()
    {
        $arr = explode('_', $this->id);
        $this->object = GcrEschoolTable::getEschool($arr[0]);
        $this->mdl_course = $this->object->selectFromMdlTable('course', 'id', $arr[1], true);
        $course = new GcrMdlCourse($this->mdl_course, $this->object);        
        if ($course->isRepresented())
        {
            $this->mdl_course = $course->getCourseCollection()->getRepresentativeCourse()->getObject();
        }
    }
    public function getMdlCourses()
    {
        $mdl_courses = array();
		// removed course authorization
        //if (GcrEschoolTable::authorizeEschoolAccess($this->object))
        //{
            $this->mdl_course->eschool = $this->object;
            $mdl_courses[] = $this->mdl_course;
        //}
        return $mdl_courses;
    }
}

?>
