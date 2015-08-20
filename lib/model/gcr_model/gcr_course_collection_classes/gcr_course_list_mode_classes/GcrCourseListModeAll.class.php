<?php

/**
 * Description of GcrCourseListModeAll
 *
 * @author ron
 */
class GcrCourseListModeAll extends GcrCourseListMode 
{
    public function setCourseList()
    {
        global $CFG;
        $mdl_courses = array();    
        foreach ($this->course_list->getInstitution()->getMnetEschools() as $eschool)
        {   
            $eschool_courses = $this->getMdlCourses($eschool);
            foreach($eschool_courses as $mdl_course)
            {
                $mdl_course->eschool = $eschool;
            }
            if ($eschool_courses)
            {
                $mdl_courses = array_merge($mdl_courses, $eschool_courses);
            }
        }
        usort($mdl_courses, function ($a, $b)
        {
            $a_fullname = strtolower(trim($a->fullname));
            $b_fullname = strtolower(trim($b->fullname));
            return strcmp($a_fullname, $b_fullname);
        });
        $this->course_list->buildListFromMdlCourseArray($mdl_courses); 
    }
    public function setObject()
    {
        $this->object = false;
    }
    public function getMdlCourses(GcrEschool $eschool)
    {
		// removed course authorization
        //if (GcrEschoolTable::authorizeEschoolAccess($eschool))
        //{
            $params = $this->course_list->getParameters();
            return $eschool->getMdlCourses(false, $params['search_string'], $params['category_id']);
        //}
        return array();
    }
}

?>
