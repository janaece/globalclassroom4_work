<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMhrAvailabilityStatusAvailable
 *
 * @author ron
 */
class GcrMhrAvailabilityStatusAvailable extends GcrMhrAvailabilityStatus
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
        return true;
    }
    public function isSetByUser()
    {
        return true;
    }
    public function deleteStatus()
    {
        // do nothing, available is implied when no record exists
    }
    public function  storeStatus()
    {
        // do nothing, available is implied when no record exists
    }
    public function getDisplayName()
    {
        return 'Available';
    }
    public function getDisplayColor()
    {
        return '#44B544';
    }
}
?>
