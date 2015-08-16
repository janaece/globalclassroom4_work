<?php global $CFG; ?>
<form enctype="multipart/form-data" id="courseManualForm" name="courseManualForm" action="/homeadmin/doManualCourse" method="POST">
  <table id="course_manual_form" cellpadding="5">
    <?php echo $courseManualForm->renderHiddenFields();?>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['purchase_user_field']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $courseManualForm['purchase_user_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $courseManualForm['purchase_user_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['purchase_type_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $courseManualForm['purchase_type_id']->render(); ?>
      </td>
    </tr>
    <tr colspan="2" class="symfonyFormError">
      <td>
      	<?php echo $courseManualForm['purchase_type_id']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['amount_field']->renderLabel(); ?>
      </td>
      <td>
      	$<?php echo $courseManualForm['amount_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $courseManualForm['amount_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['trans_time']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $courseManualForm['trans_time']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $courseManualForm['trans_time']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['profile_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $courseManualForm['profile_id']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspand="2" class="symfonyFormError">
      	<?php echo $courseManualForm['profile_id']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['purchase_type_quantity']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $courseManualForm['purchase_type_quantity']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $courseManualForm['purchase_type_quantity']->renderError(); ?>
      </td>
    </tr>
     <tr>
      <td class="symfonyFormLabel">
      	<?php echo $courseManualForm['purchase_type_description']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $courseManualForm['purchase_type_description']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $courseManualForm['purchase_type_description']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Submit Manual Payment" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Cancel" onclick="window.location.href='<?php print $return_url; ?>'" />
      	<?php 
	    if ($id = $courseManualForm->getObject()->getId())
            {
    		$delete_url = $CFG->current_app->getUrl() . '/homeadmin/deleteManualPurchase?id=' . $courseManualForm->getObject()->getId(); ?>
	      	<input class="button" type="button" value="Delete" onclick="return confirmClick('Are you sure you want to delete this transaction?', '<?php print $delete_url ?>')" />
		<?php 
	    }?>
      </td>
    </tr>
  </table>
</form>