<?php

/**
 * Description of GcrCourseListModeEschool
 *
 * @author ron
 */
class GcrCourseListModeEschool extends GcrCourseListMode
{
    public function setCourseList()
    {
        $mdl_courses = $this->getMdlCourses();
        foreach($mdl_courses as $mdl_course)
        {
            $mdl_course->eschool = $this->object;
        }
        $this->course_list->buildListFromMdlCourseArray($mdl_courses);
    }
    public function setObject()
    {
        $this->object = GcrEschoolTable::getEschool($this->id);
    }
    public function getMdlCourses()
    {
        $mdl_courses = array();
		// removed course authorization
        //if (GcrEschoolTable::authorizeEschoolAccess($this->object))
        //{
            $params = $this->course_list->getParameters();
            $mdl_courses = $this->object->getMdlCourses(false, $params['search_string'], $params['category_id']);
            if ($params['category_id'])
            {
                $course_collections = $this->object->getCourseCollections($params['category_id']);
                foreach($course_collections as $course_collection)
                {
                    $mdl_courses[] = $course_collection->getRepresentativeCourse()->getObject(); 
                }
            }
        //}
        return $mdl_courses;
    }
}

?>
