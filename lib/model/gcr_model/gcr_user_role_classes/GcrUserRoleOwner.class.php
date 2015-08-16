<?php

/**
 * Description of GcrUserRoleGCUser:
 * Note: This class does not add roles on roaming, as that is built in to
 * the EschoolAdmin Role which all owners have.
 *
 * @author Ron Stewart
 */
class GcrUserRoleOwner extends GcrUserRole
{
    public static function getName()
    {
        return 'Owner';
    }
    public static function userHasRole($user)
    {
        $user_obj = $user->getObject();
        $app = $user->getApp();
        $institution = $app->getInstitution();
        $mhr_user = $user->getUserOnInstitution();
        $owner = $institution->getOwnerUser();
        return ($mhr_user && $mhr_user->isSameUser($owner));
    }
    public static function getRolePrivileges()
    {
        return array (  'Owner',
                        'EschoolAdmin',
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