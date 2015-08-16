<?php

/**
 * Description of GcrBackgroundProcessTypeMnetReplacement:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeUpdateMdlIndexing extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        $admin_operation = new GcrAdminOperation($this->apps);
        $admin_operation->updateMdlIndexing();
        $this->process->delete();
    }
    public static function createProcess($eschools)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'UpdateMdlIndexing');
    }
}

?>