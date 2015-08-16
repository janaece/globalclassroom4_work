<?php

/**
 * Description of GcrUserRoleGCUser:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleGCStaff extends GcrUserRole
{
    public static function getName()
    {
        return 'GCStaff';
    }
    public static function userHasRole($user)
    {
        $user_obj = $user->getObject();
        $app = $user->getApp();
        $flag = false;
        $mhr_user = $user->getUserOnInstitution();
        if ($mhr_user && $user_obj)
        {
            if ($app->isHome() || $mhr_user->getObject()->staff == 1)
            {
                $home = GcrInstitutionTable::getHome();
                $home_user_obj = $home->selectFromMhrTable('usr', 'username', $user_obj->username, true);
                if ($home_user_obj)
                {
                    $home_user = new GcrMhrUser($home_user_obj, $home);
                    $mhr_usr_institution = $home_user->getMhrUsrInstitutionRecords($home->getMhrInstitution());
                    if ($mhr_usr_institution)
                    {
                        $flag = ($mhr_usr_institution->staff == 1);
                    }
                }
            }
        }
        return $flag;
    }
    public function setPermissions()
    {
        $app = $this->user->getApp();
        if ($app->isMoodle())
        {
            $app->setMdlRoleAssignment('gcstaff', 1, $this->user->getObject()->id);
        }
    }
    public static function getRolePrivileges()
    {
        return array (  'GCStaff',
                        'Owner',
                        'EschoolAdmin',
                        'EschoolStaff',
                        'EclassroomUser',
                        'Student',
                        'Guest');
    }
    public function hasCourseAccess($course)
    {
        return true;
    }
}

?>
