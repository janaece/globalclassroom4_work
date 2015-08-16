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
class GcrWantsUrlTypeMaharalogin extends GcrWantsUrlType
{
    public function executeGetParamAction()
    {
        global $CFG;
        $url = $this->wants_url->getRedirectUrl(); 
        if ($url && $CFG->current_app->isLoggedIn())
        {
            $this->wants_url->delete();
            $CFG->current_app->executeRedirect($url);
        }
    }
    public function executeSessionParamAction()
    {
        self::unsetCookie();
        $this->executeGetParamAction();
    }
}
?>