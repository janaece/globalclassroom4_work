<?php
$mdl_course = $course->getObject();
$course_list_item = new GcrCourseListItem($course);
$eschool = $course->getApp();
$id = 'gcr_course_' . $eschool->getShortName() . '_' . $mdl_course->id;
$img_src = $course_list_item->getCourseIconUrl();
$mdl_user = $course_list_item->getInstructor();
$summary = $course_list_item->getSummary();
$enrol_count = $course_list_item->getActiveUserCount();

$shortsummary = GcrInstitutionTable::formatStringSize($summary, 250, 21);

if ($mdl_user)
{
    $teacher_text = GcrEschoolTable::getInstructorProfileHtml($mdl_user);
}
else
{
    $teacher_text = 'None';
}

$fullname = GcrInstitutionTable::formatStringSize($mdl_course->fullname, 60, 30);
$cost = $course->getCost();
$cost_text = '';
if ($cost)
{
    $cost_text = 'Price: ' . GcrPurchaseTable::gc_format_money($cost);
}


?>
<div class="gc_course_list_item col2">
    <?php
    if ($meta_data)
    {
        print '<span class="coursebox" end_index="' . $meta_data . '"></span>';
    }    
    ?>
    <div id="<?php print $id ?>" class="gc_course_list_item_container">
        <div class="gc_course_list_item_header">
            <div class="gc_course_list_item_title gc_course_list_item_container_element ">
                <a title="<?php print $mdl_course->fullname ?>" href="">
                    <?php print $fullname; ?>
                </a>
            </div>
        </div>
        <div class="gc_course_list_item_body">
            <div class="gc_course_list_item_top">
                <div class="gc_course_list_item_icon gc_course_list_item_container_element">
                    <img src="<?php print $img_src ?>" />
                </div>
            </div>
            <div class="gc_course_list_item_instructor gc_course_list_item_container_element">
                <b>Instructor: <?php print $teacher_text ?></b>
            </div>
            <div class="gc_course_list_item_description gc_course_list_item_container_element">
                <?php print $shortsummary ?>
            </div>
        </div>
        <div class="gc_course_list_item_footer">
            <span class="gc_course_list_item_enrol_count">
                Enrollments: <?php print $enrol_count; ?>
            </span>
            <span class="gc_course_list_item_cost">
                <?php print $cost_text; ?>
            </span> 
        </div>
    </div>
</div>
