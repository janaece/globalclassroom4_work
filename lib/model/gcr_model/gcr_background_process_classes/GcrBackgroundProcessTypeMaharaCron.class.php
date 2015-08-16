<?php

/**
 * Description of GcrBackgroundProcessTypeMoodleCron:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeMaharaCron extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        define('INTERNAL', true);
        require(gcr::maharaDir . 'lib/version.php');
        foreach($this->apps as $institution)
        {
            $short_name = $institution->getShortName();
            $upgrade_needed = $config->version > $institution->getConfigVar('version');
            $siteclosed = $institution->getConfigVar('siteclosed');
            if (!$upgrade_needed && (!$siteclosed || empty($siteclosed)))
            {
                if (GcrEschoolTable::isShortNameValid($short_name))
                {
                    error_log("\n" . date('d/m/Y H:i:s', time()) . ": App=" . $short_name . 
                            ": Starting Cron Script", 3, gcr::rootDir . 'debug/error.log');
                    print "\n" . date('d/m/Y H:i:s', time()) . ": Starting cron for $short_name";
                    $command = "/usr/bin/php " . gcr::maharaDir . "lib/cron.php now $short_name";
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
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'MaharaCron');
    }
}

?>