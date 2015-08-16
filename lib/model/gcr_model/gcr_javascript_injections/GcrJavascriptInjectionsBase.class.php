<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrJavascriptInjectionsBase
 *
 * @author ron
 */
abstract class GcrJavascriptInjectionsBase 
{
    abstract public function getJavaScript();
    abstract public function checkConstraints(); // constrain when this injection is applied 
}

?>
