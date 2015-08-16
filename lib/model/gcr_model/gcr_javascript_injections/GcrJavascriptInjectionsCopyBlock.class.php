<?php


/**
 * GcrJavascriptInjectionsCopyBlock
 *
 * @author ron
 */
class GcrJavascriptInjectionsCopyBlock extends GcrJavascriptInjectionsBase
{
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').attr(\'display\',\'none\');';
    }
    public function checkConstraints()
    {
        return true;
    }
    protected function getSelectors()
    {
        return '.copytextboxnote';
    }
    
}