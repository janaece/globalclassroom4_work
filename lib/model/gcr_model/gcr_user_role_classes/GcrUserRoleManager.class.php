<?php

/**
 * Description of GcrUserRoleManager:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleManager 
{
    protected $roles;
    protected $user;
    protected $roles_loaded;
    
    public function __construct($user)
    {
        $this->user = $user;
        $this->roles = array();
        $this->roles_loaded = false;
    }
    
    public function getRoles($reload = false)
    {
        if (!$this->roles_loaded || $reload)
        {
            $this->setRoles();
        }
        return $this->roles;
    }
    public function setRoles()
    {
        $this->roles = GcrUserRoleFactory::getUserRoles($this->user);
        $this->roles_loaded = true;
    }
    public function hasPrivilege($role_name, $reload = false)
    {
        if ($this->validateRole($role_name))
        {
            foreach ($this->getRoles($reload) as $role)
            {
                if (in_array($role_name, $role::getRolePrivileges()))
                {
                    return true;
                }
            }
        }
        return false;
    }
    public function hasRole($role_name, $reload = false)
    {
        if ($this->validateRole($role_name))
        {
            foreach ($this->getRoles($reload) as $role)
            {
                if ($role::getName() == $role_name)
                {
                    return true;
                }
            }
        }
        return false;
    }
    public function hasCourseAccess($course, $reload = false)
    {
        foreach ($this->getRoles($reload) as $role)
        {
            if ($role->hasCourseAccess($course))
            {
                return true;
            }
        }
        return false;
    }
    public function setPermissionsOnRoles($reload = false)
    {
        foreach ($this->getRoles($reload) as $role)
        {
            $role->setPermissions();
        }
    }
    public function setAsOwner()
    {
        if ($this->hasRole('GCAdmin'))
        {
            global $CFG;
            $CFG->current_app->gcError('Cannot set GCAdmin as owner', 'gcdatabaseerror');
        }
        $app = $this->user->getApp();
        $app->setCreatorId($this->user->getObject()->id);
        $app->save();
        if (!$this->hasPrivilege('EschoolAdmin'))
        {
            $this->setAsEschoolAdmin();
        }
    }
    public function setAsEschoolAdmin()
    {
        $app = $this->user->getApp();
        $mhr_usr_institution = $this->user->getMhrUsrInstitutionRecords($app->getMhrInstitution());
        if (!$mhr_usr_institution)
        {
            $this->user->addMhrInstitutionMembership();
            $mhr_usr_institution = $this->user->getMhrUsrInstitutionRecords(gcr::maharaInstitutionName);
        }    
        $app->updateMhrTable('usr_institution', array('admin' => 1), 
                array('usr' => $this->user->getObject()->id, 'institution' => gcr::maharaInstitutionName));
    }
    public function validateRole($role)
    {
        if (!in_array($role, GcrUserRoleFactory::getUserRoleClassnames()))
        {
            global $CFG;
            $CFG->current_app->gcError('Warning: user role type does not exist: ' . $role);
            return false;
        }
        return true;
    }
}

?>
