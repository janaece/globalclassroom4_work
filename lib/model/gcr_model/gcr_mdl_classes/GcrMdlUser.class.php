<?php
// User class.
// Ron Stewart
// September 10, 2010
//
// This class represents a user from a mdl_user table of a given $this->app, and offers methods
// to manipulate this moodle user, without the need to actually be logged in to that user's eschool.
class GcrMdlUser extends GcrMdlTableRecord
{
    protected $role_manager;
    protected $mhr_user;
    
    public function  __construct($obj, $eschool) 
    {
        parent::__construct($obj, $eschool);
        $this->role_manager = new GcrUserRoleManager($this);
    }
    public function addMessageToInbox($from_mdl_user, $subject, $body_text, $body_html = '')
    {
        if ($to_mhr_user = $this->getUserOnInstitution())
        {
            if ($from_mdl_user->getObject()->mnethostid == $this->obj->mnethostid)
            {
                $from_mhr_user = $from_mdl_user->getUserOnInstitution();
            }
            else
            {
                $from_mhr_user = false;
            }
            return $to_mhr_user->addMessageToInbox($subject, $body_text, $body_html, $from_mhr_user);
        }
        return false;
    }
    public function getAccountManager()
    {
        return $this->getUserOnInstitution()->getAccountManager();
    }
    public function getUserOnEschool($eschool, $create_user = false)
    {
        if ($mhr_user = $this->getUserOnInstitution())
        {
            return $mhr_user->getUserOnEschool($eschool, $create_user);
        }
        return false;
    }
    // This function returns a user's institution.
    public function getInstitution()
    {
        if ($this->obj->mnethostid != $this->app->getSelfMdlMnetHostRecord()->id)
        {
            return $this->app->getMnetInstitution($this->obj->mnethostid);
        }
        return false;
    }
    public function getRoleManager()
    {
        return $this->role_manager;
    }
    public function getUserOnInstitution()
    {
        if (!isset($this->mhr_user))
        {
            $institution = $this->getInstitution();
            if ($institution)
            {
                $this->mhr_user = $institution->getUser($this);
            }
        }
        return $this->mhr_user;
    }
    public function getChatImageSrc()
    {
        return $this->getUserOnInstitution()->getChatImageSrc();
    }
    public function getChatCount()
    {
        return $this->getUserOnInstitution()->getChatCount();
    }
    public function getContext()
    {
        $sql = 'select * from ' . $this->app->getShortName() .
                '.mdl_context where contextlevel = ? and instanceid = ?';
            $mdl_context = $this->app->gcQuery($sql, array(30, $this->obj->id), true);
            return $mdl_context;
    }
    public function getEschool()
    {
        return $this->app;
    }
    public function getFullNameString()
    {
        if (trim($this->obj->firstname) != '')
        {
            $full_name = $this->obj->firstname;
        }
        if (trim($this->obj->lastname) != '')
        {
            if ($full_name)
            {
                $full_name .= ' ';
            }
            $full_name .= $this->obj->lastname;
        }
        return $full_name;
    }
    public function getHyperlinkToProfile()
    {
        return $this->getUserOnInstitution()->getHyperlinkToProfile();
    }
    public function getProfileIcon($local = false)
    {
        if (!$local)
        {
            $mhr_user = $this->getUserOnInstitution();
            if ($mhr_user)
            {
                return $mhr_user->getProfileIcon();
            }
        }
        $context = $this->getContext();
        return $this->app->getAppUrl() . '/pluginfile.php/' . $context->id . 
                '/user/icon/globalclassroom/f1?rev=1';
    }
    public function getUnreadMessages()
    {
        return $this->getUserOnInstitution()->getUnreadMessages();
    }
    public function hasAccess(GcrEschool $eschool)
    {
        $mhr_user = $this->getUserOnInstitution();
        return $mhr_user->hasAccess($eschool);
    }
    public function isAllowed()
    {
        $sql = 'select * from ' . $this->app->getShortName() . '.mdl_mnet_sso_access_control where ' .
                'username = ? and mnet_host_id = ?';
        $record = $this->app->gcQuery($sql, array($this->obj->username, $this->obj->mnethostid), true);
        if (!$record)
        {
            $record = $this->app->insertIntoMdlTable('mnet_sso_access_control',
                array('username' => $this->obj->username, 
                      'mnet_host_id' => $this->obj->mnethostid,
                      'accessctrl' => 'allow'));
        }
        if ($record->accessctrl === 'allow' || $record->accessctrl === null)
        {
            return true;
        }
        return false;
    }
    public function isMember()
    {
        if (!$this->isRemoteUser())
        {
            $mhr_user = $this->getUserOnInstitution();
            return $mhr_user->isMember();
        }
        return false;
    }
    public function isRemoteUser()
    {
        $mhr_user = $this->getUserOnInstitution();
        $institution = $this->getApp()->getInstitution();
        $user_institution = $mhr_user->getApp();
        return ($institution->getShortName() != $user_institution->getShortName());
    }
    public function isSameUser($user)
    {
        if ($this->getInstitution()->getShortName() == $user->getInstitution()->getShortName())
        {
            return ($this->username == $user->getObject()->username);
        }
        return false;
    }
    public function enrolUserinCourse($mdl_course, $eschool, $roleid = '5')
    {
        $mdl_context = $mdl_course->getContext();
        try
        {
            $sql = 'select * from ' . $eschool->getShortName() . '.mdl_enrol WHERE courseid = ? AND enrol = ?';
            $enrollment = $eschool->gcQuery($sql, array($mdl_course->getObject()->id, 'globalclassroom'), true);
            if(!isset($enrollment))
            {
                $eschool->addGcrEnrollment($mdl_course);
                $enrollment = $eschool->gcQuery($sql, array($mdl_course->getObject()->id, 'globalclassroom'), true);
            }
            $sql = 'select * from ' . $eschool->getShortName() . '.mdl_user_enrolments
                    WHERE userid = ? AND enrolid = ?';
            $isenrolled = $eschool->gcQuery($sql, array($this->obj->id, $enrollment->id), true);
            if(!isset($isenrolled))
            {
                $params = array(
                    'status' => 0,
                    'enrolid' => $enrollment->id,
                    'userid' => $this->obj->id,
                    'timecreated' => time(),
                    'timestart' => time(),
                    'timeend' => 0,
                );
                $eschool->insertIntoMdlTable('user_enrolments', $params);
            }
            $params = array(
                'roleid' => $roleid,
                'contextid' => $mdl_context->id,
                'userid' => $this->obj->id,
                'timemodified' => time(),
            );
            $eschool->insertIntoMdlTable('role_assignments', $params);
            return true;
        }
        catch (Exception $e)
        {
            global $CFG;
            $CFG->current_app->gcError('something went wrong with the enrollment process. ' . $e);
            return false;
        }
    }
    public function removeRolefromCourse($mdl_course, $eschool, $roleid = '5')
    {
        $mdl_context = $mdl_course->getContext();
        $sql = 'delete from ' . $eschool->getShortName() . '.mdl_role_assignments
                WHERE roleid = ? AND contextid = ? and userid = ?';
        return $eschool->gcQuery($sql, array($roleid, $mdl_context->id, $this->obj->id));
    }
    public function removeAccess()
    {
        $this->app->updateMdlTable('mnet_sso_access_control', 
                array('accessctrl' => 'deny'),
                array('username' => $this->obj->username, 'mnet_host_id' => $this->obj->mnethostid));
    }
    public function getRoles($mdl_context, $order = 'c.contextlevel DESC, r.sortorder ASC')
    {
        $userid = $this->obj->id;

        $sql = "SELECT ra.*, r.name, r.shortname
                FROM " . $this->app->getShortName() .".mdl_role_assignments ra,
                     " . $this->app->getShortName() .".mdl_role r,
                     " . $this->app->getShortName() .".mdl_context c
                WHERE ra.userid = ?
                    AND ra.roleid = r.id
                    AND ra.contextid = c.id
                    AND ra.contextid = ?
                ORDER BY $order";
        $params = array($userid, $mdl_context->id);
        return $this->app->gcQuery($sql, $params);
    }
}
		