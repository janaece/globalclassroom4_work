<?php global $CFG; ?>
<form enctype="multipart/form-data" id="classroomManualForm" name="classroomManualForm" action="/homeadmin/doManualClassroom" method="POST">
  <table id="classroom_manual_form" cellpadding="5">
    <?php echo $classroomManualForm->renderHiddenFields();?>
    <input name="user_institution_id" id="user_institution_id" type="hidden" value="<?php print $eschool_name; ?>" />
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $classroomManualForm['purchase_user_field']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $classroomManualForm['purchase_user_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $classroomManualForm['purchase_user_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $classroomManualForm['purchase_type_eschool_field']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $classroomManualForm['purchase_type_eschool_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $classroomManualForm['purchase_type_eschool_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $classroomManualForm['amount_field']->renderLabel(); ?>
      </td>
      <td>
      	$<?php echo $classroomManualForm['amount_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $classroomManualForm['amount_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $classroomManualForm['trans_time']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $classroomManualForm['trans_time']->render(); ?>
      </td>
      <td>
      	<?php echo $classroomManualForm['trans_time']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $classroomManualForm['profile_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $classroomManualForm['profile_id']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspand="2" class="symfonyFormError">
      	<?php echo $classroomManualForm['profile_id']->renderError(); ?>
      </td>
    </tr>
     <tr>
      <td class="symfonyFormLabel">
      	<?php echo $classroomManualForm['bill_cycle']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $classroomManualForm['bill_cycle']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $classroomManualForm['bill_cycle']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Submit Manual Payment" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Cancel" onclick="window.location.href='<?php print $return_url; ?>'" />
      	<?php 
	    if ($id = $classroomManualForm->getObject()->getId())
            {
    		$delete_url = $CFG->current_app->getUrl() . '/homeadmin/deleteManualPurchase?id=' . $classroomManualForm->getObject()->getId(); ?>
	      	<input class="button" type="button" value="Delete" onclick="return confirmClick('Are you sure you want to delete this transaction?', '<?php print $delete_url ?>')" />
		<?php 
	    }?>
      </td>
    </tr>
  </table>
</form>