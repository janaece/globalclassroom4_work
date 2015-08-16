<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrWantsUrlTypeMoodle
 *
 * @author ron
 */
class GcrWantsUrlTypeSimple extends GcrWantsUrlType
{
    public function executeGetParamAction()
    {
        global $CFG;
        if ($url = $this->wants_url->getRedirectUrl())
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
