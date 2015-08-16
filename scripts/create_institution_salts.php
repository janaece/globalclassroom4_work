<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();
if (!$conn = Doctrine_Manager::getInstance()->getCurrentConnection())
{
    echo "Could not connect to Database.";
    die();
}

foreach (GcrInstitutionTable::getInstitutions() as $institution)
{
    $password_salt = $institution->getPasswordSalt();
    if ($password_salt == '')
    {
        $salt = GcrEschoolTable::generateRandomString();
        $institution->setPasswordSalt($salt);
        $institution->save();
        print "\n" . $institution->getShortName() . ': salt created';
    }
}

?>