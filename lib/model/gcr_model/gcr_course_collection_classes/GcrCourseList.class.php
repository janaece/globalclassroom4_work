<?php
/**
 * Description of GcrCourseList
 *
 * @author ron
 */
class GcrCourseList 
{
    protected $start_index;
    protected $end_index;
    protected $list_size;
    protected $course_list;
    protected $courses_count;
    protected $include_disabled;
    protected $include_closed;
    protected $search_string;
    protected $category_id;
    protected $institution;
    protected $mode;
    protected $mode_id;
    protected $lib_id;
    protected $visible;
    protected $represented_courses;
    const STUDENT_MODE = 'Student';
    const TEACHER_MODE = 'Teacher';
    const ALL_COURSES_MODE = 'All';
    const ESCHOOL_MODE = 'Eschool';
    const COURSE_MODE = 'Course';
    const DEFAULT_LIST_SIZE = 100;

    public function __construct($params, $institution = false)
    {
        $this->represented_courses = array();
        $this->setInstitution($institution);
        
        foreach (self::getParameterList() as $key => $value)
        {
            if (isset($params[$key]))
            {
                $this->$key = $params[$key];
            }
            else
            {
                $this->$key = $value;
            }
        }
        $classname = 'GcrCourseListMode' . $this->mode;
        if (class_exists($classname))
        {
            $this->mode = new $classname($this, $this->mode_id);
        }
    }
    public static function getParameterList()
    {
        return array(
            'start_index' => 0,
            'list_size' => self::DEFAULT_LIST_SIZE,
            'include_closed' => false,
            'category_id' => false,
            'search_string' => false,
            'visible' => 1,
            'mode' => self::ALL_COURSES_MODE,
            'mode_id' => -1,
            'lib_id' => 0
        );
    }
    public function getParameters()
    {
        $params = array();
        foreach (self::getParameterList() as $key => $value)
        {
            $params[$key] = $this->$key;
        }
        return $params;
    }
    public function buildListFromMdlCourseArray($mdl_courses)
    {
        $this->end_index = $this->start_index;
        while ((!$this->list_size || count($this->course_list) < $this->list_size) && 
                $this->end_index < count($mdl_courses))
        {
            $eschool = $mdl_courses[$this->end_index]->eschool;
            $mdl_course = $mdl_courses[$this->end_index++];
            unset($mdl_course->eschool);
            if (!$this->isRepresented($eschool, $mdl_course))
            {
                $course = new GcrMdlCourse($mdl_course, $eschool);
                $this->setRepresentedCourses($course);
                if (!$this->isRepresented($eschool, $mdl_course))
                {
                    $this->addCourseToList($course);
                }
            }
        }
        
    }
    public function isRepresented($eschool, $mdl_course)
    {
        return array_key_exists($eschool->getShortName() . '-' . 
                $mdl_course->id, $this->represented_courses);
    }
    protected function setRepresentedCourses(GcrMdlCourse $course)
    {
        $course_collection = $course->getCourseCollection();
        if ($course_collection)
        {
            $representative = $course_collection->getRepresentativeCourse();
            foreach ($course_collection->getCourses() as $course_instance)
            {
                if ($course_instance->getObject()->id != $representative->getObject()->id)
                {
                    $this->represented_courses[$course_instance->getApp()->getShortName() . 
                        '-' . $course_instance->getObject()->id] = 1;
                }
            }
        }
    }
    public function buildListFromGcrMdlEnrolmentArray($enrolments)
    {
        $this->end_index = $this->start_index;
        while ((!$this->list_size || count($this->course_list) < $this->list_size) && 
                $this->end_index < count($enrolments))
        {
            $represented = false;
            $course = $enrolments[$this->end_index++]->getCourse();
            if ($course->isRepresented())
            {
                // If the course is represented, we check to see if the user
                // is also enrolled in the representative course. If so, we
                // will omit this course instance so avoid duplications on
                // the list. NOTE: if the user is enrolled in numerous instances
                // of a course, but not in the representative for that course group,
                // each instance will appear as a seperate course in the list. There
                // are very few situations where this is likely to happen though.
                $rep_course = $course->getCourseCollection()->getRepresentativeCourse();
                $id = $rep_course->getObject()->id;
                foreach($enrolments as $enrolment)
                {
                    if ($enrolment->getCourse()->getObject()->id == $id)
                    {
                        $represented = true;
                    }
                }
            }
            if (!$represented)
            {
                $this->addCourseToList($course);
            }
        }
    }
    public function filterCourse(GcrMdlCourse $course)
    {
        return $this->filterCourseBySearchString($course)
            && $this->filterCourseByVisibility($course)
            && $this->filterCourseByEnrollability($course);
    }
    protected function checkUserAccess(GcrMdlCourse $course)
    {
		// removed course authorization
        //return GcrEschoolTable::authorizeCourseAccess($course);
        return true;
    }
    protected function addCourseToList(GcrMdlCourse $course)
    {
        if ($this->filterCourse($course) && $this->checkUserAccess($course))
        {
            $this->course_list[$course->getApp()->getShortName() . 
                '-' . $course->getObject()->id] = $course;
			$this->courses_count = $this->courses_count + 1;
        }
    }
    protected function filterCourseBySearchString($course)
    {
        $success = true;
        if (!empty($this->search_string))
        {
            $string = strtolower($this->search_string);
            $mdl_course = $course->getObject();
            if (strpos(strtolower($mdl_course->summary), $string) === false && 
                strpos(strtolower($mdl_course->fullname), $string) === false && 
                strpos(strtolower($mdl_course->shortname), $string) === false)
            {
                $success = false;
            }  
        }
        return $success;
    }
    protected function filterCourseByVisibility($course)
    {
        return ($this->visible == $course->getObject()->visible);
    }
    protected function filterCourseByEnrollability($course)
    {
        return ($this->include_closed || $course->isSelfEnrollable());
    }
    public function getInstitution()
    {
        return $this->institution;
    }
    public function setInstitution($institution = false)
    {
        if (!$institution)
        {
            global $CFG;
            $user = $CFG->current_app->getCurrentUser();
            if ($user)
            {
                $institution = $user->getInstitution();
            }
            else
            {
                $institution = $CFG->current_app->getInstitution();
            }         
        }
        $this->institution = $institution;
    }
    public function getCourseList()
    {
        return $this->course_list;
    }
    public function getCoursesCount()
    {
        return $this->courses_count;
    }	
    public function getEndIndex()
    {
        return $this->end_index;
    }
    public function getListSize()
    {
        return $this->list_size;
    }	
    public function getIncludeClosed()
    {
        return $this->include_closed;
    }
    public function setIncludeClosed($include_closed)
    {
        $this->include_closed = $include_closed;
    }
    public function toArray()
    {
        $course_list_array = $this->getParameters();
        $course_list_array['end_index'] = $this->end_index;
        
        $courses_array = array();
        $count = 0;
        foreach ($this->course_list as $key => $course)
        {
            $count++;
            $course_array = array();
            $mdl_course = $course->getObject();
            $course_list_item = new GcrCourseListItem($course);
            $mdl_course = $course->getObject();
            $eschool = $course->getApp();
            
            $course_array['id'] = $mdl_course->id;
            $course_array['shortname'] = $mdl_course->shortname;
            $course_array['eschool'] = $eschool->getShortName();
            $course_array['fullname'] = $mdl_course->fullname;
            $course_array['course_icon_url'] = $course_list_item->getCourseIconUrl();
            $course_array['course_url'] = $course->getUrl();
            $course_array['is_representative'] = $course_list_item->getCourseCollection();
            
            $courses_array[$key] = $course_array;
        }
        $course_list_array['course_list'] = $courses_array;
        $course_list_array['course_count'] = $count;
        return $course_list_array;
    }
    
    
    
}

?>
