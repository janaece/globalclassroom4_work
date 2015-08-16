<?php

/**
 * Description of GcrJavascriptBlockCourseCategoryArray
 *
 * @author ron
 */
class GcrJavascriptBlockCourseCategoryArray extends GcrJavascriptBlock
{
    public function getJs()
    {
        global $CFG;
        foreach($CFG->current_app->getMnetEschools() as $eschool)
        {
            if (GcrEschoolTable::authorizeEschoolAccess($eschool))
            {
                $this->js .= 'category_array["' . $eschool->getShortName() . '"] = [];';
                $categories = $eschool->getCourseCategories(false);
                foreach($categories as $category)
                {
                    if ($category->getObject()->visible == 1 || 
                            GcrEschoolTable::authorizeHiddenCategoryAccess($eschool))
                    {
                        $mdl_course_category = $category->getObject();
                        $this->js .= 'category_array["' . $eschool->getShortName() . 
                              '"]["' . $mdl_course_category->id . '"] = "' . $mdl_course_category->name . '";';
                    }
                }
            }
        }
        return $this->js;
    }
}

?>
