<?php

/**
 * Description of GcrBackgroundProcessTypeResetMdlCourseBlocks:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeResetMdlCourseBlocks extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        $admin_operation = new GcrAdminOperation($this->apps);
        $admin_operation->resetMdlCourseBlocks();
        $this->process->delete();
    }
    public static function createProcess($eschools)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'ResetMdlCourseBlocks');
    }
}

?>
