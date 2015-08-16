<?php

/**
 * Description of GcrUserRoleFactory:
 * 
 * This class builds an array of GcrUserRoles for a given GcrUser object
 *
 * @author Ron Stewart
 * date: 11/11/11
 */
class GcrUserRoleFactory 
{
    public static function getUserRoles($user)
    {
        $roles = array();
        $user_obj = $user->getObject();
        if ($user_obj && $user_obj->id > 0)
        {
            foreach (self::getUserRoleClassnames() as $classname)
            {
                $classname = 'GcrUserRole' . $classname;
                if ($classname::userHasRole($user))
                {
                    $roles[] = new $classname($user);
                }
            }
        }
        return $roles;
    }
    public static function getUserRoleClassnames()
    {
        return array (  'GCAdmin',
                        'GCUser',
                        'GCStaff',
                        'Owner',
                        'EschoolAdmin',
                        'EschoolStaff',
                        'EclassroomUser',
                        'Student',
                        'Guest',
                        'GCHomeAdmin');
    }
    /*
    public static function isUserGCAdmin($user)
    {
        
    }
    public static function isUserGCStaff($user)
    {
        
    }
    public static function isUserOwner($user)
    {
        
    }
    public static function isUserEschoolAdmin($user)
    {
        
    }
    public static function isUserEschoolStaff($user)
    {
        
    }
    public static function isUserEclassroomUser($user)
    {
        
    }
    public static function isUserStudent($user)
    {
        
    }
    public static function isUserGuest($user)
    {
        
    }
    */
}

?>
