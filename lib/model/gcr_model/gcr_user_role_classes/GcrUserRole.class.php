<?php

/**
 * Description of GcrUserRole:
 * 
 * This class is the base class of a strategy pattern to offload permissions
 * handling from the GcrUser classes.
 *
 * @author Ron Stewart
 * @abstract
 * 11/11/11 cool date :)
 */
class GcrUserRole 
{
    protected $name;
    protected $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }
    public static function getName()
    {
        return '';
    }
    public static function userHasRole($user)
    {
        return false;
    }
    public static function getRolePrivileges()
    {
        return array();
    }
    
    public function hasCourseAccess($course)
    {
        if ($course->getObject()->visible == 1)
        {
            foreach ($course->getRoleAssignments($this->user) as $role)
            {
                return ($role->roleid > 0 && $role->roleid <= 5); 
            }
        }
        return false;
    }
    public function setPermissions()
    {
        return false;
    }
}

?>
