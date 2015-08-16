<?php

/**
 * Description of GcrJavascriptInjectionsProfileBlockHeader
 *
 * @author ron
 */
class GcrJavascriptInjectionsProfileBlockHeader 
{
    public function getHtml()
    {
        global $COURSE;
        return htmlspecialchars(trim($COURSE->fullname), ENT_QUOTES);
    }
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').html(\'' . $this->getHtml() . '\');';
    }
    public function checkConstraints()
    {
        global $CFG;
        return $CFG->current_app->isMoodle();
    }
    protected function getSelectors()
    {
        return '.block_course_profile h2';
    }
}

?>
