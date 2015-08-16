<?php

/**
 * Description of GcrMhrInstitution
 *
 * @author ron
 */
class GcrMhrInstitution extends GcrMhrTableRecord 
{
    protected $eschools;
    protected $users;
    
    public function hasAccessToEschool($eschool)
    {
        $filters = array();
        $filters[] = new GcrDatabaseQueryFilter('mhr_institution_name', '=', $this->obj->name);
        $filters[] = new GcrDatabaseQueryFilter('eschool_id', '=', $eschool->getShortName());
        $q = new GcrDatabaseQuery($this->app, 'gcr_institution_catalog', 'select * from', $filters);
        $result = $q->executeQuery(true);
        if ($result)
        {
            return true;
        }
        return false;
    }
    public function getMhrGcrInstitutionCatalogs()
    {
        return $this->app->selectFromMhrTable('gcr_institution_catalog', 'mhr_institution_name', $this->obj->name);
    }
    public function getMhrUsrInstitutions()
    {
        return $this->app->selectFromMhrTable('usr_institution', 'institution', $this->obj->name);
    }
    public function getEschools($refresh = false)
    {
        if (!$this->eschools || $refresh)
        {
            $mhr_gcr_institution_catalogs = $this->getMhrGcrInstitutionCatalogs();
            if ($mhr_gcr_institution_catalogs)
            {
                foreach ($mhr_gcr_institution_catalogs as $mhr_gcr_institution_catalog)
                {
                    $eschool = GcrEschoolTable::getEschool($mhr_gcr_institution_catalog->eschool_id, true);
                    if ($eschool)
                    {
                        $this->eschools[] = $eschool;
                    }
                }
            }
        }
        return $this->eschools;
    }
    public function getUsers($refresh = false)
    {
        if (!$this->users || $refresh)
        {
            $mhr_usr_institutions = $this->getMhrUsrInstitutions();
            if ($mhr_usr_institutions)
            {
                foreach($mhr_usr_institutions as $mhr_usr_institution)
                {
                    $mhr_user = $this->app->getUserById($mhr_usr_institution->usr);
                    if ($mhr_user)
                    {
                        $this->users[] = $mhr_user;
                    }
                }
            }
        }
        return $this->users;
    }
    public function addEschool($eschool, $add_users_access = true)
    {
        $params = array('mhr_institution_name' => $this->obj->name, 'eschool_id' => $eschool->getShortName());
        $this->app->upsertIntoMhrTable('gcr_institution_catalog', 
                $params, array('mhr_institution_name' => $this->obj->name, 'eschool_id' => $eschool->getShortName()));
        if ($add_users_access)
        {
            $this->addUsersAccess($eschool);
        }
    }
    public function removeEschool($eschool)
    {
        $filters = array();
        $filters[] = new GcrDatabaseQueryFilter('mhr_institution_name', '=', $this->obj->name);
        $filters[] = new GcrDatabaseQueryFilter('eschool_id', '=', $eschool->getShortName());
        $q = new GcrDatabaseQuery($this->app, 'gcr_institution_catalog', 'delete from', $filters);
        $q->executeQuery();
    }
    public function addUsersAccess($eschool)
    {
        $this->getUsers();
        foreach($this->users as $mhr_user)
        {
            $mhr_user->addAccess($eschool);
        }
    }
}

?>
