<?php
$meta_data = $course_list->getEndIndex();
$courses = $course_list->getCourseList();
if ($courses)
{
    foreach ($courses as $course)
    {
        include_partial('courseOutput', array('course' => $course, 'meta_data' => $meta_data));
        $meta_data = false;
    }
}
?>
