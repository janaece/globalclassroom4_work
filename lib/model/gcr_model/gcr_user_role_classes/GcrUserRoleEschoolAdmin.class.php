<?php

/**
 * Description of GcrUserRoleGCUser:
 * 
 *
 * @author Ron Stewart
 */
class GcrUserRoleEschoolAdmin extends GcrUserRole
{
    public static function getName()
    {
        return 'EschoolAdmin';
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
                if ($mhr_usr_institution->admin == 1)
                {
                    return true;
                }
            }
            // Take away Site Staff if the user is not an Eschool Admin
            $mdl_user_obj = $mhr_user->getObject();
            if ($mdl_user_obj->staff == 1)
            {
                $institution->updateMhrTable('usr', array('staff' => 0), array('id' => $mdl_user_obj->id));
            }
        }
        return false;
    }
    public function setPermissions()
    {
        $app = $this->user->getApp();
        $user_obj = $this->user->getObject();
        if ($app->isMoodle())
        {
            $app->setMdlRoleAssignment('eschooladmin', 1, $this->user->getObject()->id);
        }
        else
        {
            if ($user_obj->staff != 1)
            {
                $app->updateMhrTable('usr', array('staff' => 1), array('id' => $user_obj->id));
                foreach ($app->getEschools() as $eschool)
                {
                    $mdl_user = $this->user->getUserOnEschool($eschool, true);
                    if ($mdl_user)
                    {
                        $eschool->setMdlRoleAssignment('eschooladmin', 1, $mdl_user->getObject()->id);
                    }
                }
            }
        }
    }
    public static function getRolePrivileges()
    {
        return array (  'EschoolAdmin',
                        'EschoolStaff',
                        'EclassroomUser',
                        'Student',
                        'Guest');
    }
    public function hasCourseAccess($course)
    {
        return ($this->user->hasSameInstitution($course));
    }
}

?>