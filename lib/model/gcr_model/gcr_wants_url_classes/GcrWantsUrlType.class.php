<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrWantsUrlType:
 * This class is the base type for a strategy pattern to handle various
 * redirect types which need different behavior in the application
 *
 * i.e. when a user registers while attempting to access a page, a
 * 'register' type GcrWantsUrl record is saved. This wantsurl should
 * not be redirected to until the user has completed the registration
 * process. Alternatively, a 'simple' type GcrWantsUrl triggers a redirect
 * immediately.
 *
 * @author ron
 */
class GcrWantsUrlType
{
    protected $wants_url;
    const KEY = 'gcr_wants_url';
    const TYPE = 'gcr_wants_url_type';
    const DEFAULT_TYPE = 'simple';

    protected function  __construct(GcrWantsUrl $wants_url)
    {
        $this->wants_url = $wants_url;
    }
    public static function createGetParam($value = null)
    {
        if (!isset($value))
        {
            $value = $_COOKIE[self::KEY];
        }
        return self::KEY . '=' . $value;
    }
    public static function getInstance(GcrWantsUrl $wants_url)
    {
        $classname = 'GcrWantsUrlType' . ucFirst($wants_url->getRedirectType());
        return new $classname($wants_url);
    }
    public function handleRequest()
    {
        if (isset($_GET[self::KEY]))
        {
            $this->executeGetParamAction();
        }
        if (isset($_COOKIE[self::KEY]) && $_COOKIE[self::KEY] != '')
        {
            $this->executeCookieAction();
        }
    }
    public function executeGetParamAction()
    {
        // do nothing by default
    }
    public function executeCookieAction()
    {
        // do nothing by default
    }
    public static function getCookie()
    {
        if (isset($_COOKIE[self::KEY]) && $_COOKIE[self::KEY] != '')
        {
            return $_COOKIE[self::KEY];
        }
        return false;
    }
    public function setCookie($value = false)
    {
        if (!$value)
        {
            $value = $this->wants_url->getId();
        }
        setcookie(self::KEY, $value, time() + 24*60*60*30, '/');
    }
    public static function unsetCookie()
    {
        setcookie (self::KEY, "", time() - 3600);
    }
}
?>
