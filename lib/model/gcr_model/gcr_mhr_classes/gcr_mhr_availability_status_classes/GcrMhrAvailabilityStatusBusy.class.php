<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMhrAvailabilityStatusBusy
 *
 * @author ron
 */
class GcrMhrAvailabilityStatusBusy extends GcrMhrAvailabilityStatus
{
    public function showChat()
    {
        return true;
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
        return 'Busy';
    }
    public function getDisplayColor() 
    {
        return '#8D030C';
    }
}
?>
