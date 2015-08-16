<?php

class GcrMdlSiteAdminSymfonyHeaderType extends GcrSymfonyHeaderType
{
    public function getPageHeaderDiv()
    {
        global $OUTPUT, $PAGE;
        $PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
        $content = parent::getPageHeaderDivStart();
        $content .= "<div class='headermenu'>" . $OUTPUT->login_info();
        if (!empty($PAGE->layout_options['langmenu']))
        {
            $this->content .= $OUTPUT->lang_menu();
        }
        $content .= $PAGE->headingmenu . "</div>";
        $content .= parent::getPageHeaderDivEnd();
        return $content;
    }
    public function getNavDivs()
    {
        global $OUTPUT, $PAGE;
        $content = '<div id="main-nav"> </div>';
        $hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
        $content .= '<div id="sub-nav"><div class="clearfix">';
        if ($hasnavbar)
        {
            $content .= '<div class="breadcrumb">' . $OUTPUT->navbar() .
                    '</div><div class="navbutton">' . $PAGE->button;
            if (!empty($PAGE->layout_options['langmenu']))
            {
                $content .= $OUTPUT->lang_menu();
            }
            $content .= '</div>';
        }
        $content .= '</div></div>';
        return $content;
    }
}

?>
