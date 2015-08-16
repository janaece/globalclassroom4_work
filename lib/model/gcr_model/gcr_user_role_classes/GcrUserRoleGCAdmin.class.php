<?php

/**
 * Description of GcrUserRoleGCAdmin:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleGCAdmin extends GcrUserRole
{
    public static function getName()
    {
        return 'GCAdmin';
    }
    public static function userHasRole($user)
    {
        $user_obj = $user->getObject();
        $app = $user->getApp();
        $flag = false;
        if ($user_obj)
        {
            if (isset($user_obj->username))
            {
                if ($user_obj->username == 'gc4' . $app->getShortName() . 'admin')
                {
                    if ($app->isMoodle())
                    {
                        $flag = ($user_obj->mnethostid == $app->getSelfMdlMnetHostRecord()->id);
                    }
                    else
                    {
                        $flag = ($user_obj->admin == 1);
                    }
                }
            }
        }
        
        return $flag;
    }
    
    public static function getRolePrivileges()
    {
        return GcrUserRoleFactory::getUserRoleClassnames();
    }
    public function hasCourseAccess($course)
    {
        return true;
    }
}

?>
