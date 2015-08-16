<?php

/**
 * Description of GcrBackgroundProcessFactory:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessFactory 
{
    public static function getBackgroundProcess($id)
    {
        $background_process = GcrBackgroundProcessTable::getInstance()->find($id);
        if ($background_process)
        {
            $classname = 'GcrBackgroundProcessType' . $background_process->getProcessType();
            return new $classname($background_process);
        }
    }
    public static function getAll()
    {
        $background_process_types = array();
        $background_processes = GcrBackgroundProcessTable::getInstance()->findAll();
        if (count($background_processes) > 0)
        {
            foreach ($background_processes as $background_process)
            {
                $classname = 'GcrBackgroundProcessType' . 
                        $background_process->getProcessType();
                $background_process_types[] = new $classname($background_process);
            }
            
        }
        return $background_process_types;
    }
}

?>
