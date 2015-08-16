<?php

/**
 * Description of GcrBackgroundProcessMoodleUpdates:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeMoodleUpdates extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        foreach($this->apps as $eschool)
        {
            $output = '';
            $ts = time();
            $short_name = $eschool->getShortName(); 
            if (GcrEschoolTable::isShortNameValid($short_name))
            {
                $command = "/usr/bin/php " . gcr::moodleDir . "admin/cli/upgrade.php --eschool $short_name --non-interactive";
                system($command, $output);
                
                // If everything worked, this second execution should return int(0),
                // which signifies that everything is already up to date.
                system($command, $output); 
                if ($output === 0)
                {
                    $text = ': Moodle updates for ' . $short_name . 
                        ' completed in ' . (time() - $ts) . ' seconds.';
                }
                else
                {
                    $text = ': Moodle updates FAILED for ' . $short_name . 
                        ' after ' . (time() - $ts) . ' seconds.';               
                }
                $eschool->deleteCacheDirectories();
                error_log("\n" . date('d/m/Y H:i:s', time()) . $text, 3, gcr::rootDir . 'debug/error.log');
            }
        }
        $this->process->delete();
    }
    public static function createProcess($eschools)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'MoodleUpdates');
    }
    
}

?>
