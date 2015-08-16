<?php

/**
 * Description of GcrBackgroundProcessTypeResetMdlRoles:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeResetMdlRoles extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        $admin_operation = new GcrAdminOperation($this->apps);
        $admin_operation->resetMdlRoles();
        $this->process->delete();
    }
    public static function createProcess($eschools)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'ResetMdlRoles');
    }
}

?>
