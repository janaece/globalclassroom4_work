<?php
/**
 * Description of GcrEschoolAccountTable
 *
 * @author Ron Stewart
 */
class GcrUserCourseHistoryTable extends GcrCourseHistoryTable
{
    public function __construct($user, $start_ts, $end_ts, $admin, $owner, 
            $is_html = true, $include_disabled = true)
    {
        if (!$end_ts)
        {
            $end_ts = time();
        }
        $this->start_ts = $start_ts;
        $this->end_ts = $end_ts;
        $this->admin = $admin;
        $this->owner = $owner;
        $this->user = $user;
        $this->course_history = new GcrUserCourseHistory($this->user, $this->start_ts,
                $this->end_ts, $include_disabled);
        $this->table = new GcrTable(array(), array('id' => 'gc_course_history',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->current_item_amounts = array();
        $this->totals = array('average_grade' => 0, 'record_count' => 0);
        $this->is_html = $is_html;
        $this->buildTable();
    }
}
?>
