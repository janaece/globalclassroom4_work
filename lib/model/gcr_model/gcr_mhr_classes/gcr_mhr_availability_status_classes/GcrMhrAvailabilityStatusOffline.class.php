<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMhrAvailabilityStatusOffline
 *
 * @author ron
 */
class GcrMhrAvailabilityStatusOffline extends GcrMhrAvailabilityStatus
{
    public function showChat()
    {
        return false;
    }
    public function showOnline()
    {
        return false;
    }
    public function showPopup()
    {
        return false;
    }
    public function isSetByUser()
    {
        return false;
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
        return 'Offline';
    }
    public function getDisplayColor() 
    {
        return '#555655';
    }
}
?>
