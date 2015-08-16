<?php

/**
 * Description of GcrSignedRequest
 *
 * @author ron
 */
class GcrSignedRequest 
{
    protected $parameters;
    protected $app;
    const SIGNATURE_PARAM = 'gc_sign_code';
    const SIGNED_REQUEST_SALT = 'hwerw87ab2';
    const SIGN_CONFIG_VAR = 'gc_app_sign_key';
     
    public function __construct($parameters = array(), $app = false)
    {
        if (!$app)
        {
            global $CFG;
            $app = $CFG->current_app;
        }
        $this->app = $app;
        $this->parameters = $parameters;
    }
    
    public function getParameters()
    {
        return $this->parameters;
    }
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
    
    protected function generateSignatureFromParameters()
    {
        $sorted_parameters = $this->parameters;
        ksort($sorted_parameters);
        if (!$app_key = $this->app->getConfigVar(self::SIGN_CONFIG_VAR))
        {
            $app_key = GcrEschoolTable::generateRandomString(15);
            $this->app->setConfigVar(self::SIGN_CONFIG_VAR, $app_key);
        }
        $plain_text_string = $app_key . self::SIGNED_REQUEST_SALT;
        
        if (is_array($sorted_parameters))
        {
            foreach ($sorted_parameters as $key => $value)
            {
                if ($key != self::SIGNATURE_PARAM)
                {
                   $plain_text_string .= $key . $value;
                }
            }
        }
        return md5($plain_text_string);
    }
    protected function generateSignatureWithoutParameters()    
    {
        if (!$app_key = $this->app->getConfigVar(self::SIGN_CONFIG_VAR))
        {
            $app_key = GcrEschoolTable::generateRandomString(15);
            $this->app->setConfigVar(self::SIGN_CONFIG_VAR, $app_key);
        }
        $plain_text_string = $app_key . self::SIGNED_REQUEST_SALT;
        return md5($plain_text_string);
    }
    public function validateSignature()
    {
        $signature = $this->getSignature();
        if ($signature)
        {
            if (isset($this->parameters['app']))
            {
                return ($signature == $this->generateSignatureFromParameters());
            }
            else
            {
                // TO DO: eliminate all preexisting URLS without parameters
                // This contingency case is a result of a previous coding error
                // which led to paratemerless signatures being assigned to all
                // static urls generated. Since all static urls generated after
                // the coding error was fixed include $this->parameters['app'], only
                // the old urls can ever validate this way. 
                return ($signature == $this->generateSignatureWithoutParameters());
            }
        }
        return false;
    }
    public function signParameters()
    {
        $this->setSignature($this->generateSignatureFromParameters());
    }
    public function getSignature()
    {
        $signature = $this->parameters[self::SIGNATURE_PARAM];
        if ($signature && $signature != '')
        {
            return $signature;
        }
        return false;
    }
    public function setSignature($value)
    {
        $this->parameters[self::SIGNATURE_PARAM] = $value;
    }
}

?>
