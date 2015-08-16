<?php

/**
 * admin actions.
 *
 * @package    globalclassroom
 * @subpackage admin
 * @author     Justin England
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends sfActions
{
    protected function checkAuthorization()
    {
        global $CFG;
        $CFG->current_app->requireMoodle();
        if ($CFG->current_app->isHome())
        {
            $CFG->current_app->requireLogin();
            if ($CFG->current_app->hasPrivilege('GCHomeAdmin') && !session_is_loggedinas())
            {
                return true;
            }
        }
        $CFG->current_app->gcError('Unprivileged attempted access to admin module!', 'gcpageaccessdenied');
    }
    public function executeCron(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['schema'] == '1' || $form['schema'] == '2')
        {
            if ($form['schema'] == '1')
            {
                $schemaList = Doctrine::getTable('GcrEschool')->findAll();
            }
            else
            {
                $schemaList = Doctrine::getTable('GcrInstitution')->findAll();
            }
        }
        else if ($app = GcrInstitutionTable::getApp($form['schema']))
        {
            $schemaList = array($app);
        }
        if (isset($schemaList) && $schemaList[0]->isMoodle())
        {
            $count = GcrBackgroundProcessTypeMoodleCron::createProcess($schemaList);
        }
        else
        {
            $count = GcrBackgroundProcessTypeMaharaCron::createProcess($schemaList);
        }
        
        $_SESSION['adminEschoolActionMessage'] = 'Run Cron started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
        
    }
    
    public function executeSyncLdap(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['institution'] == '1')
        {
            $schemaList = Doctrine::getTable('GcrInstitution')->findAll();        
        }
        else if ($app = GcrInstitutionTable::getApp($form['institution']))
        {
            $schemaList = array($app);
        }
        $count = GcrBackgroundProcessTypeMaharaLdapSync::createProcess($schemaList);
        
        
        $_SESSION['adminEschoolActionMessage'] = 'Run Sync Ldap started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeRefreshMdlMediaelementjsUrls(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $admin_operation = new GcrAdminOperation($eschools);
        $count = $admin_operation->refreshMdlMediaelementjsUrls();
        $_SESSION['adminEschoolActionMessage'] = 'Refresh Cloud Storage Video Urls Completed, ' . $count .
                ' Cloud Storage Urls were modified. (check debug/error.log for details)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeResetMdlCacheSettings(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $admin_operation = new GcrAdminOperation($eschools);
        $admin_operation->resetMdlCacheSettings();
        $_SESSION['adminEschoolActionMessage'] = $admin_operation->getMessage();
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
        
    }
    public function executeResetMdlCourseBlocks(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $count = GcrBackgroundProcessTypeResetMdlCourseBlocks::createProcess($eschools);
        $_SESSION['adminEschoolActionMessage'] = 'Reset Course Blocks started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeResetMdlRoles(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $count = GcrBackgroundProcessTypeResetMdlRoles::createProcess($eschools);
        $_SESSION['adminEschoolActionMessage'] = 'Reset MdlRoles started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    
    public function executeUser(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        if (!$this->message = $_SESSION['adminUserActionMessage'])
        {
            $this->message = '';
        }
        unset($_SESSION['adminUserActionMessage']);
        $this->userList = $CFG->current_app->selectFromMdlTable('user', false, false, false, 'username');
        $this->adminList = Doctrine_Core::getTable('AdminAccess')->findAll();
    }
   
    public function executeMnetReplacement(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['eschool'] == '1')
        {
            $institutions = Doctrine::getTable('GcrInstitution')->findAll();
        }
        else if ($institution = GcrInstitutionTable::getInstitution($form['eschool']))
        {
            $institutions = array($institution);
        }
        $count = GcrBackgroundProcessTypeMnetReplacement::createProcess($institutions);
        $_SESSION['adminEschoolActionMessage'] = 'Mnet Replacemnet started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeDeleteCacheDirectories(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['schema'] == '1' || $form['schema'] == '2')
        {
            if ($form['schema'] == '1')
            {
                $schemaList = Doctrine::getTable('GcrEschool')->findAll();
            }
            else
            {
                $schemaList = Doctrine::getTable('GcrInstitution')->findAll();
            }
        }
        else if ($app = GcrInstitutionTable::getApp($form['schema']))
        {
            $schemaList = array($app);
        }
        $admin_operation = new GcrAdminOperation($schemaList);
        $admin_operation->deleteCacheDirectories();
        $_SESSION['adminEschoolActionMessage'] = $admin_operation->getMessage();
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executePurgeCaches(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $count = GcrBackgroundProcessTypePurgeCaches::createProcess($eschools);
        $_SESSION['adminEschoolActionMessage'] = 'Purge Caches started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeAddAdminAccess(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['user'])
        {
            if ($mdl_user_object = $CFG->current_app->selectFromMdlTable('user', 'id', $form['user'], true))
            {
                $admin = new GcrAdminAccess();
                $admin->setUserid($mdl_user_object->id);
                $admin->setUsername($mdl_user_object->username);
                $admin->save();
            }
            $_SESSION['adminUserActionMessage'] = "Username $mdl_user_object->username added to sitewide admin list.";
        }
        $this->redirect($CFG->current_app->getUrl() . '/admin/user');
    }
    public function executeRemoveAdminAccess(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['admin'])
        {
            if ($admin = Doctrine_Core::getTable('GcrAdminAccess')->find($form['admin']))
            {
                $admin->delete();
            }
            $_SESSION['adminUserActionMessage'] = "Username {$admin->getUsername()} removed from sitewide admin list.";
        }
        $this->redirect($CFG->current_app->getUrl() . '/admin/user');
    }
    public function executeEschool(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        if(isset($_SESSION['adminEschoolActionMessage']))
        {
            if (!$this->message = $_SESSION['adminEschoolActionMessage'])
            {
                $this->message = '';
            }
        }
        unset($_SESSION['adminEschoolActionMessage']);
        $this->form = new GcrEschoolAdminForm();
        $this->searchForm = new GcrSearchEschoolForm();
        $this->userList = $CFG->current_app->selectFromMdlTable('user', false, false, false, 'username');
        $this->eschoolList = Doctrine_Query::create()
                ->select('e.*')
                ->from('GcrEschool e')
                ->orderBy('e.full_name')
                ->execute();
        $this->institutionList = Doctrine_Query::create()
                ->select('i.*')
                ->from('GcrInstitution i')
                ->orderBy('i.full_name')
                ->execute();
        $this->logFileList = GcrLogFile::getLogFilesList();
        $this->mdlConfigVars = $CFG->current_app->selectFromMdlTable('config', false, false, false, 'name');
        $this->mhrConfigVars = $CFG->current_app->getInstitution()->selectFromMhrTable('config', false, false, false, 'field');
        $this->pluginConfigVars = $CFG->current_app->selectFromMdlTable('config_plugins', false, false, false, 'name');
    }

    public function executeDeleteTrial(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        $trial = Doctrine::getTable('GcrTrial')->find($form['trials']);
        $trial->setEndDate(time());
        $trial->save();

        if ($institution = Doctrine::getTable('GcrInstitution')->find($trial->getOrganizationId()))
        {
            $_SESSION['adminEschoolActionMessage'] = "Trial for {$institution->getFullName()} ({$institution->getShortName()})" .
                    " ended at " . date('d/m/Y H:i:s', $trial->getEndDate());
        }
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeDeleteInstitution(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if ($institution = Doctrine::getTable('GcrInstitution')->find($form['institution']))
        {
            if ($institution->isHome())
            {
                $CFG->current_app->gcError('Attempt to delete home schema from system', 'gcdatabaseerror');
            }
            else
            {
                $institution->deleteInstitutionFromSystemEntirely();
            }
        }

        $_SESSION['adminEschoolActionMessage'] = $institution->getShortName() . ' deleted at ' . date('d/m/Y H:i:s', time());
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeSetOrganizationId(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if (!$institution = Doctrine::getTable('GcrInstitution')->find($form['institution']))
        {
            $CFG->current_app->gcError('Invalid Institution Id: ' . $form['institution'], 'gcdatabaseerror');
        }
        if (!$eschool = Doctrine::getTable('GcrEschool')->find($form['eschool']))
        {
            $CFG->current_app->gcError('Invalid Eschool Id: ' . $form['eschool'], 'gcdatabaseerror');
        }
        $institution = $eschool->getDefaultEschoolInstitution();
        if ($institution)
        {
            $CFG->current_app->gcError('Cannot change ownership of default eschool for platform ' . 
                    $institution->getShortName(), 'gcdatabaseerror');
        }
        try
        {
            $CFG->current_app->beginTransaction();
            $institution->setMnetConnection($eschool);
            $eschool->setMnetConnection($institution);
            $eschool->setOrganizationId($institution->getId());
            $eschool->save();
            $CFG->current_app->commitTransaction();
        }
        catch (Doctrine_Exception $e)
        {
            $CFG->current_app->rollbackTransaction();
            $CFG->current_app->gcError($e->getMessage(), 'gcdatabaseerror');
        }

        $_SESSION['adminEschoolActionMessage'] = "Eschool {$eschool->getFullName()} ({$eschool->getShortName()})" .
                " is now owned by institution {$institution->getFullName()} ({$institution->getShortName()})";
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeUpdateMdlConfig(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if (substr($form['config'], 0, 1) == '#')
        {
            $form['config'] = substr($form['config'], 1);
            $table_name = 'config_plugins';
        }
        else
        {
            $table_name = 'config';
        }
        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $admin_operation = new GcrAdminOperation($eschools);
        $admin_operation->updateMdlConfig($table_name, $form['config'], $form['newConfigValue']);
        $_SESSION['adminEschoolActionMessage'] = $admin_operation->getMessage();
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeUpdateMhrConfig(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if ($form['institution'] == '1')
        {
            $institutions = Doctrine::getTable('GcrInstitution')->findAll();
        }
        else if ($institution = GcrInstitutionTable::getInstitution($form['institution']))
        {
            $institutions = array($institution);
        }
        $admin_operation = new GcrAdminOperation($institutions);
        $admin_operation->updateMhrConfig($form['config'], $form['newConfigValue']);
        $_SESSION['adminEschoolActionMessage'] = $admin_operation->getMessage();
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeAutoUpdates(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if ($form['eschool'] == '1')
        {
            $eschools = Doctrine::getTable('GcrEschool')->findAll();
        }
        else if ($eschool = GcrEschoolTable::getEschool($form['eschool']))
        {
            $eschools = array($eschool);
        }
        $count = GcrBackgroundProcessTypeMoodleUpdates::createProcess($eschools);
        $_SESSION['adminEschoolActionMessage'] = 'Moodle Updates started, ' . $count .
                ' processes were created. (check debug/error.log for updates)';
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeExecuteSqlStatement(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['schema'] == '1' || $form['schema'] == '2')
        {
            if ($form['schema'] == '1')
            {
                $schemaList = Doctrine::getTable('GcrEschool')->findAll();
            }
            else
            {
                $schemaList = Doctrine::getTable('GcrInstitution')->findAll();
            }
        }
        else if ($app = GcrInstitutionTable::getApp($form['schema']))
        {
            $schemaList = array($app);
        }
        $admin_operation = new GcrAdminOperation($schemaList);
        $admin_operation->executeSqlStatement($form['sqlStatementStart'], $form['sqlStatementEnd']);

        $_SESSION['adminEschoolActionMessage'] = $admin_operation->getMessage();
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    // This function completely deletes an eschool from the system. This includes removing the database
    // schema and the moodledata directory for that eschool.
    public function executeDeleteEschool(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        $eschool = Doctrine::getTable('GcrEschool')->find($form['eschool']);
        if ($eschool)
        {
            $institution = $eschool->getDefaultEschoolInstitution();
            if ($institution)
            {
                $CFG->current_app->gcError('Attempt to delete default eschool for platform ' . 
                        $institution->getShortName(), 'gcdatabaseerror');
            }
            else if ($eschool->isHome())
            {
                $CFG->current_app->gcError('Attempt to delete homeadmin schema from system', 'gcdatabaseerror');
            }
            else
            {
                $eschool->deleteEschoolFromSystemEntirely();
            }
        }
        $_SESSION['adminEschoolActionMessage'] = $eschool->getShortName() . ' deleted at ' . date('d/m/Y H:i:s', time());
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeAddReservedName(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();
        if ($form['name'] != '')
        {
            if (Doctrine::getTable('GcrReservedNames')->findOneByName($form['name']))
            {
                $_SESSION['adminEschoolActionMessage'] = $form['name'] . ' is already on the list of reserved names.';
            }
            else
            {
                $reserved_name = new GcrReservedNames();
                $reserved_name->setName($form['name']);
                $reserved_name->save();
                $_SESSION['adminEschoolActionMessage'] = $form['name'] . ' successfully added to list of reserved eSchool names';
            }
        }
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeEschoolMessage(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $eschoolList = Doctrine::getTable('GcrEschool')->findAll();
        $platformList = Doctrine::getTable('GcrInstitution')->findAll();
        foreach($eschoolList as $eschool)
        {
            $eschool->setConfigVar('gc_eschool_message', $_POST['eschoolMessage']);
        }
        foreach($platformList as $mhr)
        {
            $mhr->setConfigVar('gc_eschool_message', $_POST['eschoolMessage']);
        }
        $message = "eSchool Message updated.";
        $_SESSION['adminEschoolActionMessage'] = $message;
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    public function executeViewLogFile(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        if ($num_lines = $request->getParameter('num_lines'))
        {
            if (!is_number($num_lines))
            {
                $num_lines = 100;
            }
        }
        else
        {
            $num_lines = 100;
        }
        if (!$log_file = $request->getParameter('log_file'))
        {
            $CFG->current_app->gcError('Missing Log File Parameter', 'gcdatabaseerror');
        }
        $this->log_file = new GcrLogFile($log_file, $num_lines);
    }
    /**
     * Created by: Ron Stewart
     * Date: 9/8/2011
     * This function was designed to set all non-siteadmin accounts to not receive emails (internal)
     * This was used to prevent spamming where bulk uploaded users were assigned random email
     * addresses at globalclassroom.us
     */
    public function executeTurnOffEmails(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $form = $request->getPostParameters();

        if ($institution = Doctrine::getTable('GcrInstitution')->find($form['institution']))
        {
            $i = 0;
            foreach ($institution->selectFromMhrTable('usr') as $mhr_user_obj)
            {
                $mhr_user = new GcrMhrUser($mhr_user_obj, $institution);
                if ((!$mhr_user->getRoleManager()->hasPrivilege('EschoolStaff')) && $mhr_user_obj->id != 0)
                {
                    $filters = array();
                    $filters[] = new GcrDatabaseQueryFilter('field', '=', 'maildisabled');
                    $filters[] = new GcrDatabaseQueryFilter('usr', '=', $mhr_user_obj->id);
                    $q = new GcrDatabaseQuery($institution, 'usr_account_preference', 'select * from', $filters);
                    if ($q->executeQuery(true))
                    {
                        $institution->updateMhrTable('usr_account_preference',
                            array('value' => '1'),
                            array('usr' => $mhr_user_obj->id, 'field' => 'maildisabled'));
                    }
                    else
                    {
                        $institution->insertIntoMhrTable('usr_account_preference',
                            array(  'usr' => $mhr_user_obj->id,
                                    'field' => 'maildisabled',
                                    'value' => '1'));
                    }
                    $i++;
                }
            }
            $_SESSION['adminEschoolActionMessage'] = $institution->getFullName() . ' (' . $institution->getShortName() .
                    '): ' . $i . ' users had emailing turned off';
        }
        else
        {
            $CFG->current_app->gcError('Invalid Institution Id: ' . $form['institution'], 'gcdatabaseerror');
        }
        $this->redirect($CFG->current_app->getUrl() . '/admin/eschool');
    }
    
    /*************************************************************************
     * Below are commented out one-off functions which were used at one point to programmatically fix
     * an uncommon or one-time issue. Each has a comment explaining what they were used for at the time, who
     * created it, and when. 
     */

    // Created by: Ron Stewart
    // Date: 7/30/2011
    // This function was used to add our new message type "moodlemessage" to all maharas' mhr_activity_type
    // table.
    /*
    public function executeFixMoodleMessageType(sfWebRequest $request)
    {
        global $CFG;
        $this->checkAuthorization();
        $count = 0;
        foreach (GcrInstitutionTable::getInstance()->findAll() as $institution)
        {
            if (!$record = $institution->selectFromMhrTable('activity_type', '"name"', 'moodlemessage', true))
            {
                if ($activity_type = $institution->insertIntoMhrTable('activity_type', array('id' => gcr::autoNumber, '"name"' => 'moodlemessage', 'delay' => 0)))
                {
                    print '<br />moodlemessage type added to ' . $institution->getShortName();
                }
                else
                {
                    print '<br />ERROR, moodlemessage type NOT added to ' . $institution->getShortName();
                    $count++;
                }
            }
            else
            {
                print '<br />N/A, moodlemessage type already exists for ' . $institution->getShortName();
            }
        }
        print '<br /><br />Add moodlemessage type completed with ' . $count . ' errors.';
        die();
    } */
}
