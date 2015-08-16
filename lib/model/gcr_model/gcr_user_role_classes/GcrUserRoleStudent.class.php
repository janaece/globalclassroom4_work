<?php

/**
 * Description of GcrUserRoleStudent:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleStudent extends GcrUserRole
{
    public static function getName()
    {
        return 'Student';
    }
    public static function userHasRole($user)
    {
        return $user->getUserOnInstitution();
    }
    public static function getRolePrivileges()
    {
        return array (  'Student',
                        'Guest');
    }
    
}

?>