<?php


/**
 * Description of GcrUserCourseHistoryWithEnrolTable
 *
 * @author Ron Stewart
 * @copyright (c) 2014, GlobalClassroom Inc.
 */
class GcrUserCourseHistoryWithEnrolTable extends GcrUserCourseHistoryTable 
{
    protected function setColumns()
    {
        parent::setColumns();
        $course_enrol_date = new GcrTableColumn();
        $course_enrol_date_header = new GcrTableCell(array(), 'Enrol Date', true);
        $course_enrol_date->addCell($course_enrol_date_header);
        $this->column_functions[] = 'getCourseEnrolDateCell';
        $this->table->addColumn($course_enrol_date);

        $eschool_short_name = new GcrTableColumn();
        $eschool_short_name_header = new GcrTableCell(array(), 'Catalog', true);
        $eschool_short_name->addCell($eschool_short_name_header);
        $this->column_functions[] = 'getEschoolShortNameCell';
        $this->table->addColumn($eschool_short_name);

        $institution_short_name = new GcrTableColumn();
        $institution_short_name_header = new GcrTableCell(array(), 'Platform', true);
        $institution_short_name->addCell($institution_short_name_header);
        $this->column_functions[] = 'getInstitutionShortNameCell';
        $this->table->addColumn($institution_short_name);

    }
    
    protected function getCourseEnrolDateCell($ts, $enrolment)
    {
        $content = date('m/d/Y', $enrolment->getObject()->timecreated);
        return new GcrTableCell(array(), $content);
    }
    protected function getEschoolShortNameCell($ts, $enrolment)
    {
        $content = $enrolment->getApp()->getShortName();
        return new GcrTableCell(array(), $content);
    }
    protected function getInstitutionShortNameCell($ts, $enrolment)
    {
        $content = $enrolment->getApp()->getInstitution()->getShortName();
        return new GcrTableCell(array(), $content);
    }
}
