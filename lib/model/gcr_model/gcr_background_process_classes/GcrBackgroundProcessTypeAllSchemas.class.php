<?php

/**
 * Description of GcrBackgroundProcessMoodleUpdates:
 * 
 *
 * @author Ron Stewart
 */
abstract class GcrBackgroundProcessTypeAllSchemas extends GcrBackgroundProcessType
{
    protected $apps;
    
    protected function initialize()
    {
        $this->apps = array();
        $job_data = $this->process->getJobData();
        $short_names = explode(';', $job_data);
        foreach ($short_names as $short_name)
        {
            $app = GcrInstitutionTable::getApp($short_name);
            if ($app)
            {
                $this->apps[] = $app;
            }
        }
    }
    public static function createProcess($apps, $type)
    {
        foreach($apps as $app)
        {
            $apps_array[] = $app;
        }
        $process_count = 0;
        $app_arrays = array_chunk($apps_array, 
                ceil(count($apps_array) / gcr::backgroundProcessCount));
        foreach ($app_arrays as $app_array)
        {
            $job_data = '';
            $first_iteration_flag = true;
            foreach ($app_array as $app)
            {
                if ($first_iteration_flag)
                {
                    $first_iteration_flag = false;
                }
                else
                {
                    $job_data .= ';';
                }
                $job_data .= $app->getShortName();
            }
            $background_process = new GcrBackgroundProcess();
            $background_process->setJobData($job_data);
            $background_process->setProcessType($type);
            $background_process->save();
            exec('nohup php ' . gcr::rootDir . 'scripts/background_process.php ' . 
                    $background_process->getId() . ' > /dev/null &');
            $process_count++;
        }
        return $process_count;
    }
    
}

?>
