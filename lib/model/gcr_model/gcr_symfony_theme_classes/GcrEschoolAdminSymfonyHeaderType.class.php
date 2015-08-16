<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrEschoolAdminSymfonyHeaderType
 *
 * @author ron
 */
class GcrEschoolAdminSymfonyHeaderType extends GcrSymfonyHeaderType
{
    public function getNavDivs()
    {
        $content = '';
        if ($this->institution)
        {
            $content .= '<div id="main-nav">';
            $content .= '<ul>';
            $content .= '
            <li><a href="'.$this->institution->getAppUrl().'artefact/eschooladmin/settings.php">Configure Site</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/users/search.php">Manage Users</a></li>
            <li><a href="'.$this->institution->getAppUrl().'admin/users/institutions.php">Manage Institutions</a></li>
            <li><a href="'.$this->institution->getAppUrl().'">Return To Site</a></li>';
            $content .= '</ul></div><div id="sub-nav"></div>';
        }
        return $content;
    }
}
?>
