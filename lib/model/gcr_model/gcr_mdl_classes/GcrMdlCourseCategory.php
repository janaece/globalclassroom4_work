<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GcrMdlCourseCategory
 *
 * @author ron
 */
class GcrMdlCourseCategory extends GcrMdlTableRecord
{
    protected $courses;
    
    public function getContext()
    {
        $sql = 'select * from ' . $this->app->getShortName() .
            '.mdl_context where contextlevel = ? and instanceid = ?';
        $mdl_context = $this->app->gcQuery($sql, array(40, $this->obj->id), true);
        return $mdl_context;
    }
    public function getCourses($refresh = false)
    {
        if (!isset($this->courses) || $refresh)
        {
            $mdl_courses = $this->getMdlCourses();
            foreach ($mdl_courses as $mdl_course)
            {
                $this->courses[] = new GcrMdlCourse($mdl_course, $this->app);
            }
        }
        return $this->courses;
    }
    public function getMdlCourses()
    {
        return $this->app->selectFromMdlTable('course', 'category', $this->obj->id);
    }
    public function getCourseCollection()
    {
        return GcrCourseCollection::getInstance($this);   
    }
    public function getDescription()
    {
        $baseurl = $this->app->getAppUrl() . '/pluginfile.php/' . 
                $this->getContext()->id . '/coursecat/description/';
        return str_replace('@@PLUGINFILE@@/', $baseurl, $this->obj->description);

    }
}

?>
