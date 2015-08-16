<?php

/**
 * Description of GcrUserRoleGCUser:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleEclassroomUser extends GcrUserRole
{
    public static function getName()
    {
        return 'EclassroomUser';
    }
    public static function userHasRole($user)
    {
        $flag = false;
        $app = $user->getApp();
        $mhr_user = $user->getUserOnInstitution();
        if ($mhr_user && !$user->isRemoteUser())
        {
            $eclassrooms = $mhr_user->getEclassrooms(true);
            
            if ($app->isMahara())
            {
                $flag = (count($eclassrooms) > 0) ? true : false;
            }
            else
            {
                foreach ($eclassrooms as $eclassroom)
                {
                    if ($eclassroom->getEschoolId() == $app->getShortName())
                    {
                        $flag = true;
                    }
                }
            }
        }
        return $flag;
    }
    public static function getRolePrivileges()
    {
        return array (  'EclassroomUser',
                        'Student',
                        'Guest');
    }
}

?>