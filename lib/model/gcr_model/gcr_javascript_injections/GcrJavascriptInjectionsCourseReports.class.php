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
class GcrJavascriptInjectionsCourseReports extends GcrJavascriptInjectionsBase
{
    public function getHtml()
    {
        global $CFG, $COURSE;
        $url = $CFG->current_app->getAppUrl();
        $id = $COURSE->id;
        $html = 
        '<li class="type_unknown contains_branch collapsed">' .
            '<p class="tree_item branch">' .
                '<span tabindex="0">Reports</span>' .
            '</p>' .
            '<ul>' .
                '<li class="type_setting collapsed item_with_icon">' .
                    '<p class="tree_item leaf">' .
                        '<a href="' . $url . '/report/log/index.php?id=' . $id . '" title="Logs">' .
                            '<img class="smallicon navicon" src="' . $url . '/theme/image.php?theme=globalclassroom&image=i%2Freport&rev=713" title="" alt="">' .
                            'Logs' .
                        '</a>' .
                    '</p>' .
                '</li>' .
                '<li class="type_setting collapsed item_with_icon">' .
                    '<p class="tree_item leaf">' .
                        '<a href="' . $url . '/report/loglive/index.php?id=' . $id . '&inpopup=1">' .
                            '<img class="smallicon navicon" src="' . $url . '/theme/image.php?theme=globalclassroom&image=i%2Freport&rev=713" title="" alt="">' .
                            'Live logs' .
                        '</a>' .
                    '</p>' .
                '</li>' .
                '<li class="type_setting collapsed item_with_icon">' .
                    '<p class="tree_item leaf">' .
                        '<a href="' . $url . '/report/outline/index.php?id=' . $id . '" title="Activity report">' .
                            '<img class="smallicon navicon" src="' . $url . '/theme/image.php?theme=globalclassroom&image=i%2Freport&rev=713" title="" alt="">' .
                            'Activity report' .
                        '</a>' .
                    '</p>' .
                '</li>' .
                '<li class="type_setting collapsed item_with_icon">' .
                    '<p class="tree_item leaf">' .
                        '<a href="' . $url . '/report/participation/index.php?id=' . $id . '" title="Course participation">' .
                            '<img class="smallicon navicon" src="' . $url . '/theme/image.php?theme=globalclassroom&image=i%2Freport&rev=713" title="" alt="">' .
                            'Course participation' .
                        '</a>' .
                    '</p>' .
                '</li>' .
            '</ul>' .
        '</li>';

        return $html;
    }
    public function getJavaScript()
    {
        return 'jQuery(\'' . $this->getSelectors() . '\').parent().parent().parent().append(\'' . $this->getHtml() . '\');';
    }
    public function checkConstraints()
    {
        global $CFG, $COURSE;
        if ($CFG->current_app->isMoodle())
        {
            if (isset($COURSE->id))
            {
                $course = new GcrMdlCourse($COURSE, $CFG->current_app);
                $context = $course->getContext();
                
                if ($context)
                {
                    return ($CFG->current_app->hasPrivilege('EschoolAdmin') || 
                            $CFG->current_app->hasCapability('moodle/site:viewreports', $context));
                }
            }
        }
        return false;
    }
    protected function getSelectors()
    {
        return '.block_settings :contains(Course administration) .tree_item :contains(Users):first';
    }
    
}

?>
