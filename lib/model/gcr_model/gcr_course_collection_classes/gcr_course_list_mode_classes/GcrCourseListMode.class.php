<?php

/**
 * Description of GcrCourseListMode
 *
 * @author ron
 */
abstract class GcrCourseListMode 
{
    protected $id;
    protected $object;
    protected $course_list;
    
    public function __construct(GcrCourseList $course_list, $id = false)
    {
        $this->id = $id;
        $this->course_list = $course_list;
        $this->setObject();
        $this->setIncludeClosed();
        $this->setCourseList();
    }
    public function getModeName()
    {
        return str_replace('GcrCourseListMode', '', get_class($this));
    }
    public function setIncludeClosed()
    {
        global $CFG;
        $this->course_list->setIncludeClosed($CFG->current_app->hasPrivilege('EclassroomUser'));
    }
    
    abstract public function setCourseList();
    abstract public function setObject();
}

?>
