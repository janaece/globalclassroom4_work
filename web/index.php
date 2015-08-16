<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
require_once('/var/www/globalclassroom4/lib/model/gcr_model/gcr.class.php');
gcr::initialize();
 
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
