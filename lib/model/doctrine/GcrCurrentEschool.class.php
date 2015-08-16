<?php
// CurentEschool class.
// Ron Stewart
// Aug. 19, 2010
//
// This class represents the eSchool which Moodle sees (remember, to Moodle, there is only one local eschool). 
// This class should hold any methods for Eschools which are dependent on Moodle functions which operate only on 
// the current Eschool. For example, method enrolUserInCourse uses the Moodle function enrol_user_into_course(), 
// which enrols a user in to a course on the current eschool.

class GcrCurrentEschool extends GcrEschool
{
    public $institution;
    protected $current_user;
    protected $eschool_session;

    // A de facto copy constructor, as Doctrine disallows parameters in its version of a constructor.
    // First, we get an Eschool object using Doctrine. Then, we copy it here, and add Current Eschool
    // initialization settings.
    function loadFromDoctrineModelObject($eschool)
    {
        foreach($eschool->toArray() as $column => $value)
        {
            $this->$column = $value;
        }
        $this->institution = $this->getInstitution();
    }
    // This gets called after the Moodle library is loaded, but before we hand off
    // control to Moodle or Symfony.
    public function initializeCurrentApp($mdl_user_object, $mdl_session, $mdl_site)
    {
        global $USER, $SESSION, $FULLME, $ME, $SITE;

        $USER = $mdl_user_object;
        $SESSION = $mdl_session;
        $FULLME = $this->getUrl($_SERVER['REQUEST_URI']);
        $ME = $_SERVER['REQUEST_URI'];
        $SITE = $mdl_site;
        $this->eschool_session = $mdl_session;
        // If this is the error page, if so, ignore other checks
        if (!$this->checkForErrorPage())
        {
            $this->current_user = new GcrCurrentMdlUser();
            $this->refreshSessionTimeout();
            $this->checkForSpecialConditions();
        }
    }
    function includeGCJQueryLib()
    {
        require_once(gcr::webDir . 'lib/gc_jquery.php');
        if ($this->hasPrivilege('Student') && !$this->hasPrivilege('GCAdmin'))
        {
            require_once(gcr::webDir . 'lib/gc_jquery_updates.php');
        }
    }
    public function checkForSpecialConditions()
    {
        // check to see if this is brand new eschool
        if ($this->getResetKeys() == 't')
        {
            $this->replaceMnetKeys();
            $this->setConfigVar('gc_new_mnet_key', 1);
            $eschool = Doctrine::getTable('GcrEschool')->find($this->id);
            $eschool->setResetKeys('f');
            $eschool->save();
            redirect($this->getUrl() . '/eschool/updateMnetConnections');
        }
        // check for an automatic login databse record and corresponding token set
        $this->checkForAutoLogin();

        $this->checkForReplaceMnet();

        // check to see if a transfer GET variable was set and redirect as needed
        $this->checkForTransfer();

        // check cookie which says which mahara they are from for us to check when they aren't logged in
        $this->checkInstitutionCookie();

        //check for logout
        $this->checkForLogout();
    }
    public function checkForErrorPage()
    {
        return strpos($_SERVER['REQUEST_URI'], '/eschool/error') !== false;
    }
    // This function will check to see if a token has been stored in the global.eschool table
    // for record gchome in field token. If it has and it matches the $_POST['token'] value,
    // this user will be logged in as gc<schemaname>admin
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
                    $_POST['username'] = $record->getUsername();
                    $_POST['password'] = $record->getUserPassword();
                    if(isset($_GET['transfer']))
                    {
                        $_SESSION['transfer'] = $_GET['transfer'];
                    }
                    if(isset($_GET['wantsurl']))
                    {
                        $_SESSION['wantsurl'] = $_GET['wantsurl'];
                    }
                    $record->delete();
                    return true;
                }
            }
        }
        return false;
    }
    public function checkForLogout()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/login/logout.php'))
        {
            if ($current_user = $this->getCurrentUser())
            {
                $institution = $current_user->getInstitution();
                if ($institution)
                {
                    redirect($institution->getUrl() . '/eschool/logout');
                }
            }
        }
    }
    public function checkForTransfer()
    {
        if (isset($_GET['transfer']))
        {
            if ($institution = Doctrine::getTable('GcrInstitution')->findOneByShortName($_GET['transfer']))
            {
                if (!$this->isLoggedIn())
                {
                    $wantsurl = GcrEschoolTable::stripParameterFromUrl($this->getUrl($_SERVER['REQUEST_URI']), 'transfer');
                    redirect($this->getInstitutionJumpUrl($wantsurl, $institution));
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
                $CFG->current_app->gcError('No Token Stored in gchome.config table for mnet replacement',
                        'gcpageaccessdenied');
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
    public function checkInstitutionCookie()
    {
        if (!isset($_COOKIE['gc_eschool']))
        {
            if ($this->isLoggedIn())
            {
                if ($current_user = $this->getCurrentUser())
                {
                    if ($institution = $current_user->getInstitution())
                    {
                        setcookie('gc_eschool', $institution->getShortName(), time() + 24*60*60*365, '/');
                    }
                }
            }
            else if (isset($_GET['gc_referral']))
            {
                setcookie('gc_eschool', $_GET['gc_referral'], time() + 24*60*60*365, '/');
            }
        }
    }
    // WARNING: if this function is called, redirects on this request will 
    // result in a core dump, crashing php-fpm.
    public function clearCache()
    {
        purge_all_caches();
    }
    // Enrolls a user using a copy of Moodle 2.0 code from the Paypal Enrolment plugin.
    // $course is a mdl_course object, not a GcrMdlCourse object and $user is an $mdl_user
    // object not a GcrMdlUser object.
    public function enrolUserInCourse ($course, $user, $plugin = 'globalclassroom')
    {
        $plugin = enrol_get_plugin($plugin);
        $sql = 'select * from ' . $this->short_name . '.mdl_enrol where enrol = ? and courseid = ?';
        $plugin_instance = $this->gcQuery($sql, array('globalclassroom', $course->id), true);

        if ($plugin_instance->enrolperiod)
        {
            $timestart = time();
            $timeend   = $timestart + $plugin_instance->enrolperiod;
        }
        else
        {
            $timestart = 0;
            $timeend   = 0;
        }

        // Enrol user
        $plugin->enrol_user($plugin_instance, $user->id, $plugin_instance->roleid, $timestart, $timeend);
        $plugin->send_welcome_message($plugin_instance, $user);
        $this->sendEnrolmentEmails($plugin, $course, $user);   
    }
    public function executeRedirect($url)
    {
        redirect($url);
    }
    public function hasCapability($privilege, $mdl_context)
    {
        $context = context::instance_by_id($mdl_context->id);
        return has_capability($privilege, $context);
    }
    public function resetCourseBlocks()
    {
        global $CFG;
        require_once(gcr::moodleDir . 'lib/blocklib.php');
        $courses = get_courses();//can be feed categoryid to just effect one category
        foreach($courses as $course) 
        {
            $context = get_context_instance(CONTEXT_COURSE, $course->id);
            blocks_delete_all_for_context($context->id);
            blocks_add_default_course_blocks($course);
        } 
    }
    public function sendEnrolmentEmails($plugin, $mdl_course, $mdl_user_obj)
    {
        $mailstudents = $plugin->get_config('mailstudents');
        $mailteachers = $plugin->get_config('mailteachers');
        $mailadmins   = $plugin->get_config('mailadmins');

        $course = new GcrMdlCourse($mdl_course, $this);
        $mdl_user = new GcrMdlUser($mdl_user_obj, $this);   
        if (!$mhr_user = $mdl_user->getUserOnInstitution())
        {
            return false;
        }
        $mhr_teacher = $course->getInstructor();
        if ($mhr_teacher)
        {
            $mhr_teacher = $mhr_teacher->getUserOnInstitution();
        }
        $mhr_owner = $this->getOwnerUser();
        if ($mhr_owner)
        {
            $mhr_owner = $mhr_owner->getUserOnInstitution();
        }
        if (!empty($mailstudents))
        {
            $mhr_user_to = $mhr_user;
            if (!$mhr_user_from = $mhr_teacher)
            {
                $mhr_user_from = $mhr_owner;
            }
            if ($mhr_user_from)
            {
                $message_type = 'maharamessage';
                $a = new StdClass;
                $a->coursename = $course->getObject()->fullname;
                $subject = get_string("enrolmentnew", 'enrol_globalclassroom', $course->getObject()->shortname);
                $body = get_string('welcometocoursetext', 'enrol_globalclassroom', $a);
                $this->sendEnrolmentEmailsHelper($subject, $body, $mhr_user_to,
                        $mhr_user_from, $message_type);
            }
        }

        if (!empty($mailteachers))
        {
            if ($mhr_user_to = $mhr_teacher)
            {
                $mhr_user_from = $mhr_user;
                $message_type = 'moodlemessage';
                $a = new StdClass;
                $a->user = $mdl_user->getObject()->firstname . ' ' . $mdl_user->getObject->lastname;
                $a->course = $course->getObject()->fullname;
                $subject = get_string("enrolmentnew", 'enrol_globalclassroom', $course->getObject()->shortname);
                $body = get_string('enrolmentnewuser', 'enrol_globalclassroom', $a);
                $this->sendEnrolmentEmailsHelper($subject, $body, $mhr_user_to,
                        $mhr_user_from, $message_type);
            }
        }

        if (!empty($mailadmins))
        {
            $mhr_user_from = $mhr_user;
            if ($mhr_user_to = $mhr_owner)
            {
                $message_type = 'moodlemessage';
                $a = new StdClass;
                $a->user = $mdl_user->getObject()->firstname . ' ' . $mdl_user->getObject->lastname;
                $a->course = $course->getObject()->fullname;
                $subject = get_string("enrolmentnew", 'enrol_globalclassroom', $course->getObject()->shortname);
                $body = get_string('enrolmentnewuser', 'enrol_globalclassroom', $a);
                $this->sendEnrolmentEmailsHelper($subject, $body, $mhr_user_to,
                        $mhr_user_from, $message_type);
            }
        }      
    }
    protected function sendEnrolmentEmailsHelper($subject, $body, $mhr_user_to, $mhr_user_from, $message_type)
    {
        if (!$mhr_user_to->addMessageToInbox($subject, $body, false, $mhr_user_from, $message_type))
        {
            $full_name = 'Administrator';
            if ($mhr_user_from)
            {
                $full_name = $mhr_user_from->getFullNameString();
            }
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From: " . $full_name . "\r\n";
            mail($mhr_user_to->getObject()->email, $subject, $body, $headers);
        }
    }
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }
    // Any kind of errors should be sent here in most cases, with a 2nd parameter if it is fatal.
    // The 3rd arg can choose a url to send the user after error message, moodle index page is default.
    public function gcError ($log_message, $error_string = null, $link = null)
    {
        $backtrace = debug_backtrace();
        // prints to log file, "dd/mm/yyyy hh:mm:ss: eschool_short_name: user_id: error_message: calling_function_name"
        error_log("\n" . date('d/m/Y H:i:s', time()) . ": App=" . $this->short_name . " $log_message: Stack Trace:" .
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
    public function getInstitutionFromCookie()
    {
        if (isset($_COOKIE['gc_eschool']))
        {
            return Doctrine::getTable('GcrInstitution')->findOneByShortName($_COOKIE['gc_eschool']);
        }
        return false;
    }
    public function getTemporarySchemaName()
    {
        global $DB;
        $sql = 'SELECT nspname FROM pg_namespace WHERE oid = pg_my_temp_schema()';
        $temp_schema = $DB->get_records_sql($sql);
        $key = key($temp_schema);
        if (isset($key))
        {
            return $key;
        }
        return false;
    }
    public function isTemporaryTable($table_name)
    {
        global $DB;
        if ($temp_schema = $this->getTemporarySchemaName())
        {
            $sql = "SELECT table_name FROM information_schema.tables WHERE table_name = '$table_name' AND table_schema = '$temp_schema'";
            $result = $DB->get_records_sql($sql);
            if (count($result > 0))
            {
                return $result;
            }
        }
        return false;
    }
    public function isGuestUser()
    {
        return isguestuser();
    }
    public function isLoggedIn()
    {
        global $USER;
        if (isset($USER->id))
        {
            return ($USER->id > 0);
        }
        return false;
    }
    public function hasPrivilege($role, $reload = false)
    {
        if ($this->current_user)
        {
            return $this->current_user->getRoleManager()->hasPrivilege($role, $reload);
        }
        return false;
    }
    function loadPasswordSalts()
    {
        global $CFG;
        $salts = Doctrine::getTable('GcrEschoolSaltHistory')->findByEschoolid($this->id);
        $saltcount = 0;

        foreach($salts as $salt)
        {
            $salt_variable_name = 'passwordsaltalt' . ++$saltcount;
            $CFG->$salt_variable_name = $salt->getSalt();
        }
    }
    public function printFooter()
    {
        $footer = new GcrSymfonyFooter($this->getInstitution());
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
    public function replaceMnetKeys()
    {
        $mnet = get_mnet_environment();
        $mnet->replace_keys();
    }
    public function requireLogin()
    {
        if (!$this->isLoggedIn())
        {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
            {
                $wants_url = false;
            }
            else
            {
                $wants_url = $_SERVER['REQUEST_URI'];
            }
            $url = $this->getInstitutionJumpUrl($wants_url, $this->getInstitutionFromCookie());
            redirect($url);
        }
    }
    public function requireMahara()
    {
        $institution = $this->getInstitution();
        if ($this->isLoggedIn()) // Use the current user's mahara, if logged in
        {
            $user_institution = $this->current_user->getInstitution();
            if ($user_institution)
            {
                $institution = $user_institution;
            }
        }
        redirect($institution->getUrl() . $_SERVER['REQUEST_URI']);
    }
    public function requireMoodle()
    {
        return true;
    }

    public function installLangPack($pack)
    {
        global $CFG;
        require_once($CFG->libdir.'/adminlib.php');
        require_once($CFG->libdir.'/filelib.php');
        require_once($CFG->libdir.'/componentlib.class.php');
        $thisversion = '2.0'; // TODO this information should be taken from version.php or similar source
        make_upload_directory('lang');
        if (is_array($pack))
        {
            $packs = $pack;
        }
        else
        {
            $packs = array($pack);
        }
        foreach ($packs as $pack)
        {
            if ($cd = new component_installer('http://download.moodle.org', 'langpack/'.$thisversion, $pack.'.zip', 'languages.md5', 'lang'))
            {
                $status = $cd->install();
                switch ($status) 
                {
                case COMPONENT_ERROR:
                    if ($cd->get_error() == 'remotedownloaderror') {
                        $a = new stdClass();
                        $a->url = 'http://download.moodle.org/langpack/'.$thisversion.'/'.$pack.'.zip';
                        $a->dest = $CFG->dataroot.'/lang';
                        print_error($cd->get_error(), 'error', 'langimport.php', $a);
                    } else {
                        print_error($cd->get_error(), 'error', 'langimport.php');
                    }
                    break;
                case COMPONENT_INSTALLED:
                    if ($parentlang = get_parent_language($pack)) {
                        // install also parent pack if specified
                        if ($cd = new component_installer('http://download.moodle.org', 'langpack/'.$thisversion,
                                $parentlang.'.zip', 'languages.md5', 'lang')) {
                            $cd->install();
                        }
                    }
                    break;
                }
            }
            else
            {
                echo $OUTPUT->notification('Had an unspecified error with the component installer, sorry.');
            }
        }
    }

    public function deleteLangPack($uninstalllang)
    {
        global $CFG;
        require_once($CFG->libdir.'/filelib.php');
        foreach ($uninstalllang as $lang)
        {
            if ($lang == 'en')
            {
                $notice_error = 'English language pack can not be uninstalled';
                return $notice_error;
            }
            else
            {
                $dest1 = $CFG->dataroot.'/lang/'.$lang;
                $rm1 = false;
                if (file_exists($dest1)){
                    $rm1 = remove_dir($dest1);
                }
                if ($rm1)
                {
                    $notice_ok = get_string('langpackremoved','admin');
                    return $notice_ok;
                }
                else
                {    //nothing deleted, possibly due to permission error
                    $notice_error = 'An error has occurred, language pack is not completely uninstalled, please check file permissions';
                    return $notice_error;
                }
            }
        }
    }
    
	/**
     * checks the md5 of the zip file, grabbed from download.moodle.org,
     * against the md5 of the local language file from last update
     * @param string $lang
     * @param string $md5check
     * @return bool
     */
    public function isInstalledLang($lang, $md5check) 
    {
        global $CFG;
        $md5file = $CFG->dataroot.'/lang/'.$lang.'/'.$lang.'.md5';
        if (file_exists($md5file))
        {
            return (file_get_contents($md5file) == $md5check);
        }
        return false;
    }	
	
	 /**
     * Returns the latest list of available language packs from
     * moodle.org
     * @return array or false if can not download
     */
    public function getRemoteListOfLanguages() 
    {
        $source = 'http://download.moodle.org/langpack/2.0/languages.md5';
        $availablelangs = array();
        
        if ($content = download_file_content($source)) 
        {
            $alllines = explode("\n", $content);
            foreach($alllines as $line) 
            {
                if (!empty($line))
                {
                    $availablelangs[] = explode(',', $line);
                }
            }
            return $availablelangs;

        }
        else
        {
            return false;
        }
    }
    public function refreshSessionTimeout()
    {
        if (!$this->hasPrivilege('GCAdmin'))
        {
            $user = $this->current_user->getUserOnInstitution();
            if ($user)
            {
                $user->refreshSessionTimeout();
            }
        }
    }
}