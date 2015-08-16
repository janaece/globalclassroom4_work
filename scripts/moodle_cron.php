<?php
// moodle_cron.php
// Written/modified by: Steven Nelson
// On Date: 12/13/2010
// Description: This is a script that performs the same job as moodle's cron.  When called from the command line, the current cron
//for moodle throws errors because of our moodle config.php file customizations.

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();

$eschools = Doctrine_Core::getTable('GcrEschool')->findAll();
GcrBackgroundProcessTypeMoodleCron::createProcess($eschools);