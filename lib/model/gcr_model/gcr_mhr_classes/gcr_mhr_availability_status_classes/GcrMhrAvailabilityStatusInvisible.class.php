<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMhrAvailabilityStatusInvisible
 *
 * @author ron
 */
class GcrMhrAvailabilityStatusInvisible extends GcrMhrAvailabilityStatus
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
        return true;
    }
    public function getDisplayName()
    {
        return 'Offline';
    }
    public function getIcon()
    {
        return self::getStatusTypeIcon($this->user->getApp(), 'offline');
    }
    public function getDisplayColor() 
    {
        return '#555655';
    }

}
?>
