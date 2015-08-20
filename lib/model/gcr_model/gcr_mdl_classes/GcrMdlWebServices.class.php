<?php
// added by Mohan following Ron's email for fixing xmlrpc
require_once(gcr::rootDir . 'lib/xmlrpc/lib/xmlrpc.inc');

class GcrMdlWebServices
{
    public static function deleteUser(GcrMhrUser $mhr_user_deleted)
    {
        
        global $CFG;
        $mhr_user_obj = $mhr_user_deleted->getObject();
        $username_array = explode('.', $mhr_user_obj->username);

        $mhr_user_obj->username = $username_array[0];
        $mhr_user = new GcrMhrUser($mhr_user_obj, $mhr_user_deleted->getApp());
        foreach ($CFG->current_app->getMnetEschools() as $eschool)
        {
           
            $mdl_user = $mhr_user->getUserOnEschool($eschool);
            
            if ($mdl_user)
            {
                $params = array($mdl_user->getObject()->id);
                $result = self::executeWebServiceRequest($eschool, 'moodle_user_delete_users', $params);
            }
        }
    }
    public static function getCourses()
    {
        return self::executeWebRequestOnMnetEschools('moodle_course_get_courses');
    }
    protected static function executeWebRequestOnMnetEschools($func, $params = array())
    {
        global $CFG;
        $results = array();
        
        foreach ($CFG->current_app->getMnetEschools() as $eschool)
        {
            $wwwroot = $eschool->getAppUrl();
            $result = self::executeWebServiceRequest($eschool, $func, $params);
            if ($result)
            {
               $results[$wwwroot] = $result; 
            }
        }
        return $results;
    }
    protected static function executeWebServiceRequest($eschool, $func, $params = array())
    {
        global $CFG;
        $result = false;
        $server = array();
        $params = php_xmlrpc_encode($params);
        $wwwroot = $eschool->getAppUrl();
        if ($server[$wwwroot] = $eschool->getWebServicesToken())
        {
            $xmlrpc_client = new xmlrpc_client("$wwwroot/webservice/xmlrpc/server.php?wstoken=$server[$wwwroot]");
            $xmlrpc_msg = new xmlrpcmsg($func, array($params));
            $xmlrpc_resp = $xmlrpc_client->send($xmlrpc_msg);
            if ($xmlrpc_resp == false)
            {
                $CFG->current_app->gcError('GcrWebService call failed: ' . $func);
            }
            if (!$xmlrpc_resp->faultCode())
            {
                $result = php_xmlrpc_decode($xmlrpc_resp->value());
            }
            if (!empty($xmlrpc_resp->errstr))
            {
                $CFG->current_app->gcError('GcrWebService call error: ' . $xmlrpc_resp->errstr);
            }
        }
        return $result;
    }
    public static function getUserCourses()
    {
        global $CFG;
        $current_user = $CFG->current_app->getCurrentUser();
        $params = array('username' => array($current_user->getObject()->username));
        return self::executeWebRequestOnMnetEschools('moodle_gcrwebservices_get_user_courses', $params);
    }

    public static function createCourse($fields, $eschoolid)
    {
        $params = array($fields);
        $eschool = Doctrine::getTable('GcrEschool')->findOneById($eschoolid);
        return self::executeWebServiceRequest($eschool, 'moodle_course_create_courses', $params);
    }
    
    public static function createCategory($fields, $eschoolid)
    {
        $params = array($fields);
        $eschool = Doctrine::getTable('GcrEschool')->findOneById($eschoolid);
        return self::executeWebServiceRequest($eschool, 'core_course_create_categories', $params);
    }
    public static function searchCourses($query)
    {
        $params = array('query' => $query);
        return self::executeWebRequestOnMnetEschools('moodle_gcrwebservices_search_courses', $params);
    }
}