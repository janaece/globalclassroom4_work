<?php

/**
 * Description of GcrUserRoleGCUser:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleGCUser extends GcrUserRole
{
    public static function getName()
    {
        return 'GCUser';
    }
    public static function userHasRole($user)
    {
        $user_obj = $user->getObject();
        $mhr_user = $user->getUserOnInstitution();
        if ($mhr_user && $user_obj)
        {
            if ($mhr_user->getApp()->isHome() || $mhr_user->getObject()->admin == 1)
            {
                $home_user = GcrInstitutionTable::getHome()->selectFromMhrTable('usr', 
                        'username', $mhr_user->getObject()->username, true);
                if ($home_user)
                {
                    return ($home_user->staff == 1 || $home_user->admin == 1);
                }
            }
        }
        return false;
    }
    public function setPermissions($eschool = false)
    {
        $app = $this->user->getApp();
        if ($app->isMoodle())
        {
            $app->setMdlRoleAssignment('gcstaff', 1, $this->user->getObject()->id);
        }
    }
    public static function getRolePrivileges()
    {
        return array (  'GCUser',
                        'GCStaff',
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
