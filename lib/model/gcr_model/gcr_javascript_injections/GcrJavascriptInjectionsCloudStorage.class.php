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
class GcrJavascriptInjectionsCloudStorage extends GcrJavascriptInjectionsBase
{
    public function getHtml()
    {
        global $CFG;
        $user = $CFG->current_app->getCurrentUser()->getUserOnInstitution();
        $html = '<li class="type_setting collapsed item_with_icon">';
        $html .= '<p class="tree_item leaf">';
        $html .= '<a href="' . $user->getApp()->getUrl() . '/institution/viewUserStorage" target="_blank" title="Cloud Storage">';
        $html .= '<img class="smallicon navicon" src="' . $CFG->current_app->getUrl() . '/images/icons/cloudstorage.png" title="" alt="">';
        $html .= 'Cloud Storage</a></p></li>';
        return $html;
    }
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').append(\'' . $this->getHtml() . '\');';
    }
    public function checkConstraints()
    {
        global $CFG;
        return ($CFG->current_app->hasPrivilege('Student') && (!$CFG->current_app->hasPrivilege('GCAdmin')));
    }
    protected function getSelectors()
    {
        return '.block_settings .block_tree_box .type_course > ul';
    }
    
}

?>
