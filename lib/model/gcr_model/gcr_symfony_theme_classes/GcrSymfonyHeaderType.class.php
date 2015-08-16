<?php

class GcrSymfonyHeaderType
{
    protected $current_user;
    protected $institution;
    protected $current_app;

    public function __construct($current_user = false)
    {
        global $CFG;
        $this->current_app = $CFG->current_app;
        $this->institution = false;
        if ($this->current_user = $current_user)
        {
            $this->institution = $current_user->getInstitution();
        }
    }
    public static function getInstance($current_user = false)
    {
        global $CFG;
        if (!$current_user)
        {
            $role = 'NoUser';
        }
        else if ($current_user->getRoleManager()->hasPrivilege('GCAdmin'))
        {
            $role = 'SiteAdmin';
        }
        else if (sfContext::getInstance()->getModuleName() == 'course' &&
                sfContext::getInstance()->getActionName() == 'view')
        {
            return new GcrUserSymfonyCourseViewHeaderType($current_user);
        }
        else if ($current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $role = 'EschoolAdmin';
        }
        else
        {
            $role = 'User';
        }
        if ($CFG->current_app->isMoodle())
        {
            $app = 'Mdl';
        }
        else
        {
            $app = 'Mhr';
        }
        $classname = 'Gcr' . $app . $role . 'SymfonyHeaderType';
        return new $classname($current_user);
    }
    public function getContent()
    {
        $content = $this->getPageHeaderDiv();
        $content .= $this->getNavDivs();
        return $content;
    }
    public function getPageHeaderDiv()
    {
        $content = $this->getPageHeaderDivStart();
        $chat_image_src = $this->current_user->getChatImageSrc();
        $chat_count = $this->current_user->getChatCount();
        if ($this->current_user && $this->institution)
        {
            $content .= '<div id="right-nav">';
            $content .= '<ul><li>';
            $content .= '<a href="'. $this->institution->getAppUrl() .'account/activity">';
            $content .= '<img src="'.$this->institution->getAppUrl() . 'theme/globalclassroom/static/images/email.gif" alt="Inbox">';
            $content .= '<span class="navcount unreadmessagecount">' . $this->current_user->getUnreadMessages()->count . "<span>";
            $content .= '</a> | </li>';
            $content .= '<li class="btn-logout"><a href="'.$this->institution->getAppUrl().'?logout" accesskey="l">Logout</a></li>';
            $content .= '</ul></div>';
            $content .= '<form id="usf" method="post" action="'.$this->institution->getAppUrl().'user/find.php">';
            $content .= '<input type="text" id="usf_query" name="query" tabindex="1" value="Search Users">';
            $content .= '<input type="submit" id="usf_submit" name="submit" tabindex="1" value="Go">';
            $content .= '</form>';
        }
        $content .= $this->getPageHeaderDivEnd();
        return $content;
    }
    public function getPageHeaderDivStart()
    {
        $content  = '<div id="symfony-container">';
        $content .= '<div id="page_header">';
        $content .= '<table id="header_table">';
        $content .= '<tr>';
        $content .= '<td id="header-left">';
        $content .= '<h1 id="site-logo"><a href="' . $this->current_app->getAppUrl() .'">';
        if($this->current_app->isMahara())
        {
            $content .= '<img id="banner" src="'. $this->current_app->getAppUrl() .'local/printBanner.php" alt="banner" />';
        }
        if($this->current_app->isMoodle())
        {
            $content .= '<img id="banner" src="'. $this->current_app->getAppUrl() .'/custom/printBanner.php" alt="banner" />';
        }
        $content .= '</a></h1>';
        $content .= '</td>';
        $content .= '<td id="header-center">';
        $content .= '</td>';
        $content .= '<td id="header-right">';
        return $content;
    }
    public function getPageHeaderDivEnd()
    {
        return '</td></tr></table></div>';
    }
    public function getNavDivs()
    {
        return '<div id="main-nav"><ul></div><div id="sub-nav"></div>';
    }
}

?>
