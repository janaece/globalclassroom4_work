<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrJavascriptInjectionsCloudStorage
 *
 * @author ron
 */
class GcrJavascriptInjectionsRepairCourse extends GcrJavascriptInjectionsBase
{
    public function getHtml()
    {
        global $CFG, $COURSE;
        $html = '<li class="type_setting collapsed item_with_icon">';
        $html .= '<p class="tree_item leaf">';
        $html .= '<a href="' . $CFG->current_app->getUrl() . '/course/repair?course=' . $COURSE->id . '" title="Repair Course">';
        $html .= '<img class="smallicon navicon" src="' . $CFG->current_app->getUrl() . '/images/icons/repaircourse.png" title="Repair Course" alt="">';
        $html .= 'Repair</a></p></li>';
        return $html;
    }
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').append(\'' . $this->getHtml() . '\');';
    }
    public function checkConstraints()
    {
        global $CFG, $COURSE;
        return (isset($COURSE) && $CFG->current_app->isMoodle() && 
                $CFG->current_app->hasPrivilege('EclassroomUser'));
    }
    protected function getSelectors()
    {
        return '.block_settings .block_tree_box .type_course > ul';
    }
    
}

?>
