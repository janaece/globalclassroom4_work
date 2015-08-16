<?php global $CFG; 
$app = $CFG->current_app;
$course = $app->getCourse($params['course']);
$item_id = 'nonmember_' . $course->getApp()->getShortName() . '_' . $course->getObject()->id;
$item = Doctrine::getTable('GcrPurchaseItem')->find($item_id);
if (!$item)
{
    $app->gcError('Item ID: ' . $item_id . ' not found', 'gcdatabaseerror');
}

?>
<table class="content">
    <tr>
        <td>
            <?php
            try
            {
                include_partial('nonmemberform', array('app' => $app, 'course' => $course, 'item' => $item));
            }
            catch (Exception $e)
            {
                $CFG->current_app->gcError('Error loading partial class: ' . $e->getMessage(), 'gcdatabaseerror');
            }
            ?>
        </td>
    </tr>
</table>
