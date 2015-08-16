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
class GcrJavascriptInjectionsParticipants extends GcrJavascriptInjectionsBase
{
    public function getHtml()
    {
        global $CFG, $COURSE;
        $html = '<li class="type_setting collapsed item_with_icon">';
        $html .= '<p class="tree_item leaf">';
        $html .= '<a href="' . $CFG->current_app->getAppUrl() . '/user/index.php?id=' . $COURSE->id . '" title="Participants">';
        $html .= '<img class="smallicon navicon" src="' . $CFG->current_app->getUrl() . '/images/icons/user-search.png" title="Participants" alt="Participants">';
        $html .= 'Participants</a></p></li>';
        return $html;
    }
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').first().prepend(\'' . $this->getHtml() . '\');';
    }
    public function checkConstraints()
    {
        global $CFG, $COURSE;
        return (isset($COURSE) && $CFG->current_app->isMoodle() && 
                $CFG->current_app->hasPrivilege('EclassroomUser'));
    }
    protected function getSelectors()
    {
        return '.block_settings .block_tree_box .type_course .type_unknown > ul';
    }
    
}

?>
