<?php

class GcrEschoolTable extends Doctrine_Table
{
    public static function authorizeCourseAccess(GcrMdlCourse $course)
    {
        global $CFG;
        $eschool = $course->getApp();
        $open_access = ($eschool->getConfigVar('forcelogin') == 0);
        $visible = $course->isVisible();
        $authorized = false;
        if ($CFG->current_app->hasPrivilege('Student'))
        {
            $current_user = $CFG->current_app->getCurrentUser();
            if ($current_user->hasAccess($eschool))
            {
                $authorized = $CFG->current_app->hasPrivilege('GCStaff');
                if (!$authorized && $CFG->current_app->hasPrivilege('EschoolStaff'))
                {
                    $authorized = ($eschool->getInstitution()->getId() == 
                            $CFG->current_app->getInstitution()->getId());
                }
                if (!$authorized)
                {
                    $authorized = $visible;
                    if (!$authorized)
                    {
                        $mdl_user = $current_user->getUserOnEschool($eschool);
                        $authorized = ($mdl_user && $course->isInstructor($mdl_user));
                    }
                }
            }
        }
        else
        {
            $authorized = ($visible && $open_access);
        }
        
        return $authorized;
    }
    public static function authorizeEschoolAccess($eschool, $restrict_user_access = false) 
    {
        global $CFG;
        $authorized = ($eschool->getConfigVar('forcelogin') == 0);
        if ($CFG->current_app->hasPrivilege('Student')) 
        {
            $authorized = ($authorized && (!$restrict_user_access));
            if (!$authorized) 
            {
                $user = $CFG->current_app->getCurrentUser();
                $authorized = $user->hasAccess($eschool);
            }
        }
        return $authorized;
    }
    public static function authorizeHiddenCategoryAccess($eschool)
    {
        global $CFG;
        $authorized = false;
        if ($CFG->current_app->hasPrivilege('GCStaff'))
        {
            $authorized = true;
        }
        else if ($CFG->current_app->hasPrivilege('EschoolStaff'))
        {
            $authorized = ($eschool->getInstitution()->getId() == 
                    $CFG->current_app->getInstitution()->getId());
        }
        return $authorized;
    }
    public static function constructCurrentEschool(GcrEschool $eschool)
    {
        global $CFG;
        // get the eschool record
        $CFG->current_app = new GcrCurrentEschool();
        $CFG->current_app->loadFromDoctrineModelObject($eschool);
    }
    // This function checks the database to make sure a short_name is not already in use
    public static function isShortNameUsed ($name)
    {
        $eschool = Doctrine::getTable('GcrEschool')->findOneByShortName($name);
        $institution = Doctrine::getTable('GcrInstitution')->findOneByShortName($name);

        if ($eschool || $institution)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public static function getEschool($short_name, $fail_silently = false)
    {
        global $CFG;
        $eschool = Doctrine::getTable('GcrEschool')->findOneByShortName($short_name);
        if (!$eschool && !$fail_silently)
        {
            $CFG->current_app->gcError('Eschool ' . $short_name . ' does not exist.', 'gcdatabaseerror');
        }
        return $eschool;
    }
    // This function returns a block of html which contains a user's profile
    // link and profile icon. If the user is not local, the link to profile
    // is excluded.
    public static function getInstructorProfileHtml(GcrMdlUser $mdl_user)
    {
        global $CFG;
        $html = '<span class="gc_user_profile">';
        $img = '<img class="gc_user_profile_img" src="' . $mdl_user->getProfileIcon() . '" />';
        
        $mhr_user = $mdl_user->getUserOnInstitution();
        if ($mhr_user && $mhr_user->getApp()->getShortName() == $CFG->current_app->getShortName())
        {
            $html .= '<a class="gc_user_profile_link" href="' . $mhr_user->getHyperlinkToProfile() . '">' .
                    $img . ' <span class="gc_user_profile_fullname">' . 
                    GcrInstitutionTable::formatStringSize($mhr_user->getFullnameString(), 22) . '</span></a>';  
        }
        else
        {
            $html .= $img . ' ' . $mdl_user->getFullnameString();
        }
        $html .= '</span>';
        return $html;
    }
    public static function getLetterGrade($grade, $grade_letters)
    {
        foreach ($grade_letters as $boundary => $letter)
        {
            if ($grade >= $boundary)
            {
                if (!isset($user_grade) || $boundary > $user_grade['lowerboundary'])
                {
                    $user_grade = array('lowerboundary' => $boundary, 'letter' => $letter);
                }
            }
        }
        return $user_grade['letter'];
    }
    public static function getHome()
    {
        return Doctrine::getTable('GcrEschool')->findOneByShortName(gcr::gchomeSchemaMoodle);
    }
    public static function getPrimaryTemplate()
    {
        return Doctrine::getTable('GcrEschool')->findOneByShortName(gcr::gcPrimaryMoodleTemplate);
    }
    // This function checks the database to make sure that a short_name
    // is not on the reserved words list
    public static function isShortNameReserved ($name)
    {
        $reservedName = Doctrine::getTable('GcrReservedNames')->findOneByName(strtolower($name));

        if ($reservedName)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public static function isShortNameValid($name)
    {
        if (preg_match('/^[a-zA-Z][a-zA-Z0-9]{1,31}$/', $name))
        {
            return true;
        }
        return false;
    }
    public static function getEschools($public_only = false)
    {
        if ($public_only)
        {
            return Doctrine::getTable('GcrEschool')->createQuery('e')
                    ->where('e.is_public = ?', true)
                    ->orderBy('e.full_name')->execute();
        }
        return Doctrine::getTable('GcrEschool')->createQuery('e')->orderBy('e.full_name')->execute();
    }
    // This function returns a list of eschools which were created by the given user
    public static function getEschoolsByCreator ($userid)
    {
        return  Doctrine::getTable('GcrEschool')->findByEschoolCreator($userid);
    }

    public static function parseShortNameFromUrl($url)
    {
        $urlWithoutProtocol = substr($url, strpos($url, '://') + 3);
        $explodedUrl = explode('.', $urlWithoutProtocol);
        return $explodedUrl[0];
    }
    // This function is used to generate strong passwords for our gc<schemaname>admin users in both
    // Moodle and in PostgreSQL. Moodle only allows passwords of 32 char max, so the function
    // defaults to 32 len.
    public static function generateAdminPassword ($length = 32, $validchars = false, $uniquechars = true)
    {
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));
        if (!$validchars)
        {
            $validchars = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";
        }
        $password  = "";
        $counter   = 0;

        while ($counter < $length)
        {
            $actChar = substr($validchars, rand(0, strlen($validchars)-1), 1);

            // All characters must be different
            if (!($uniquechars) || !(strstr($password, $actChar)))
            {
                $password .= $actChar;
                $counter++;
            }
        }
        return $password;
    }
    // This function returns a string of random alphanumeric chars
    public static function generateRandomString ($length = 64)
    {
        $randstr = "";

        for($i = 0; $i < $length; $i++)
        {
            $randnum = mt_rand(0,61);

            if ($randnum < 10)
            {
                $randstr .= chr($randnum+48);
            }
            else if ($randnum < 36)
            {
                $randstr .= chr($randnum+55);
            }
            else
            {
                $randstr .= chr($randnum+61);
            }
        }

        return $randstr;
    }
    // Copied from stratus/admin/report/unittest/ex_reporter.php
    public static function stripParameterFromUrl($url, $param)
    {
        $url = preg_replace('/(\?|&)' . $param . '=[^&]*&?/', '$1', $url);
        if (strpos($url, '?') == strlen($url) - 1)
        {
            $url = substr($url, 0 , -1);
        } 
        return $url;
    }
}