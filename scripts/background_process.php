<?php
// admin_operation.php
// Written by: Ron Stewart 
// On Date: 12/01/2011
// Description: This script offloads heavy processes from the web client, and splits
// them in to gcr::adminOperationProcesses processes.

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
require_once(dirname(__FILE__).'/../lib/model/gcr_model/gcr_accounting_classes/GcrEschoolAccount.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();

if (isset($argv[1]))
{
    $background_process = GcrBackgroundProcessFactory::getBackgroundProcess($argv[1]);
}
if (!$background_process)
{
     error_log("\n" . date('d/m/Y H:i:s', time()) . ": background_process.php: parameter invalid: " . 
             $argv[1], 3, gcr::rootDir . 'debug/error.log');
        
}
$background_process->startProcess();
die();
?>