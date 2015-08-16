<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMhrAvailabilityStatusAway
 *
 * @author ron
 */
class GcrMhrAvailabilityStatusAway extends GcrMhrAvailabilityStatus
{
    public function showChat()
    {
        return false;
    }
    public function showOnline()
    {
        return true;
    }
    public function showPopup()
    {
        return false;
    }
    public function isSetByUser()
    {
        return true;
    }
    public function getDisplayName()
    {
        return 'Away';
    }
    public function getDisplayColor() 
    {
        return '#CB8229';
    }
}
?>
