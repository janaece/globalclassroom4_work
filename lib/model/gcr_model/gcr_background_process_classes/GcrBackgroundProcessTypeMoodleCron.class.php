<?php

/**
 * Description of GcrBackgroundProcessTypeMoodleCron:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeMoodleCron extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        define('MOODLE_INTERNAL', true);
        require(gcr::moodleDir . "version.php");
        foreach($this->apps as $eschool)
        {
            $short_name = $eschool->getShortName();
            $eschool_version = $eschool->getConfigVar('version');
            $upgrade_running = $eschool->getConfigVar('upgraderunning');
            
            if (empty($eschool_version))
            {
                error_log('Version data missing from ' . $short_name, 3, gcr::rootDir . 'debug/error.log');
            }
            if ((!$upgrade_running) && ($version <= $eschool_version))
            {
                if (GcrEschoolTable::isShortNameValid($short_name))
                {
                    error_log("\n" . date('d/m/Y H:i:s', time()) . ": App=" . $short_name . 
                            ": Starting Cron Script", 3, gcr::rootDir . 'debug/error.log');
                    print "\n" . date('d/m/Y H:i:s', time()) . ": Starting cron for $short_name";
                    $command = "/usr/bin/php " . gcr::moodleDir . "admin/cli/cron.php --eschool $short_name";
                    system($command);
                }
            }
            else
            {
                print "\n" . date('d/m/Y H:i:s', time()) . ": Delaying cron for $short_name, upgrade pending";
            }
        }
        $this->process->delete();
    }
    public static function createProcess($eschools)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'MoodleCron');
    }
}

?>