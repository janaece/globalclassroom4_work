<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrJavascriptBlockFactory
 *
 * @author ron
 */
class GcrJavascriptBlockFactory 
{
    public static function getInstance($class, $params = array())
    {
        $javascript_block = false;
        $classname = 'GcrJavascriptBlock' . $class;
        if (class_exists($classname))
        {
           $javascript_block = new $classname($params); 
        }
        return $javascript_block;
    }
    public static function printBlock($class, $params = array())
    {
        $instance = self::getInstance($class, $params);
        print $instance->getJs();
    }
}

?>
