<?php

/**
 * Description of GcrMdlRole:
 * 
 *
 * @author Ron Stewart
 */
class GcrMdlRole extends GcrMdlTableRecord
{
    public function copyMdlRoleObjectValues($mdl_role)
    {
        $this->app->beginTransaction();
        $directory = $this->getRoleDirectory($mdl_role);
        $this->copyMdlRoleObjectSystemCapabilities($mdl_role);
        $this->copyMdlRoleObjectContextLevels($mdl_role);
        $this->copyMdlRoleObjectAllowAssign($mdl_role, $directory);
        $this->copyMdlRoleObjectAllowOverride($mdl_role, $directory);
        $this->copyMdlRoleObjectAllowSwitch($mdl_role, $directory);
        $this->app->commitTransaction();
    }
    public function getRoleDirectory($mdl_role)
    {
        $directory = array();
        $mdl_roles_remote = $mdl_role->getApp()->selectFromMdlTable('role');
        foreach ($mdl_roles_remote as $mdl_role_remote)
        {
            $mdl_role_local = $this->app->selectFromMdlTable('role', 'shortname', 
                    $mdl_role_remote->shortname, true);
            if ($mdl_role_local)
            {
                $directory[$mdl_role_remote->id] = $mdl_role_local;
            }
        }
        return $directory;
    }
    // We can only do the system context (1) because we don't know if
    // other contexts exist on disparate moodles.
    public function copyMdlRoleObjectSystemCapabilities(GcrMdlRole $mdl_role)
    {
        $filters = array();
        $filters[] = new GcrDatabaseQueryFilter('contextid', '=', 1);
        $this->deleteTableValues('role_capabilities', $filters);
        foreach ($mdl_role->getCapabilities($filters) as $mdl_role_capability)
        {
            $params = array('roleid' => $this->obj->id,
                            'capability' => $mdl_role_capability->capability,
                            'contextid' => 1,
                            'permission' => $mdl_role_capability->permission,
                            'timemodified' => time(),
                            'modifierid' => $mdl_role_capability->modifierid);
            $this->app->insertIntoMdlTable('role_capabilities', $params);
        }
    }
    public function deleteTableValues($table_name, $filters = array())
    {
        $filters[] = new GcrDatabaseQueryFilter('roleid', '=', $this->obj->id);
        $q = new GcrDatabaseQuery($this->app, $table_name, 'delete from', $filters);
        $q->executeQuery();
    }
    public function copyMdlRoleObjectAllowAssign(GcrMdlRole $mdl_role, $role_directory = false)
    {
        if (!$role_directory)
        {
            $role_directory = $this->getRoleDirectory($mdl_role);
        }
        $this->deleteTableValues('role_allow_assign');
        foreach ($mdl_role->getAllowAssign() as $mdl_role_allow_assign)
        {
            if (array_key_exists($mdl_role_allow_assign->allowassign, $role_directory))
            {
                $params = array('roleid' => $this->obj->id,
                                'allowassign' => $role_directory[$mdl_role_allow_assign->allowassign]->id);
                $this->app->insertIntoMdlTable('role_allow_assign', $params);
            }
        }
    }
    public function copyMdlRoleObjectAllowOverride(GcrMdlRole $mdl_role, $role_directory = false)
    {
        if (!$role_directory)
        {
            $role_directory = $this->getRoleDirectory($mdl_role);
        }
        $this->deleteTableValues('role_allow_override');
        foreach ($mdl_role->getAllowOverrides() as $mdl_role_allow_override)
        {
            if (array_key_exists($mdl_role_allow_override->allowoverride, $role_directory))
            {
                $params = array('roleid' => $this->obj->id,
                                'allowoverride' => $role_directory[$mdl_role_allow_override->allowoverride]->id);
                $this->app->insertIntoMdlTable('role_allow_override', $params);
            }
        }
    }
    public function copyMdlRoleObjectAllowSwitch(GcrMdlRole $mdl_role, $role_directory = false)
    {
        if (!$role_directory)
        {
            $role_directory = $this->getRoleDirectory($mdl_role);
        }
        $this->deleteTableValues('role_allow_switch');
        foreach ($mdl_role->getAllowSwitches() as $mdl_role_allow_switch)
        {
            if (array_key_exists($mdl_role_allow_switch->allowswitch, $role_directory))
            {
                $params = array('roleid' => $this->obj->id,
                                'allowswitch' => $role_directory[$mdl_role_allow_switch->allowswitch]->id);
                $this->app->insertIntoMdlTable('role_allow_switch', $params);
            }
        }
    }
    public function copyMdlRoleObjectContextLevels(GcrMdlRole $mdl_role)
    {
        $this->deleteTableValues('role_context_levels');
        foreach ($mdl_role->getContextLevels() as $mdl_role_context_level)
        {
            $params = array('roleid' => $this->obj->id,
                            'contextlevel' => $mdl_role_context_level->contextlevel);
            $this->app->insertIntoMdlTable('role_context_levels', $params);
        }
    }
    public function getContextLevels()
    {
        return $this->app->selectFromMdlTable('role_context_levels', 'roleid', $this->obj->id);
    }
    public function getAllowSwitches()
    {
        return $this->app->selectFromMdlTable('role_allow_switch', 'roleid', $this->obj->id);
    }
    public function getAllowOverrides()
    {
        return $this->app->selectFromMdlTable('role_allow_override', 'roleid', $this->obj->id);
    }
    public function getAllowAssign()
    {
        return $this->app->selectFromMdlTable('role_allow_assign', 'roleid', $this->obj->id);
    }
    public function getCapabilities($filters = array(), $return_one = false)
    {
        $filters[] = new GcrDatabaseQueryFilter('roleid', '=', $this->obj->id);
        $q = new GcrDatabaseQuery($this->app, 'role_capabilities', 'select * from', $filters);
        return $q->executeQuery($return_one);
    }
}

?>
