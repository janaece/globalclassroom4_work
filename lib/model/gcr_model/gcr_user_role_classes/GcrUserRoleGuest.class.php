<?php

/**
 * Description of GcrUserRoleGuest:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleGuest extends GcrUserRole
{
    public static function getName()
    {
        return 'Guest';
    }
    public static function userHasRole($user)
    {
        $app = $user->getApp();
        if ($app->isMoodle())
        {
            $mdl_user_obj = $user->getObject();
            if ($mdl_user_obj->id == 1 && $mdl_user_obj->mnethostid == 1 && 
                    $mdl_user_obj->username == 'guest')
            {
                return true;
            }
        }
        return false;
    }
    public static function getRolePrivileges()
    {
        return array ('Guest');
    }
    public function hasCourseAccess($course)
    {
        return false;
    }
}

?>