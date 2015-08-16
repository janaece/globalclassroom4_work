<?php

/**
 * Description of GcrBackgroundProcessTypeMaharaLdapSync:
 * 
 *
 * @author Ron Stewart
 */
class GcrBackgroundProcessTypeMaharaLdapSync extends GcrBackgroundProcessTypeAllSchemas
{
    public function startProcess() 
    {
        define('INTERNAL', true);
        foreach($this->apps as $institution)
        {
            $short_name = $institution->getShortName();
            
            if (GcrEschoolTable::isShortNameValid($short_name))
            {
                error_log("\n" . date('d/m/Y H:i:s', time()) . ": App=" . $short_name . 
                        ": Starting LDAP sync", 3, gcr::rootDir . 'debug/error.log');
                print "\n" . date('d/m/Y H:i:s', time()) . ": Starting LDAP sync for $short_name";
                $command = "/usr/bin/php " . gcr::maharaDir . "auth/ldap/cli/sync_users.php now $short_name";
                system($command);
            }
            
        }
        $this->process->delete();
    }
    public static function createProcess($eschools)
    {
        return GcrBackgroundProcessTypeAllSchemas::createProcess($eschools, 'MaharaLdapSync');
    }
}

?>