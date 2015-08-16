<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrNoUserSymfonyHeaderType
 *
 * @author ron
 */
class GcrNoUserSymfonyHeaderType extends GcrSymfonyHeaderType
{
    public function getPageHeaderDiv()
    {
        $content = parent::getPageHeaderDivStart();
        $content .= parent::getPageHeaderDivEnd();
        return $content;
    }
}
?>