<?php
// GCMhrUser class.
// Ron Stewart
// Feb 13, 2011
//
// This is a wrapper class for the Mahara $USER object.
class GcrCurrentMhrUser extends GcrMhrUser
{
    protected $user_storage;
    
    function __construct ()
    {
        global $USER, $CFG;
        $id = $USER->get('id');
        $mhr_user_object = false;

        if ($id)
        {
            $mhr_user_object = $CFG->current_app->selectFromMhrTable('usr', 'id', $id, true);   
        }

        parent::__construct($mhr_user_object, $CFG->current_app);
        $role_manager = $this->getRoleManager();
        $role_manager->setPermissionsOnRoles();
        if ($role_manager->hasPrivilege('Student'))
        {
            $this->setAccessForMnetEschools();
        }
    }

    public function getAuthInstance ()
    {
        if ($this->isLoggedIn())
        {
            return parent::getAuthInstance();
        }
    }

    public function getUserStorage()
    {
        if (!isset($this->user_storage))
        {
            $this->user_storage = new GcrUserStorageAccessS3();
        }
        return $this->user_storage;
    }
    public function isLoggedIn ()
    {
        return is_logged_in();
    }

    public function requiresMembership ()
    {
        if ($var = $this->app->getConfigVar('gc_force_membership'))
        {
            if ($var == 'on')
            {
                if (!$this->getRolemanager()->hasPrivilege('GCUser'))
                {
                    if (!$this->isMember())
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function logout ()
    {
        global $USER;

        if (isset($_COOKIE['gc_eschools']))
        {
            $short_names = explode(';', $_COOKIE['gc_eschools']);

            foreach ($short_names as $short_name)
            {
                if ($short_name == '')
                {
                    continue;
                }

                if ($eschool = Doctrine::getTable('GcrEschool')->findOneByShortName($short_name))
                {
                    if ($mdl_user = $this->getUserOnEschool($eschool))
                    {
                        $sql = 'select id, timecreated from ' . $eschool->getShortName() . 
                                '.mdl_sessions where userid = ? order by timecreated DESC';
                        $mdl_session = $eschool->gcQuery($sql, array($mdl_user->getObject()->id), true);

                        if ($mdl_session)
                        {
                            $eschool->updateMdlTable('sessions', array('sid' => time() . 'logout' .
                                    GcrEschoolTable::generateRandomString(15)), array('id' => $mdl_session->id));
                        }
                    }
                }
            }

            setcookie('gc_eschools', '', time() - 65536,
                      ini_get('session.cookie_path'),
                      ini_get('session.cookie_domain'),
                      ini_get('session.cookie_secure'),
                      ini_get('session.cookie_httponly'));
        }

        $USER->logout();
    }
}