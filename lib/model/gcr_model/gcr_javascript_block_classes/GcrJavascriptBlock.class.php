<?php
/**
 * Description of GcrJavascriptBlock
 *
 * @author ron
 */
class GcrJavascriptBlock 
{
    protected $params;
    protected $js;
    
    public function __construct($params = array())
    {
       $this->params = $params;
    }
    public function getJs()
    {
        return $this->js;
    }
    public function addJSArrayVar($array, $name)
    {
        $json = json_encode($array);
        $this->js .= $name . ' = ' . $json . ';';
    }
    
}

?>