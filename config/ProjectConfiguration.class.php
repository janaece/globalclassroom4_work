<?php
require_once '/var/www/globalclassroom4/vendor/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('prestaPaypalPlugin');
  }
  // REGISTER HTML PURIFIER:
  static protected $HTMLPurifierLoaded = false;
  static public function registerHTMLPurifier()
  {
    if(self::$HTMLPurifierLoaded) {
      return;
    }
 
    require_once('/var/www/globalclassroom4/web/portal/lib/htmlpurifier/HTMLPurifier/Bootstrap.php');
 
    spl_autoload_register(array('HTMLPurifier_Bootstrap', 'autoload'));
 
    self::$HTMLPurifierLoaded = true;
  }
  
}
