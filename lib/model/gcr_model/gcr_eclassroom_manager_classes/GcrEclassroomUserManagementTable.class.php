<?php
/**
 * Description of GcrEschoolAccountTable
 *
 * @author Ron Stewart
 */
class GcrEclassroomUserManagementTable
{
    protected $table;
    protected $user;
    protected $column_functions;
    protected $current_item_amounts;
    protected $totals;

    public function __construct($user)
    {
        $this->user = $user;
        $this->table = new GcrTable(array(), array('id' => 'gc_eclassroom_user_table',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->current_item_amounts = array();
        $this->totals = array('total_enrolments' => 0, 'record_count' => 0);
        $this->buildTable();
    }
    protected function buildTable()
    {
        $this->setColumns();
        $courses = $this->user->getEclassroomCourses();
        foreach ($courses as $course)
        {
            $this->setTotals($course);
            $columns = $this->table->getColumns();
            for ($i = 0; $i < $this->table->getColumnCount(); $i++)
            {
                $function = $this->column_functions[$i];
                $columns[$i]->addCell($this->$function($course));
            }
        }
        foreach($columns as $column)
        {
            if (!$column->hasContent())
            {
                $column->setHidden(true);
            }
        }
    }
    protected function setColumns()
    {
        $course = new GcrTableColumn();
        $course_header = new GcrTableCell(array(), 'Course', true);
        $course->addCell($course_header);
        $this->column_functions[] = 'getCourseCell';
        $this->table->addColumn($course);
        
        $catalog = new GcrTableColumn();
        $catalog_header = new GcrTableCell(array(), 'Catalog', true);
        $catalog->addCell($catalog_header);
        $this->column_functions[] = 'getCatalogCell';
        $this->table->addColumn($catalog);

        $start_date = new GcrTableColumn();
        $start_date_header = new GcrTableCell(array(), 'Start Date', true);
        $start_date->addCell($start_date_header);
        $this->column_functions[] = 'getStartDateCell';
        $this->table->addColumn($start_date);

        $status = new GcrTableColumn();
        $status_header = new GcrTableCell(array(), 'Status', true);
        $status->addCell($status_header);
        $this->column_functions[] = 'getStatusCell';
        $this->table->addColumn($status);

        $enrolments = new GcrTableColumn();
        $enrolments_header = new GcrTableCell(array(), 'Enrolments', true);
        $enrolments->addCell($enrolments_header);
        $this->column_functions[] = 'getEnrolmentsCell';
        $this->table->addColumn($enrolments);

        $actions = new GcrTableColumn();
        $actions_header = new GcrTableCell(array(), '', true);
        $actions->addCell($actions_header);
        $this->column_functions[] = 'getActionsCell';
        $this->table->addColumn($actions);
    }
    protected function setTotals($course)
    {
        $this->current_item_amounts['enrolments'] = count($course->getActiveUsersInCourse());
        $this->totals['enrolments'] += $this->current_item_amounts['enrolments'];
        $this->totals['record_count']++;
    }
    public function getTotal($key)
    {
        return $this->totals[$key];
    }
    protected function getCourseCell($course)
    {
        $content = '';
        $course_obj = $course->getObject();
        $eschool = $course->getApp();
        $tooltip = $course->fullname . ' (' . $course->shortname . ')';
        $content .= '<a href="' . $eschool->getAppUrl() . '/course/view.php?id=' . 
                $course_obj->id . '" target="_blank">' . $course_obj->fullname . '</a>';
        return new GcrTableCell(array('title' => $tooltip), $content);
    }
    protected function getCatalogCell($course)
    {
        $content = '';
        $eschool = $course->getApp();
        $tooltip = $eschool->getFullName() . ' (' . $eschool->getShortName() . ')';
        $content .= '<a href="' . $eschool->getAppUrl() . '" target="_blank">' . 
                $eschool->getFullName() . '</a>';
        return new GcrTableCell(array('title' => $tooltip), $content);
    }
    protected function getStartDateCell($course)
    {
        $ts = $course->getObject()->startdate;
        $content = '<span style="display:none">' . $ts . '</span>' . date('M j, Y', $ts);
        return new GcrTableCell(array(), $content);
    }
    protected function getStatusCell($course)
    {
        $course_obj = $course->getObject();
        if ($course_obj->visible != 1)
        {
            $content = 'Disabled';
        }
        else
        {
            $content = 'Enabled';
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getEnrolmentsCell($course)
    {
        $eschool = $course->getApp();
        $content = '<a href="' . $eschool->getAppUrl() . '/enrol/users.php?id=' . 
                $course->getObject()->id . '">' . $this->current_item_amounts['enrolments'] . ' Enrolments</a>';
        return new GcrTableCell(array(), $content);
    }
    protected function getActionsCell($course)
    {
        global $CFG;
        $eschool = $course->getApp();
        $course_obj = $course->getObject();
        
        $content = '<span style="float:right"><a href="' . $CFG->current_app->getAppUrl() . 
                'artefact/courses/invite.php?courseid=' . $course_obj->id . '&eschoolid=' . 
                $eschool->getId() . '"><button>Invite Users</button></a>' .
                ' <a href="' . $eschool->getAppUrl() . '/course/edit.php?id=' . $course_obj->id . '&transfer=' . 
                $CFG->current_app->getShortName() . '"><button>Edit</button></a></span>';
        return new GcrTableCell(array(), $content);
    }
    public function getHTML()
    {
        return $this->table->getHTML();
    }
    public function printTable()
    {
        print $this->getHTML();
    }
}
?>
