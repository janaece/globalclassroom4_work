<?php
// mahara_cron.php
// Written/modified by: Steven Nelson
// On Date: 12/13/2010
// Description: This is a script that performs the same job as mahara's cron.

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();

$institutions = Doctrine_Core::getTable('GcrInstitution')->findAll();
GcrBackgroundProcessTypeMaharaCron::createProcess($institutions);