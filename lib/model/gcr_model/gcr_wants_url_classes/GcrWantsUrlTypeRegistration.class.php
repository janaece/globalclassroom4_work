<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrWantsUrlTypeRegistration
 *
 * @author ron
 */
class GcrWantsUrlTypeRegistration extends GcrWantsUrlType
{
    public function executeGetParamAction()
    {
        global $CFG;
        $this->setCookie();
    }
    public function executeCookieAction()
    {
        global $CFG;
        $reset_username = false;
        if (isset($_SESSION['resetusername']))
        {
            $reset_username = $_SESSION['resetusername'];
        }    
        if ((!$reset_username) && $CFG->current_app->isLoggedIn())
        {
            if (!$CFG->current_app->getCurrentUser()->requiresMembership())
            {
                self::unsetCookie();
                if ($this->wants_url->getWantsUrl() != $_SERVER['REQUEST_URI'])
                {
                    if ($url = $this->wants_url->getRedirectUrl())
                    {
                        $this->wants_url->delete();
                        $CFG->current_app->executeRedirect($url);
                    }
                }
            }
        }
    }
}
?>
