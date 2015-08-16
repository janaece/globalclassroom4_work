<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrSymfonyFooter
 *
 * @author ron
 */
class GcrSymfonyFooter
{
    protected $institution;

    public function __construct($institution)
    {
        $this->institution = $institution;
    }
    public function printFooter()
    {
        $institution = $this->institution;
        $content  = '<div id="footer-wrap">';
        $footer_links = $institution->selectFromMhrTable('config', 'field', 'footerlinks', true);
        $content .= '<div id="footernavleft"><a href="' . $institution->getSupportUrl() . '" target="_blank">Technical Support</a></div>';
        $content .= '<div id="footernav">';
        
        if($pos = strpos($footer_links->value, 'termsandconditions'))
        {
            $content .= '<a href="' . $institution->getAppUrl() . 'terms.php">Terms and Conditions</a>';
            $content .= '|';
        }
        if($pos = strpos($footer_links->value, 'privacystatement'))
        {
            $content .= '<a href="' . $institution->getAppUrl() . 'privacy.php">Privacy Statement</a>';
            $content .= '|';
        }
        if($pos = strpos($footer_links->value, 'about'))
        {
            $content .= '<a href="' . $institution->getAppUrl() . 'about.php">About</a>';
            $content .= '|';
        }
        if($pos = strpos($footer_links->value, 'contactus'))
        {
            $content .= '<a href="http://' . gcr::domainName . '/info/contact">Contact Us</a>';
        }
        $content .= '</div>';
        $content .= '<div id="poweredby">';
        $content .= '<a href="http://globalclassroom.us">';
        $content .= '<img src="https://s3.amazonaws.com/static.globalclassroom.us/marketing/Stratus/poweredby_blk-trans.png" alt="powered by globalclassroom" />';
        $content .= '</a></div>';
        $content .= '</div>';

        // turn background black if site admin
        $app = gcr::getApp();
        $current_user = $app->getCurrentUser();
        if ($current_user)
        {
            $role_manager = $current_user->getRoleManager();
            if ($role_manager->hasPrivilege('GCAdmin'))
            {
                $content .= '<script type="text/javascript">';
                $content .= 'jQuery("body").css("background", "#000")';
                $content .= '</script>';
            }
        }

        print $content;
    }
}
?>
