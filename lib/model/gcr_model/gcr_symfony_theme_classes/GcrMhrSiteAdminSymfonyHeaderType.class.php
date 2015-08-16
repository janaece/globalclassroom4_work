<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMhrSiteAdminSymfonyHeaderType
 *
 * @author ron
 */
class GcrMhrSiteAdminSymfonyHeaderType extends GcrSymfonyHeaderType
{
    public function getNavDivs()
    {
        $content = '';
        if ($this->institution)
        {
            $content .= '<div id="main-nav">';
            $content .= '<ul>';
            $content .= '
            <li><a href="'.$this->institution->getAppUrl().'admin">Admin home</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/site/options.php">Configure Site</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/users/search.php">Users</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/groups/groups.php">Groups</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/users/institutions.php">Institutions</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/extensions/plugins.php">Extensions</a></li>
            <li><a href="'.$this->institution->getAppUrl().'">Return to Site</a></li>';
            $content .= '</ul></div><div id="sub-nav"></div>';
        }
        return $content;
    }
}
?>
