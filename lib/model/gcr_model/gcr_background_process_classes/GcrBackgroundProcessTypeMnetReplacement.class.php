<?php

/**
 * Description of GcrBackgroundProcessTypeMnetReplacement:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeMnetReplacement extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        $admin_operation = new GcrAdminOperation($this->apps);
        $admin_operation->mnetReplacement($this->process->getId());
        $this->process->delete();
    }
    public static function createProcess($institutions)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($institutions, 'MnetReplacement');
    }
}

?>
