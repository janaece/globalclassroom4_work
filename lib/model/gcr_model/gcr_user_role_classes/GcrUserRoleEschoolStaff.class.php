<?php

/**
 * Description of GcrUserRoleGCUser:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleEschoolStaff extends GcrUserRole
{
    public static function getName()
    {
        return 'EschoolStaff';
    }
    public static function userHasRole($user)
    {
        $mhr_user = $user->getUserOnInstitution();
        if ($mhr_user && !$user->isRemoteUser())
        {
            $institution = $mhr_user->getApp();
            $mhr_usr_institution = $mhr_user->getMhrUsrInstitutionRecords($institution->getMhrInstitution());
            if ($mhr_usr_institution)
            {
                if ($mhr_usr_institution->staff == 1)
                {
                    return true;
                }
            }
        }
        return false;
    }
    public static function getRolePrivileges()
    {
        return array (  'EschoolStaff',
                        'EclassroomUser',
                        'Student',
                        'Guest');
    }
}

?>