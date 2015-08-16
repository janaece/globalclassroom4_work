<?php
class GcrCourseHistory
{
    protected $enrolments;
    
    public function __construct($enrolments, $start_ts = 0, $end_ts = false)
    {
        if (!$this->end_ts = $end_ts)
        {
            $this->end_ts = time();
        }
        if ($start_ts < gcr::startDateForApplication)
        {
            $this->start_ts = gcr::startDateForApplication;
        }
        else
        {
            $this->start_ts = $start_ts;
        }
        $this->setEnrolments($enrolments);
    }
    public function getEnrolments()
    {
        return $this->enrolments;
    }
    public function setEnrolments($enrolments)
    {
        $this->enrolments = array();
        foreach ($enrolments as $enrolment)
        {
            if ($mdl_course = $enrolment->getMdlCourse())
            {
                $ts = $mdl_course->startdate;
            }
            else
            {
                $ts = $enrolment->getObject()->timecreated;
            }
            // Just in case two start_dates happen to have identical timestamps
            while (array_key_exists($ts, $this->enrolments))
            {
                $ts++;
            }
            if ($ts >= $this->start_ts && $ts <= $this->end_ts)
            {
                $this->enrolments[$ts] = $enrolment;
            }
        }
        ksort($this->enrolments, SORT_NUMERIC);
    }
}