<?php
/**
 * Description of GcrEschoolAccountTable
 *
 * @author Ron Stewart
 */
class GcrEclassroomManagementTable
{
    protected $table;
    protected $institution;
    protected $column_functions;
    protected $current_item_amounts;
    protected $totals;

    public function __construct($institution)
    {
        $this->institution = $institution;
        $this->table = new GcrTable(array(), array('id' => 'gc_eclassroom_management',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->current_item_amounts = array();
        $this->totals = array('total_courses' => 0, 'total_sales' => 0, 'record_count' => 0);
        $this->buildTable();
    }
    protected function buildTable()
    {
        $this->setColumns();
        foreach ($this->institution->getEclassrooms() as $eclassroom)
        {
            $mhr_user =  $eclassroom->getUser();
            if ($mhr_user->isDeleted())
            {
                continue;
            }
            $this->setTotals($eclassroom);
            $columns = $this->table->getColumns();
            for ($i = 0; $i < $this->table->getColumnCount(); $i++)
            {
                $function = $this->column_functions[$i];
                $columns[$i]->addCell($this->$function($eclassroom));
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
        $user = new GcrTableColumn();
        $user_header = new GcrTableCell(array(), 'User', true);
        $user->addCell($user_header);
        $this->column_functions[] = 'getUserCell';
        $this->table->addColumn($user);
        
        $catalog = new GcrTableColumn();
        $catalog_header = new GcrTableCell(array(), 'Catalog', true);
        $catalog->addCell($catalog_header);
        $this->column_functions[] = 'getCatalogCell';
        $this->table->addColumn($catalog);

        $mhr_institution = new GcrTableColumn();
        $mhr_institution_header = new GcrTableCell(array(), 'Institution', true);
        $mhr_institution->addCell($mhr_institution_header);
        $this->column_functions[] = 'getMhrInstitutionCell';
        $this->table->addColumn($mhr_institution);

        $status = new GcrTableColumn();
        $status_header = new GcrTableCell(array(), 'Status', true);
        $status->addCell($status_header);
        $this->column_functions[] = 'getStatusCell';
        $this->table->addColumn($status);

        $courses = new GcrTableColumn();
        $courses_header = new GcrTableCell(array(), 'Courses', true);
        $courses->addCell($courses_header);
        $this->column_functions[] = 'getCoursesCell';
        $this->table->addColumn($courses);

        $sales = new GcrTableColumn();
        $sales_header = new GcrTableCell(array(), 'Course Sales', true);
        $sales->addCell($sales_header);
        $this->column_functions[] = 'getSalesCell';
        $this->table->addColumn($sales);

        $suspend = new GcrTableColumn();
        $suspend_header = new GcrTableCell(array(), 'Access', true);
        $suspend->addCell($suspend_header);
        $this->column_functions[] = 'getSuspendCell';
        $this->table->addColumn($suspend);
    }
    protected function setTotals($eclassroom)
    {
        $sales = $eclassroom->getCourseSales();
        $this->current_item_amounts['sales'] = 0;
        foreach($sales as $sale)
        {
            $this->current_item_amounts['sales'] += $sale->getAmount();
        }
        $this->current_item_amounts['courses'] = $eclassroom->getCoursesCount();
        $this->totals['total_courses'] += $this->current_item_amounts['courses'];
        $this->totals['total_sales'] += $this->current_item_amounts['sales'];
        $this->totals['record_count']++;
    }
    public function getTotal($key)
    {
        return $this->totals[$key];
    }
    protected function getUserCell($eclassroom)
    {
        $content = '';
        $user_tooltip = '';
        $mhr_user = $eclassroom->getUser();
        if ($mhr_user)
        {
            $mhr_user_obj = $mhr_user->getObject();
            $user_tooltip = $mhr_user_obj->username . ', email: ' . $mhr_user_obj->email;
            $content .= '<a href="' . $mhr_user->getHyperlinkToProfile() . '" target="_blank">' . 
                    $mhr_user->getFullnameString() . '</a>';
        }
        return new GcrTableCell(array('title' => $user_tooltip), $content);
    }
    protected function getCatalogCell($eclassroom)
    {
        $content = '';
        $eschool = $eclassroom->getEschool();
        $content .= '<a href="' . $eschool->getAppUrl() . '" target="_blank">' . 
                $eschool->getFullName() . '</a>';
        return new GcrTableCell(array('title' => $user_tooltip), $content);
    }
    protected function getMhrInstitutionCell($eclassroom)
    {
        $content = '';
        $mhr_institution = $eclassroom->getMhrInstitution();
        if ($mhr_institution)
        {
            $content .= '<a href="' . $this->institution->getAppUrl() . 'institution?institution=' . 
                    $mhr_institution->name . '">' . $mhr_institution->displayname . '</a>';
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getStatusCell($eclassroom)
    {
        $content = 'Free';
       
        $next_payment_ts = $eclassroom->getNextPaymentDate();
        if ($next_payment_ts < time())
        {
            $content = 'Overdue';
        }
        else
        {
            $content = 'Paid';
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getCoursesCell($eclassroom)
    {
        $mhr_user = $eclassroom->getUser();
        $content = '<a href="' . $this->institution->getUrl() . '/institution/eclassroom?id=' . 
                $mhr_user->getObject()->id . '">' . $this->current_item_amounts['courses'] . ' Courses</a>';
        return new GcrTableCell(array(), $content);
    }
    protected function getSalesCell($eclassroom)
    {
        $mhr_user = $eclassroom->getUser();
        $content = '<a href="' . $this->institution->getUrl() . '/account/view?user=' . $mhr_user->getObject()->id . 
                '" target="_blank">' . GcrPurchaseTable::gc_format_money($this->current_item_amounts['sales']) . '</a>';
        return new GcrTableCell(array(), $content);
    }
    protected function getSuspendCell($eclassroom)
    {
        global $CFG;
        
        $suspended = $eclassroom->getSuspended();
        if ($suspended == 't')
        {
            $action_text = 'Reactivate';
            $status_text = 'Suspended';
            $add_class = ' class="cautiontext"';
        }
        else
        {
            $action_text = 'Supend';
            $status_text = 'Active';
        }
        $content = '<span style="float:left"' . $add_class . '>' . $status_text . '</span>' .
                    '<a style="float:right" href="' . $CFG->current_app->getUrl() . 
                    '/institution/suspendEclassroom?id=' . $eclassroom->getId() .
                    '"><button>' . $action_text . '</button></a>';
        if ($CFG->current_app->hasPrivilege('GCUser'))
        {
            $content .= '<button onclick="confirmClick(\'Are you sure you want to delete this eClassroom? (This action is not reversable)\', \'' . 
                        $CFG->current_app->getUrl() . '/institution/deleteEclassroom?id=' . 
                        $eclassroom->getId() . '\')" style="float:right">Delete</button>';
        }
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
