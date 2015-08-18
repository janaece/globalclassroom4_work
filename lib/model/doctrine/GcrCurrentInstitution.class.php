<?php
// CurentInstitution class.
// Ron Stewart
// Feb 9, 2010
//
// This class represents the institution which owns the Moodle or Mahara that the user is currently accessing.

class GcrCurrentInstitution extends GcrInstitution
{
    public $eschools;
    protected $current_user;

    // A de facto copy constructor, as Doctrine disallows parameters in its version of a constructor.
    // First, we get an Eschool object using Doctrine. Then, we copy it here, and add Current Eschool
    // initialization settings.
    function loadFromDoctrineModelObject($institution)
    {
        foreach($institution->toArray() as $column => $value)
        {
            $this->$column = $value;
        }
        $eschools = $this->getEschools();
        if ($eschools)
        {
            foreach ($eschools as $eschool)
            {
                $this->eschools[] = $eschool;
            }
        }
    }
    function loadPasswordSalts()
    {
        global $CFG;
        $salts = Doctrine::getTable('GcrInstitutionSaltHistory')->findByInstitutionid($this->id);
        $saltcount = 0;

        foreach($salts as $salt)
        {
            $salt_variable_name = 'passwordsaltalt' . ++$saltcount;
            $CFG->$salt_variable_name = $salt->getSalt();
        }
    }
    function includeGCJQueryLib()
    {
        require_once(gcr::webDir . 'lib/gc_jquery.php');
        if ($this->current_user && $this->current_user->isLoggedIn())
        {
            require_once(gcr::webDir . 'lib/gc_jquery_updates.php');
        }
    }
    function checkForExclusiveSpecialConditions()
    {
        // If this is the cron script is running, don't do anything else
        if (defined('GC_CRON_RUNNING'))
        {
            return true;
        }
        // If this is the error page, ignore other checks
        if ($this->checkForErrorPage())
        {
            return true;
        }
        // is mahara closed for an upgrade?
        if ($this->checkForUpgrade())
        {
            return true;
        }
        return false;
    }
    function checkForSpecialConditions()
    {
        if (!$this->checkForExclusiveSpecialConditions())
        {
            // create the current user object
            $this->current_user = new GcrCurrentMhrUser();
            // reset the 2 hour idle timeout for current_user
            $this->refreshSessionTimeout();
            // is this a template? if so dont allow non-admins in.
            $this->checkForTemplate();
            // is this is brand new institution?
            $this->checkForIsNew();
            // is key replacement parameter set?
            $this->checkForReplaceMnet();
            // does this institution require membership?
            $this->checkForForceMembership();
            // is there a wantsurl in the $_GET?
            $this->checkForWantsUrl();
            // is this a call to the jump.php script (keep track of where people go)?
            $this->checkForJumpRequest();
            // is the user new, with the mahara default dashboard set? If so, overwrite it.
            $this->checkForDefaultDashboardTemplate();
            // Check for referrals from outside platforms to where redirects may be needed
            $this->checkExternalPlatformCookie();
            // Include library of event listening functions for mahara event subscriptions
            include_once(gcr::rootDir . 'function_lib/MaharaEventListenersLib.php');
        }
    }
    public function checkForErrorPage()
    {
        return strpos($_SERVER['REQUEST_URI'], '/eschool/error') !== false;
    }
    public function getTemporarySchemaName()
    {
        global $db;
        $sql = 'SELECT nspname FROM pg_namespace WHERE oid = pg_my_temp_schema()';
        $temp_schema = $db->Execute($sql);
	$key = $temp_schema->fields['nspname'];
        if (isset($key))
        {
            return $key;
        }
        return false;
    }
    public function checkExternalPlatformCookie()
    {
        if (!$this->isLoggedIn())
        {
            if (isset($_GET['gc_referral']))
            {
                setcookie('gc_platform', $_GET['gc_referral'], time() + 24*60*60*365, '/');
            }
        }
    }
    public function checkForIsNew()
    {
        if ($this->is_new == 't')
        {
            $this->replaceMnetKeys();
            $this->setConfigVar('gc_new_mnet_key', 1);
            $institution = Doctrine::getTable('GcrInstitution')->find($this->id);
            $institution->setIsNew('f');
            $institution->save();
            redirect($this->getUrl() . '/institution/updateMnetConnections');
        }
    }
    public function checkForTemplate()
    {
        $siteclosedforupgrade = get_config('siteclosed');
        if ($siteclosedforupgrade != 1)
        {
            if ($this->isPrimaryTemplate() && (!$this->hasPrivilege('GCAdmin')))
            {
                redirect('https://' . gcr::frontPageDomain . '/notfound');
            }
        }
    }
    public function checkForJumpRequest()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/auth/xmlrpc/jump.php'))
        {
            if (!isset($_GET['wr']) && isset($_GET['hostwwwroot']))
            {
                // Ron Stewart: 06/14/2012
                // This is a bug fix for moodle generated urls which only provide
                // a hostwwwroot parameter to feed to the remote mnet host's jump
                // page. IMO, this should be corrected on the mahara side, since
                // connected network nodes cannot be expected to provide an 
                // instance id (ins param) for their host record on the mahara side. 
                // For the time being, correcting the params here is the most lightweight
                // way of dealing with the issue (no overwrites required).
                
                $remotewwwroot = $_GET['hostwwwroot'];
                $short_name = GcrEschoolTable::parseShortNameFromUrl($remotewwwroot);
                $eschool = GcrEschoolTable::getEschool($short_name);
                $mhr_auth_instance = $this->getAuthInstance($eschool);
                if ($mhr_auth_instance)
                {
                    $_GET['wr'] = $remotewwwroot;
                    $_GET['ins'] = $mhr_auth_instance->id;
                }
            }
            else
            {
                $remotewwwroot = param_variable('wr');
                $short_name = GcrEschoolTable::parseShortNameFromUrl($remotewwwroot);
            }
            if (isset($_COOKIE['gc_eschools']))
            {
                if (strpos($_COOKIE['gc_eschools'], ';' . $short_name . ';') === false)
                {
                    setcookie('gc_eschools', $_COOKIE['gc_eschools'] . $short_name . ';', false, '/');
                }
            }
            else
            {
                setcookie('gc_eschools', ';' . $short_name . ';', false, '/');
            }
        }
    }

    // This function will check to see if a token has been stored in the autologin table
    // for record gchome in field token. If it has and it matches the $_POST['token'] value,
    // this user will be logged in as gc4<schemaname>admin
    public function checkForAutoLogin()
    {
        if (isset($_GET['token']))
        {
            $deleted = Doctrine_Query::create()
                    ->delete()
                    ->from('GcrAutoLogin a')
                    ->where('a.expire < ?', time())
                    ->execute();
            $auto_login_records = Doctrine::getTable('GcrAutoLogin')->findByAppId($this->short_name);
            foreach ($auto_login_records as $record)
            {
                // just in case of a race condition make sure, once again, that the token isn't expired
                if ($record->getExpire() > time() && $_GET['token'] == $record->getUserToken())
                {
                    $_POST['login_username'] = $record->getUsername();
                    $_POST['login_password'] = $record->getUserPassword();
                    $_POST['submit'] = 'Login';
                    $_POST['sesskey'] = '';
                    $_POST['pieform_login'] = '';
                    $record->delete();
                    return true;
                }
            }
        }
        return false;
    }
    
    // If the config var gcralternateloginurl is set, we redirect to it when
    // this function is called. 
    public function checkForAlternateLogin()
    {
        $alt_url = $this->getConfigVar('gcralternateloginurl');
        if (!empty($alt_url))
        {
            redirect($alt_url);
        }
    }
    public function performPreloginChecks()
    {
        $this->checkForAutoLogin();
        $this->checkForLogout();
    }
    public function checkForLogout()
    {
        if (isset($_GET['logout']))
        {
            header('Location: ' . $this->getUrl() . '/eschool/logout');
            die();
        }
    }
    public function checkForForceMembership()
    {
        if ($this->hasPrivilege('Student'))
        {
            if ($this->current_user->requiresMembership())
            {
                $url = explode('/', $_SERVER['REQUEST_URI']);
                if ($url[1] != 'purchase' && !($url[1] == 'eschool' && $url[2] == 'logout'))
                {
                    redirect($this->getUrl() . '/purchase/membership');
                }
            }
        }
    }
    public function checkForReplaceMnet()
    {
        if (isset($_GET['replace_mnet']))
        {
            $home = GcrEschoolTable::getHome();
            if (!$var = $home->getConfigVar('gc_replace_mnet_token' . $_GET['replace_mnet']))
            {
                global $CFG;
                $CFG->current_app->gcError('No Token Stored in gchome.config table for mnet replacement', 'gcpageaccessdenied');
            }
            else if ($var != $_GET['replace_mnet'])
            {
                global $CFG;
                GcrEschoolTable::getHome()->deleteFromMdlTable('config', 'name', 'gc_replace_mnet_token' . $_GET['replace_mnet']);
                $CFG->current_app->gcError('Mnet replacement token ' . $_GET['replace_mnet'] .
                        ' does not match record ' . $var, 'gcpageaccessdenied');
            }
            $this->replaceMnetKeys();
            print '<div id="mnet_replacement"></div>';
            die();
        }
    }

    public function checkForWantsUrl()
    {
        GcrWantsUrlTable::handleRequest();
    }
    public function checkForUpgrade()
    {
        $siteclosedforupgrade = get_config('siteclosed');
        if ($siteclosedforupgrade == 1)
        {
            if (strpos($_SERVER['REQUEST_URI'], '/admin/upgrade') === false)
            {
                redirect($this->getAppUrl() . 'admin/upgrade.php');
            }
            return true;
        }
        return false;
    }
    // Any kind of errors should be sent here in most cases, with a 2nd parameter if it is fatal.
    public function gcError ($log_message, $error_string = null)
    {
        $backtrace = debug_backtrace();
        // prints to log file, "dd/mm/yyyy hh:mm:ss: eschool_short_name: user_id: error_message: calling_function_name"
        error_log("\n" . date('d/m/Y H:i:s', time()) . ": " . $this->short_name . ": $log_message: Stack Trace:" .
                $backtrace[1]['function'] . "<-" . $backtrace[2]['function'] . "<-" . $backtrace[3]['function'] . "<-" .
                        $backtrace[4]['function'] . "<-" . $backtrace[5]['function'], 3, gcr::rootDir . 'debug/error.log');
        // send user to error page if this was a fatal error
        if ($error_string)
        {
            redirect($this->getUrl() . '/eschool/error?msg=' . $error_string);
        }
    }
    public function getCurrentUser()
    {
        return $this->current_user;
    }
    public function checkForDefaultDashboardTemplate()
    {
        if ($this->isLoggedIn() && $this->current_user->hasDefaultDashboardTemplate())
        {
            if ($this->getDefaultDashboardMhrView())
            {
                $this->current_user->setDashboardToGcrDashboardTemplate();
            }
        }
    }
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }
    public function isLoggedIn()
    {
        if (isset($this->current_user))
        {
            return $this->current_user->isLoggedIn();
        }
        return false;
    }
    public function isViewDefaultDashboardTemplate(View $view)
    {
        $gcr_dashboard_template = $this->getDefaultDashboardMhrView();
        if ($gcr_dashboard_template)
        {
           return ($view->get('title') == gcr::defaultDashboardTemplateTitle); 
        }
    }
    public function hasPrivilege($role, $reload = false)
    {
        if ($this->isLoggedIn())
        {
            return $this->current_user->getRoleManager()->hasPrivilege($role, $reload);
        }
        return false;
    }
    public function logout()
    {
        $this->redirect($this->getAppUrl() . '?logout');
    }
    public function printFooter()
    {
        $footer = new GcrSymfonyFooter($this);
        $footer->printFooter();
    }
    public function printHeader()
    {
        $user = false;
        if ($this->isLoggedIn())
        {
            $user = $this->getCurrentUser();
        }
        $header = GcrSymfonyHeaderType::getInstance($user);
        print $header->getContent();
    }
    public function executeRedirect($url)
    {
        redirect($url);
    }
    public function replaceMnetKeys()
    {
        require_once(get_config('docroot') . 'api/xmlrpc/lib.php');
        $openssl = OpenSslRepo::singleton();
        if (!$key_pair = $openssl->get_keypair(true))
        {
            global $CFG;
            $CFG->current_app->gcError('Failure to regenerate keypair', 'gcdatabaseerror');
        }
    }
    public function requireLogin()
    {
        if (!$this->isLoggedIn())
        {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            {
                $param_string = ''; 
            }
            else
            {
                $wants_url = GcrWantsUrlTable::createWantsUrl('maharalogin', $this);
                $param_string = '?' . $wants_url->toGetParam();
            }
            redirect($this->getAppUrl() . $param_string);
        }
    }
    public function requireMahara()
    {
        return true;
    }
    public function requireMoodle()
    {
        redirect($this->getDefaultEschool()->getUrl() . $_SERVER['REQUEST_URI']);
    }
    public function refreshSessionTimeout()
    {
        if ($this->current_user->isLoggedIn())
        {
            $this->current_user->refreshSessionTimeout();
        }
    }
    
    // This function was added as a caching based alternative function
    // for checking if a mahara table exists. This is so that we can overwrite poorly 
    // written Mahara code to point to this function for better performance.
    // Caveat: Do not use this function in situations where the table list
    // could become outdated during the same request being processed, i.e.
    // during the upgrade process, where tables may be created or dropped.
    // rstewart 12/22/11
    public function tableExists($table, $use_caching = true)
    {
        global $CFG, $db;

        $exists = true;

        /// Do this function silenty (to avoid output in install/upgrade process)
        $olddbdebug = $db->debug;
        $db->debug = false;

        /// Load the needed generator
        $classname = 'XMLDB' . $CFG->dbtype;
        $generator = new $classname();
        $generator->setPrefix($CFG->prefix);
        /// Calculate the name of the table
        $tablename = $generator->getTableName($table, false);

        /// Search such tablename in DB
        if (isset($CFG->gc_metatables_cache) && $use_caching)
        {
            $metatables = $CFG->gc_metatables_cache;
        }
        else
        {
            $metatables = $db->MetaTables();
            $CFG->gc_metatables_cache = $metatables;
        }
        $metatables = array_flip($metatables);
        $metatables = array_change_key_case($metatables, CASE_LOWER);
        if (!array_key_exists($tablename,  $metatables)) {
            $exists = false;
        }

        /// Re-set original debug 
        $db->debug = $olddbdebug;

        return $exists;
    }
}
