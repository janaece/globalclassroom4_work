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
class GcrJavascriptInjectionsChangeUsername extends GcrJavascriptInjectionsBase
{
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').attr(\'disabled\',\'disabled\');';
    }
    public function checkConstraints()
    {
        return true;
    }
    protected function getSelectors()
    {
        return '#accountprefs_username, #edituser_site_username';
    }
    
}

?>
