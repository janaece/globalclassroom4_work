<?php
// User Enrolment class.
// Ron Stewart
// June 9, 2011
//
// This class represents a user enrolment from a mdl_user_enrolments table of a given $this->eschool, and offers methods
// to manipulate this enrolment, without the need to actually be logged in to that eschool.
class GcrMdlUserEnrolment extends GcrMdlTableRecord
{
    protected $course;
    protected $mhr_user;
    protected $mdl_enrol;
    protected $grade_data;

    public function __construct($obj, $eschool)
    {
        parent::__construct($obj, $eschool);
        $this->mdl_enrol = $this->app->selectFromMdlTable('enrol', 'id', $this->obj->enrolid, true);
        $this->course = $this->app->getCourse($this->mdl_enrol->courseid);
        $mdl_user_obj = $this->app->selectFromMdlTable('user', 'id', $this->obj->userid, true);
        $mdl_user = new GcrMdlUser($mdl_user_obj, $this->app);
        $this->user = $mdl_user->getUserOnInstitution();

        $short_name = $this->app->getShortName();
        $sql = 'select * from ' . $short_name . '.mdl_grade_items gi, ' .
            $short_name . '.mdl_grade_grades gg where gg.userid = ? ' .
            'and gi.courseid = ? and gi.itemtype = ? and gg.itemid = gi.id order by gg.timecreated';
        $this->grade_data = $this->app->gcQuery($sql, array($mdl_user->getObject()->id,
                                                            $this->course->getObject()->id,
                                                            'course'), true);
    }
    public function getCourse()
    {
        return $this->course;
    }
    public function getMdlEnrol()
    {
        return $this->mdl_enrol;
    }
    public function getMdlCourse()
    {
        if ($this->course)
        {
            return $this->course->getObject();
        }
        return false;
    }
    public function getUser()
    {
        return $this->user;
    }
     public function getGradeData()
    {
        return $this->grade_data;
    }
    public function getGradeLetter()
    {
        $grade = false;
        $grade_letters = $this->course->getGradeLetters();
        if ($this->grade_data)
        {
            $grade = $this->grade_data->finalgrade;
        }
        if ($grade && $grade != '')
        {
            return GcrEschoolTable::getLetterGrade($grade, $grade_letters);
        }
        return false;
    }
    public function getInstructor()
    {
        if ($this->course)
        {
            return $this->course->getInstructor();
        }
        return false;
    }
}
?>