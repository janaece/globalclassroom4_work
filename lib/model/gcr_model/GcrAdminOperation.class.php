<?php
// This class handles most admin module actions
// Created by Ron Stewart
// Date: June 21, 2011

class GcrAdminOperation
{
    protected $eschool_bot;
    protected $app_array;
    protected $failure_list;
    protected $max_failures_allowed;
    protected $start_time;
    protected $message;
    protected $completion_time;
    protected $app_description;
    protected $operation_description;
    protected $kill_operation;

    public function __construct($app_array)
    {
        $this->eschool_bot = new EschoolBot();
        $this->app_array = $app_array;
        if (count($this->app_array) > 1)
        {
            $this->app_description = 'All Schemas';
        }
        else
        {
            $this->app_description = $app_array[0]->getShortName();
        }
        $this->failure_list = array();
        $this->max_failures_allowed = 10;
        $this->start_time = false;
        $this->message = '';
        $this->completion_time = false;
        $this->kill_operation = false;
        $this->operation_description = '';
    }

    public function autoUpdates()
    {
        $this->start_time = time();
        $this->operation_description = 'Auto Updates';
        foreach($this->app_array as $eschool)
        {
            $ts = time();
            $this->eschool_bot->setApp($eschool);
            if (!$result = $this->eschool_bot->performAutoUpdate())
            {
                $failureList[] = $eschool->getShortName();
                $this->setKillOperation();
            }
            $seconds = time() - $ts;
            $this->updateLogFile($eschool, $result, $seconds);
        }
        $this->close();
    }
    public function executeSqlStatement($sql_start, $sql_end)
    {
        $this->start_time = time();
        $this->operation_description = 'SQL Statement: "' . stripslashes($sql_start .
                ' ' . $this->app_description . '.' . $sql_end . '"');
        foreach($this->app_array as $app)
        {
            $sql = $this->constructSqlStatement($app, $sql_start, $sql_end);
            $result = $app->gcQuery($sql, array(), false, true);
            if ($result == -1)
            {
                $this->failure_list[] = $app->getShortName();
            }
            else
            {
                GcrDatabaseAccessPostgres::logQueryResult($sql, $result);
            }
        }
        $this->close();
    }
    protected function constructSqlStatement($app, $sql_start, $sql_end)
    {
        $sql_start = str_replace('<<GC_SCHEMA_NAME>>', $app->getShortName(), $sql_start);
        $sql_end = str_replace('<<GC_SCHEMA_NAME>>', $app->getShortName(), $sql_end);
        return stripslashes($sql_start . ' ' . $app->getShortName() . '.' . $sql_end);
    }
    public function resetMdlCourseBlocks()
    {
        $this->start_time = time();
        $this->operation_description = 'Reset Course Blocks:';
        foreach($this->app_array as $eschool)
        {
            $ts = time();
            $this->eschool_bot->setApp($eschool);
            if (!$result = $this->eschool_bot->performResetMdlCourseBlocks())
            {
                $failure_list[] = $eschool->getShortName();
                $this->setKillOperation();
            }
            $seconds = time() - $ts;
            $this->updateLogFile($eschool, $result, $seconds);
        }
        $this->close();
    }
    public function resetMdlCacheSettings()
    {
        global $CFG;
        $this->start_time = time();
        $this->operation_description = 'Reset Moodle Cache Settings:';
        
        foreach($this->app_array as $eschool)
        {
            if ($eschool->isHome())
            {
                continue;
            }
            if (!GcrEschoolTable::isShortNameValid($eschool->getShortName()))
            {
                $CFG->current_app->gcError($short_name . ' is an invalid shortname', 'gcdatabaseerror');
            }
            $eschool->setMdlCacheSettings();
        }
        $this->close();
    }
    public function refreshMdlMediaelementjsUrls()
    {
        $this->start_time = time();
        $count = 0;
        $this->operation_description = 'Refresh Cloud Storage Video Urls:';
        foreach ($this->app_array as $eschool)
        {
            $ts = time();
            $mdl_mediaelementjs_records = $eschool->selectFromMdlTable('mediaelementjs');
            foreach ($mdl_mediaelementjs_records as $mdl_mediaelementjs)
            {
                $old_url = $mdl_mediaelementjs->externalurl;
                $mdl_mediaelementjs = GcrStorageAccessS3::refreshUrl($mdl_mediaelementjs, $eschool->getInstitution());
                if ($old_url != $mdl_mediaelementjs->externalurl)
                {
                    $eschool->updateMdlTable('mediaelementjs', 
                                array('externalurl' => $mdl_mediaelementjs->externalurl),
                                array('id' => $mdl_mediaelementjs->id));
                    $count++;
                }
            }
            $seconds = time() - $ts;
            $this->updateLogFile($eschool, true, $seconds);
        }
        $this->close();
        return $count;
    }
    public function resetMdlRoles()
    {
        $this->start_time = time();
        $this->operation_description = 'mdl_roles set to template values on Moodle: <' . 
                $this->app_description . '>';
        $template_eschool = GcrEschoolTable::getPrimaryTemplate();
        foreach ($this->app_array as $eschool)
        {
            
            if ($eschool->isPrimaryTemplate())
            {
                continue;
            }
            $roles_copied = '';
            $start_app_ts = time(); 
            $template_mdl_roles = $template_eschool->selectFromMdlTable('role');
            // First, make sure that every role on the template exists or is created
            // on the moodle. This must be done first because otherwise foreign
            // key references to new roles will reference a non-existing record.
            foreach ($template_mdl_roles as $template_mdl_role_obj)
            {
                $mdl_role_obj = $eschool->selectFromMdlTable('role', 'shortname', 
                        $template_mdl_role_obj->shortname, true);
                if (!$mdl_role_obj)
                {
                    $sql = 'select max(sortorder) as max_sortorder from ' . 
                            $eschool->getShortName() . '.mdl_role';
                    $max_sortorder = $eschool->gcQuery($sql, array(), true);
                    $params = array('name' => $template_mdl_role_obj->name,
                                    'shortname' => $template_mdl_role_obj->shortname,
                                    'description' => $template_mdl_role_obj->description,
                                    'sortorder' => $max_sortorder->max_sortorder + 1,
                                    'archetype' => $template_mdl_role_obj->archetype);
                    $mdl_role_obj = $eschool->insertIntoMdlTable('role', $params);
                    $roles_copied .= $mdl_role_obj->shortname . ';';
                }
            }
            // Now, we copy all table data
            foreach ($template_mdl_roles as $template_mdl_role_obj)
            {
                $mdl_role_obj = $eschool->selectFromMdlTable('role', 'shortname', 
                        $template_mdl_role_obj->shortname, true);
                $mdl_role = new GcrMdlRole($mdl_role_obj, $eschool);
                $template_mdl_role = new GcrMdlRole($template_mdl_role_obj, $template_eschool);
                $mdl_role->copyMdlRoleObjectValues($template_mdl_role);
            }
            $seconds = time() - $start_app_ts;
            $result_text = false;
            if ($roles_copied != '')
            {
                $result_text = ' Roles: ' . $roles_copied . ' were copied from template in ' . 
                        $seconds  . ' seconds.';
            }
            $this->updateLogFile($eschool, true, $seconds, $result_text);
        }
    }
    public function updateMdlConfig($table_name, $var, $value)
    {
        $this->start_time = time();
        $this->operation_description = 'SQL Statements: UPDATE <' . $this->app_description .
                '>.mdl_' . $table_name . ' SET value = ' . $value . ' WHERE name = ' . $var;
        foreach($this->app_array as $eschool)
        {
            $sql = 'UPDATE ' . $eschool->getShortName() . '.' . gcr::moodlePrefix . $table_name .
                    ' SET value = ? WHERE name = ?';
            if ($eschool->gcQuery($sql, array($value, $var), false, true) == -1)
            {
                $failure_list[] = $eschool->getShortName();
                $this->setKillOperation();
            }
        }
        $this->close();
    }
    public function updateMhrConfig($var, $value)
    {
        $this->start_time = time();
        $this->operation_description = 'SQL Statements: UPDATE <' . $this->app_description .
                '>.mhr_config SET value = ' . $value . ' WHERE field = ' . $var;
        foreach($this->app_array as $institution)
        {
            $sql = 'UPDATE ' . $institution->getShortName() . '.' . gcr::maharaPrefix . 'config' .
                    ' SET value = ? WHERE field = ?';
            if ($institution->gcQuery($sql, array($value, $var), false, true) == -1)
            {
                $failure_list[] = $institution->getShortName();
                $this->setKillOperation();
            }
        }
        $this->close();
    }
    public function purgeCaches()
    {
        $this->start_time = time();
        $this->operation_description = 'Purge Caches:';
        foreach($this->app_array as $eschool)
        {
            $ts = time();
            $this->eschool_bot->setApp($eschool);
            if (!$result = $this->eschool_bot->performPurgeCaches())
            {
                $failure_list[] = $eschool->getShortName();
                $this->setKillOperation();
            }
            $seconds = time() - $ts;
            $this->updateLogFile($eschool, $result, $seconds);
        }
        $this->close();
    }
    public function deleteCacheDirectories()
    {
        $this->start_time = time();
        $this->operation_description = 'Delete Cache Directories:';
        foreach($this->app_array as $app)
        {
            $ts = time();
            $result = $app->deleteCacheDirectories();
            $seconds = time() - $ts;
            $this->updateLogFile($app, $result, $seconds);
        }
        $this->close();
    }
    public function mnetReplacement()
    {
        $this->start_time = time();
        $this->operation_description = 'MNET Connection Replacement';
        $this->max_failures_allowed = 10;
        $home = GcrEschoolTable::getHome();
        $token = GcrEschoolTable::generateRandomString();
        $home->setConfigVar('gc_replace_mnet_token' . $token, $token);

        foreach($this->app_array as $institution)
        {
            if ($this->kill_operation)
            {
                exit;
            }
            $this->replaceMnetInstitution($institution, $token);
        }
        $home->deleteFromMdlTable('config', 'name', 'gc_replace_mnet_token' . $token);
        $this->close();
    }
    public function close()
    {
        $this->completion_time = time() - $this->start_time;
        $this->setMessage();
        $this->eschool_bot->close();
    }
    public function setMessage()
    {
        if (count($this->failure_list) > $this->max_failures_allowed)
        {
            $this->message = $this->operation_description . ' terminated because more than ' .
                    $this->max_failures_allowed . ' update failures have occurred.';
        }
        else if (count($this->failure_list) > 0)
        {
            $this->message = $this->operation_description . ' completed with some failures, schema(s): ' .
                    implode(",", $this->failure_list) . ' did not complete successfully.';
        }
        else
        {
            $this->message = $this->operation_description . ' was successfully performed on ' .
                    $this->app_description . ' in ' . $this->completion_time . ' seconds';
        }
    }
    protected function replaceMnetInstitution($institution, $token)
    {
        if (!$this->replaceMnet($institution, $token))
        {
            $this->failure_list[] = $institution->getShortName();

        }
        $eschools = $institution->getEschools();
        foreach ($eschools as $eschool)
        {
            if ($this->kill_operation)
            {
                return false;
            }
            if (!$this->replaceMnet($eschool, $token))
            {
                $this->failure_list[] = $eschool->getShortName();
            }
        }
        $institution->updateEschoolMnetConnections();
        foreach ($eschools as $eschool)
        {
            $eschool->updateInstitutionMnetConnections();
        }
    }
    protected function updateLogFile($app, $success, $seconds = 0, $result_text = false)
    {
        if (!$result_text)
        {
            $result_text = ($success) ? " Completed in $seconds seconds." : " FAILED.";
        }
        $text = "\n" . date('d/m/Y H:i:s', time()) . ': ' . $app->getShortName() . ': ' .
                $this->operation_description . $result_text;
        error_log($text, 3, gcr::rootDir . 'debug/error.log');
    }
    protected function replaceMnet($app, $token)
    {
        $start_time = time();
        $this->eschool_bot->setApp($app, 'nologin');
        if ($this->eschool_bot->performMnetConnectionReplacement($token))
        {
            $seconds = time() - $start_time;
            // log that the eschool was updated successfully
            $this->updateLogFile($app, true, $seconds);
            return true;
        }
        else
        {
            $this->updateLogFile($app, false);
            $this->setKillOperation();
            return false;
        }
    }
    protected function setKillOperation($kill = false)
    {
        if ($kill || (count($this->failure_list) > $this->max_failures_allowed))
        {
            $this->kill_operation = true;
        }
    }
    public function getMessage()
    {
        return $this->message;
    }
}
?>