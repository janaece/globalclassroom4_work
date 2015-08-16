<?php

abstract class GcrMhrAvailabilityStatus extends GcrMhrTableRecord
{
    protected $user;

    public function __construct($status, $mhr_user)
    {
        $this->user = $mhr_user;
        parent::__construct($status, $this->user->getApp());
    }

    abstract public function showChat();
    abstract public function showOnline();
    abstract public function showPopup();
    abstract public function isSetByUser();
    abstract public function getDisplayColor();

    protected static function getClassInstance($status, $user)
    {
        $classname = 'GcrMhrAvailabilityStatus' . ucFirst($status->short_name);
        return new $classname($status, $user);
    }
    public static function getInstance($mhr_user)
    {
        $app = $mhr_user->getApp();
        if ($mhr_user->isLoggedIn())
        {
            if ($mhr_user_availability_status = $app->selectFromMhrTable('gcr_user_availability_status',
                    'user_id', $mhr_user->getObject()->id, true))
            {
                $status = $app->selectFromMhrTable('gcr_availability_status',
                        'id', $mhr_user_availability_status->status_id, true);
            }
            else
            {
                $status = $app->selectFromMhrTable('gcr_availability_status', 'short_name', 'available', true);
            }
        }
        else
        {
            $status = $app->selectFromMhrTable('gcr_availability_status', 'short_name', 'offline', true);
        }
        return self::getClassInstance($status, $mhr_user);
    }
    protected function deleteStatus()
    {
        $this->app->deleteFromMhrTable('gcr_user_availability_status', 'user_id', $this->user->getObject()->id);
    }
    protected function storeStatus()
    {
        $this->app->insertIntoMhrTable('gcr_user_availability_status',
                array('user_id' => $this->user->getObject()->id, 'status_id' => $this->obj->id));
    }

    public function setStatus($status)
    {
        if ($status->short_name != $this->obj->short_name)
        {
            $new_availability_status = self::getClassInstance($status, $this->user);
            try
            {
                $this->app->beginTransaction();
                $this->deleteStatus();
                $new_availability_status->storeStatus();
                $this->app->commitTransaction();
            }
            catch (Doctrine_Exception $e)
            {
                $this->app->rollbackTransaction();
                global $CFG;
                $CFG->current_app->gcError($e, 'gcdatabaseerror');
            }
            return $new_availability_status;
        }
    }
    public function getIcon()
    {
        return self::getStatusTypeIcon($this->user->getApp(), $this->obj->short_name);
    }
    public static function getStatusTypeIcon($app, $short_name)
    {
        return $app->getUrl() . '/images/icons/gc-' . $short_name .
                '-status.png';
    }
    public function getOnlineUsersHtml()
    {
        include(gcr::webDir . '/lib/templates/online_user_row.php');
    }
}


?>