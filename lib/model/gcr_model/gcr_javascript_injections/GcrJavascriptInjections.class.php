<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This class loads all onload style injections we make throughout the
 * platform
 *
 * @author Ron Stewart
 * Created: 11/10/2011
 */
class GcrJavascriptInjections 
{
    public static function getAll()
    {
        $html = '';
        foreach (self::getInjectList() as $item)
        {
            
            $classname = 'GcrJavascriptInjections' . $item;
            $injection = new $classname();
            if ($injection->checkConstraints())
            {
                $html .= $injection->getJavaScript();
            }
        }
        return $html;
    }
    public static function getInjectList()
    {
        return array('ChangeUsername', 'CourseReports', 'CloudStorage', 'ProfileBlockHeader', 'Participants', 'RepairCourse');
    }
    
}

?>
