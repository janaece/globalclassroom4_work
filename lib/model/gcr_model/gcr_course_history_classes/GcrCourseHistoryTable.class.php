<?php
/**
 * Description of GcrEschoolAccountTable
 *
 * @author Ron Stewart
 */
class GcrCourseHistoryTable
{
    protected $table;
    protected $user;
    protected $is_html;
    protected $course_history;
    protected $start_ts;
    protected $end_ts;
    protected $admin;
    protected $owner;
    protected $column_functions;
    protected $current_item_amounts;
    protected $totals;

    public function __construct($enrolments, $user, $start_ts, $end_ts,
            $admin, $owner, $is_html = true)
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
        $this->course_history = new GcrCourseHistory($enrolments, $this->start_ts, $this->end_ts);
        $this->table = new GcrTable(array(), array('id' => 'gc_course_history',
            'class' => 'tablesorter', 'cellspacing' => '1'), false, true);
        $this->column_functions = array();
        $this->current_item_amounts = array();
        $this->totals = array('average_grade' => 0, 'record_count' => 0);
        $this->is_html = $is_html;
        $this->buildTable();
    }
    protected function buildTable()
    {
        $this->setColumns();
        foreach ($this->course_history->getEnrolments() as $ts => $enrolment)
        {
            $this->setTotals($enrolment);
            $columns = $this->table->getColumns();
            for ($i = 0; $i < $this->table->getColumnCount(); $i++)
            {
                $function = $this->column_functions[$i];
                $columns[$i]->addCell($this->$function($ts, $enrolment));
            }
        }
    }
    protected function setColumns()
    {
        $course_start_date = new GcrTableColumn();
        $course_start_date_header = new GcrTableCell(array(), 'Course Start Date', true);
        $course_start_date->addCell($course_start_date_header);
        $this->column_functions[] = 'getCourseStartDateCell';
        $this->table->addColumn($course_start_date);

        $course = new GcrTableColumn();
        $course_header = new GcrTableCell(array(), 'Course', true);
        $course->addCell($course_header);
        $this->column_functions[] = 'getCourseCell';
        $this->table->addColumn($course);

        $instructor = new GcrTableColumn();
        $instructor_header = new GcrTableCell(array(), 'Instructor', true);
        $instructor->addCell($instructor_header);
        $this->column_functions[] = 'getInstructorCell';
        $this->table->addColumn($instructor);

        $credits = new GcrTableColumn();
        $credits_header = new GcrTableCell(array(), 'Credits', true);
        $credits->addCell($credits_header);
        $this->column_functions[] = 'getCreditsCell';
        $this->table->addColumn($credits);

        $grade = new GcrTableColumn();
        $grade_header = new GcrTableCell(array(), 'Grade %', true);
        $grade->addCell($grade_header);
        $this->column_functions[] = 'getGradeCell';
        $this->table->addColumn($grade);

        $grade_letter = new GcrTableColumn();
        $grade_letter_header = new GcrTableCell(array(), 'Result', true);
        $grade_letter->addCell($grade_letter_header);
        $this->column_functions[] = 'getGradeLetterCell';
        $this->table->addColumn($grade_letter);

        $include_in_report = new GcrTableColumn();
        $include_in_report_header = new GcrTableCell(array(), 'Include', true);
        $include_in_report->addCell($include_in_report_header);
        $this->column_functions[] = 'getIncludeInReportCell';
        $this->table->addColumn($include_in_report);
    }
    protected function setTotals($enrolment)
    {
        $old_count = $this->totals['record_count'];
        $this->totals['record_count']++;
        if ($grade_data = $enrolment->getGradeData())
        {
            $this->totals['average_grade'] = ($grade_data->finalgrade +
                    $this->totals['average_grade'] * $old_count) / $this->totals['record_count'];
        }
    }
    public function getAverageGradeLetter()
    {
        $grade_letters = array('93'=>'A', '90'=>'A-', '87'=>'B+', '83'=>'B', '80'=>'B-',
                '77'=>'C+', '73'=>'C', '70'=>'C-', '67'=>'D+', '60'=>'D', '0'=>'F');
        return GcrEschoolTable::getLetterGrade($this->totals['average_grade'], $grade_letters);
    }
    public function getTotal($key)
    {
        return $this->totals[$key];
    }
    public function getTable() 
    {
        return $this->table;
    }
    protected function getCourseStartDateCell($ts, $enrolment)
    {
        $content = '';
        if ($this->is_html)
        {
            $content .= '<span style="display:none">' . $ts . '</span>';
            $content .= date('M d, Y', $ts);
        }
        else
        {
            $content .= date('m/d/Y', $ts);
        }
        return new GcrTableCell(array('class' => 'transactionDate'), $content);
    }
    protected function getCourseCell($ts, $enrolment)
    {
        $content = '';
        $course = $enrolment->getCourse();
        if ($course)
        {
            
            $mdl_course = $course->getObject();
            $content = "$mdl_course->fullname ($mdl_course->shortname)";
            if (strlen($content) > 50)
            {
                $content = $mdl_course->fullname;
            }
            if (GcrEschoolTable::authorizeCourseAccess($course))
            {
                global $CFG;
                $content = '<a href="' . $course->getUrl() . 
                        '?transfer=' . $CFG->current_app->getShortName() . '">' . $content . '</a>';
            }
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getCreditsCell($ts, $enrolment)
    {
        $content = 'N/A';
        if ($course = $enrolment->getCourse())
        {
            if ($credit_hours = $course->getCreditHours())
            {
                $content = $credit_hours;
            }
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getInstructorCell($ts, $enrolment)
    {
        $content = '';
        if ($mhr_user = $enrolment->getInstructor())
        {
            $content = $mhr_user->getFullNameString();
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getGradeCell($ts, $enrolment)
    {
        $content = '';
        if ($grade_data = $enrolment->getGradeData())
        {
            $content = number_format($grade_data->finalgrade, 1, '.', '') . '%';
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getGradeLetterCell($ts, $enrolment)
    {
        $content = '';
        if ($letter = $enrolment->getGradeLetter())
        {
            $content = $letter;
        }
        return new GcrTableCell(array(), $content);
    }
    protected function getIncludeInReportCell($ts, $enrolment)
    {
        $eschool = $enrolment->getApp();
        $content = '<input name="' . $eschool->getShortName() . '&' . $enrolment->getObject()->id .
                '" type="checkbox" checked="checked"></input>';
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
    public function printTableAsPdf()
    {
        global $CFG;
        define('K_PATH_CACHE', $CFG->dataroot . '/temp/');
        require_once(gcr::webDir . 'lib/tcpdf/config/lang/eng.php');
        require_once(gcr::webDir . 'lib/tcpdf/tcpdf.php');
        $app = $this->user->getApp();
        
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator($app->getFullName());
        $pdf->SetAuthor($app->getFullName());
        $pdf->SetTitle('Student Transcript');
        $pdf->SetSubject($this->user->getFullNameString());
        $pdf->SetKeywords('transcript, course, grade');

        $address = $app->getAddressObject();
        $person2 = $app->getPerson2Object();

        $header_string = $person2->getFullName() . "\n";
        $header_string .= $address->getStreet1() . "\n";
        if ($address->getStreet2() != '')
        {
            $header_string .= $address->getStreet2() . "\n";
        }
        $header_string .= $address->getCity() . ', ' . $address->getState() . ' ' . $address->getZipcode() . "\n";
        $header_string .= $person2->getPhone1() . "\n";
        $header_string .= $person2->getPhone2() . "\n";
        $header_string .= $person2->getEmail();
        
        // set default header data
        $pdf->SetHeaderData('../../../../../../..' . $app->getLogoFilePath(),
                30, trim(trim($app->getFullName())) . ' Student Transcript', 'Student: ' .
                trim($this->user->getFullNameString()) .  ', ' . $this->user->getObject()->email . "\nDate: " . date('m/d/Y', time()));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('helvetica', '', 8);

        // add a page
        $pdf->AddPage();
        $mdl_user_obj = $this->user->getObject();
        $html = '<b>Educational Provider Information:</b><br />';
        $html .= $app->getFullName() . '<br />';
        $html .= $person2->getFullName() . '<br />';
        $html .= $address->getStreet1() . '<br />';
        if ($address->getStreet2() != '')
        {
            $html .= $address->getStreet2() . "<br />";
        }
        $html .= $address->getCity() . ', ' . $address->getState() . ' ' . $address->getZipcode() . "<br />";
        $html .= $person2->getPhone1() . "<br />";
        $html .= $person2->getPhone2() . "<br />";
        $html .= $person2->getEmail() . '<br /><br />';
        
        $w = array(17, 65, 50, 12, 12, 23);

        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        // Data
        $fill = 0;
        $pdf->writeHTMLCell(array_sum($w), $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        $pdf->SetFont('helvetica', '', 8);

        $row_count = $this->table->getRowCount();
        $columns = $this->table->getColumns();
        
        // Colors, line width and bold font
        $pdf->SetFillColor(230, 238, 238);
        $pdf->SetDrawColor(221, 221, 221);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B', 8);
        // Header
        $pdf->Cell($w[0], 7, 'Date', 1, 0, 'L', 1);
        $pdf->Cell($w[1], 7, 'Course', 1, 0, 'L', 1);
        $pdf->Cell($w[2], 7, 'Instructor', 1, 0, 'L', 1);
        $pdf->Cell($w[3], 7, 'Credits', 1, 0, 'L', 1);
        $pdf->Cell($w[4], 7, 'Grade', 1, 0, 'L', 1);
        $pdf->Cell($w[5], 7, 'Result', 1, 0, 'L', 1);
        
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(240, 240, 246);
        $pdf->SetFont('helvetica', '', 8);
        // Data
        $fill = 0;

        // This convoluted code does the following for each table row:
        // 1. Break the table row contents into a multidimentional
        // array, $row[column][line], so that we can handle cells
        // which require multiple lines because they are too long.
        // 2. Print each column left to right, and repeat for each line
        // required for this row.
        for ($row = 1; $row < $row_count; $row++)
        {
            $row_content = array();
            $max_line_index = 0;
            for ($column = 0; $column < 6; $column++)
            {
               $max_chars = floor(.75 * $w[$column]);
               $cell_content = strip_tags($columns[$column]->getCells($row)->getContent());
               $column_line_count = ceil((strlen($cell_content) + .1) / $max_chars);
               if (($column_line_count - 1) > $max_line_index)
               {
                   $max_line_index = $column_line_count - 1;
               }
               for ($i = 0; $i < $column_line_count; $i++)
               {
                   $row_content[$column][$i] = substr($cell_content, $max_chars * $i, $max_chars);  
               }              
            }
            for ($i = 0; $i <= $max_line_index; $i++)
            {
                for ($column = 0; $column < 6; $column++)
                {
                    if (!isset($row_content[$column][$i]))
                    {
                        $row_content[$column][$i] = '';
                    }
                    $pdf->Cell($w[$column], 6, $row_content[$column][$i], 'LR', 0, 'L', $fill);
                }
                $pdf->Ln();
            }
            $fill=!$fill;
        }
        $pdf->SetFont('helvetica', '', 7);
        $html = '*Note: This transcript may not include all course records for the specified user. Neither Globalclassroom Inc. nor the educational provider can verify the accuracy of the grades listed and cannot be held responsible for any usage of this document or the information provided therein.';
        $pdf->Cell(array_sum($w), 0, '', 'T');
        $pdf->Ln();
        $pdf->writeHTMLCell(array_sum($w), $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('transcript.pdf', 'I');

    }
}
?>
