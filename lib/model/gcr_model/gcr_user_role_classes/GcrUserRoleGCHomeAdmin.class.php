<?php

/**
 * Description of GcrUserRoleGCAdmin:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleGCHomeAdmin extends GcrUserRole
{
    public static function getName()
    {
        return 'GCHomeAdmin';
    }
    public static function userHasRole($user)
    {   
        $app = $user->getApp();
        if ($app->isHome() && $app->isMoodle())
        {
            $user_obj = $user->getObject();
            if ($user_obj)
            {
                $admin = Doctrine::getTable('GcrAdminAccess')->findOneByUserid($user_obj->id);
                if ($admin)
                {
                    if ($admin->username == $user_obj->username)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    public static function getRolePrivileges()
    {
        return array('GCHomeAdmin');
    }
}

?>
