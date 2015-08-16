<?php


class GcrInstitutionTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GcrInstitution');
    }
    public static function constructCurrentInstitution($institution)
    {
        global $CFG;
        // get the eschool record
        $CFG->current_app = new GcrCurrentInstitution();
        $CFG->current_app->loadFromDoctrineModelObject($institution);
    }

    public static function executeAccountingCron()
    {
        $institutions = Doctrine_Core::getTable('GcrInstitution')->findAll();
        foreach($institutions as $institution)
        {
            // test code
            //if ($institution->getShortName() != 'globalsandbox') continue;
            GcrPurchaseTable::clearPendingTransactions(60*60*24);
            $institution->getAccountManager()->updateAccounting(array('update_eclassrooms' => true, 'verbose' => true));
        }
    }
    public static function getSideblockProfileHeaderHeight($fullname, $wrap_cut, $line_height, $base_height)
    {
        $name_line_count = ceil(strlen($fullname) / $wrap_cut);
        return $line_height * ($name_line_count - 1) + $base_height;
    }
    // Utility function to control the length of a string so it displays within its 
    // parent container properly. The optional $max_line_length param prevents
    // a very long word from breaking out of its container.
    public static function formatStringSize($string, $max_length, $max_line_length = false, $ellipsis = '...')
    {
        if (strlen($string) > $max_length)
        {
            $string = substr($string, 0, $max_length - 3) . $ellipsis;
        }
        if ($max_line_length)
        {
            $string = preg_replace('#(\S{' . $max_line_length . ',})#e', 
                    "chunk_split('$1', " . $max_line_length. ", '<br />\n')", $string);
        }
        return $string;
    }
    public static function getInstitution($short_name, $fail_silently = false)
    {
        global $CFG;
        $institution = Doctrine::getTable('GcrInstitution')->findOneByShortName($short_name);
        if (!$institution && !$fail_silently)
        {
            $CFG->current_app->gcError('Institution ' . $short_name .
                    ' does not exist.', 'gcdatabaseerror');
        }
        return $institution;
    }

    // This function checks whether an email address is already being used by a user account
    // on a given institution. GcrCurrentInstitution is assumed if no $institution param.
    public static function isEmailAddressUsed ($email, $institution = false)
    {
        if (!$institution)
        {
            global $CFG;
            $institution = $CFG->current_app->getInstitution();
        }
        $email = strtolower($email);
        $sql = 'SELECT * FROM ' . $institution->getShortName() . 
                '.mhr_usr WHERE lower(email) = ?';
        if (!$existing_email = $institution->gcQuery($sql, array($email), true))
        {
            $sql = 'SELECT * FROM ' . $institution->getShortName() . 
                '.mhr_artefact_internal_profile_email WHERE lower(email) = ?';
            if (!$existing_email = $institution->gcQuery($sql, array($email), true))
            {
                return false;
            }
        }
        return true;
    }
    // This function checks the database to make sure a short_name is not already in use
    public static function isShortNameUsed ($name)
    {
        $institution = Doctrine::getTable('GcrInstitution')->findOneByShortName($name);

        if ($institution)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public static function generateAutoLoginRecord ($schema, $username, $password, $expire = 30)
    {
        $expire += time();
        $token = GcrAutoLoginTable::generateToken();

        $auto_login = new GcrAutoLogin();
        $auto_login->setAppId($schema);
        $auto_login->setUsername($username);
        $auto_login->setUserPassword($password);
        $auto_login->setUserToken($token);
        $auto_login->setExpire($expire);
        $auto_login->save();

        return $token;
    }
    public static function getApp($schema)
    {
        if (!$app = Doctrine::getTable('GcrEschool')->findOneByShortName($schema))
        {
            if (!$app = Doctrine::getTable('GcrInstitution')->findOneByShortName($schema))
            {
                return false;
            }
        }
        return $app;
    }
    public static function getDbFormatTimestamp($ts)
    {
        return date('Y-m-d H:i:s', $ts);
    }
    public static function getHome()
    {
        return Doctrine::getTable('GcrInstitution')->findOneByShortName(gcr::gchomeSchemaMahara);
    }
    public static function getPrimaryTemplate()
    {
        return Doctrine::getTable('GcrInstitution')->findOneByShortName(gcr::gcPrimaryMaharaTemplate);
    }
    public static function parseShortNameFromUrl($url)
    {
        $urlWithoutProtocol = substr($url, strpos($url, '://') + 3);
        $explodedUrl = explode('.', $urlWithoutProtocol);
        return $explodedUrl[0];
    }
    public static function getInstitutions()
    {
        return Doctrine::getTable('GcrInstitution')->createQuery('e')->orderBy('e.full_name')->execute();
    }
    public static function verifyUsername($username)
    {
        // This regex was taken from portal/auth/internal/lib.php, function is_username_valid($username)
        // and it needs to stay in snyc with this.
        if (!preg_match('/^[a-zA-Z0-9!@#$%^&*()\-_=+\[{\]}\\|;:\'",<\.>\/?`]{3,30}$/', $username))
        {
            return 'Invalid username (must be 3-30 characters)';
        }
        return null;
    }
    // This code was taken from portal/auth/internal/lib.php, function is_password_valid($password)
    // and it needs to stay in snyc with this.
    public static function verifyPassword($password)
    {
        if (!preg_match('/^[a-zA-Z0-9 ~!@#\$%\^&\*\(\)_\-=\+\,\.<>\/\?;:"\[\]\{\}\\\|`\']{6,}$/', $password))
        {
            return 'Invalid password (must be at least 6 characters)';
        }
        // The password must have at least one digit and two letters in it
        if (!preg_match('/[0-9]/', $password))
        {
            return 'Invalid password (must include at least one numerical digit)';
        }

        $password = preg_replace('/[a-zA-Z]/', "\0", $password);
        if (substr_count($password, "\0") < 2)
        {
            return 'Invalid password (must include at least one two alphabetic letters)';
        }
        return null;
    }
    /*
    public static function convertEschoolShortNameToMhrInstitutionName($short_name)
    {
            $keys = array ('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F',
                    '6' => 'G', '7' => 'H' , '8' => 'I', '9' => 'J');
            $name = '';
            for ($i = 0; $i < strlen($short_name); $i++)
            {
                    $char = substr($short_name, $i, 1);
                    if (array_key_exists($char, $keys))
                    {
                            $name .= $keys[$char];
                    }
                    else
                    {
                            $name .= $char;
                    }
            }
            return $name;
    }
    public static function convertMhrInstitutionNameToEschoolShortName($name)
    {
            $keys = array ('A' => '0', 'B' => '1', 'C' => '2', 'D' => '3', 'E' => '4', 'F' => '5',
                    'G' => '6', 'H' => '7' , 'I' => '8', 'J' => '9');
            $short_name = '';
            for ($i = 0; $i < strlen($name); $i++)
            {
                    $char = substr($name, $i, 1);
                    if (array_key_exists($char, $keys))
                    {
                            $short_name .= $keys[$char];
                    }
                    else
                    {
                            $short_name .= $char;
                    }
            }
            return $short_name;
    }
    */
}