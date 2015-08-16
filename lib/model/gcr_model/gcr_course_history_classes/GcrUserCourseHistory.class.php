<?php
class GcrUserCourseHistory extends GcrCourseHistory
{
    protected $user;
    protected $start_ts;
    protected $end_ts;
    protected $include_disabled;

    public function __construct(GcrMhrUser $user, $start_ts = 0, $end_ts = false, $include_disabled = true, $role_id = 5)
    {
        $this->include_disabled = $include_disabled;
        $visible = ($include_disabled) ? false : 1;
        $this->user = $user;
        $enrolments = $user->getEnrolments(array(), $role_id, $visible);
        parent::__construct($enrolments, $start_ts, $end_ts);
    }
}