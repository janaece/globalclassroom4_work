<?php 
global $CFG;
$institution = GcrInstitutionTable::getInstitution($eschool_name);
$mhr_user_obj = $institution->getOwnerUser()->getObject();
?>
<form enctype="multipart/form-data" id="eschoolManualForm" name="eschoolManualForm" action="/homeadmin/doManualEschool" method="POST">
  <table id="eschool_manual_form" cellpadding="5">
    <?php echo $eschoolManualForm->renderHiddenFields();?>
    <input name="user_institution_id" id="user_institution_id" type="hidden" value="<?php print $eschool_name; ?>" />
    <tr>
      <td>
      	eSchool Name:
      </td>
      <td>
      	<?php print $institution->getFullName() . ' (' . $eschool_name . ')'; ?>
      </td>
      <td> &nbsp</td>
    </tr>
    <tr>
      <td>
      	eSchool Owner:
      </td>
      <td>
      	<?php echo $mhr_user_obj->lastname . ", " . $mhr_user_obj->firstname . " (" . $mhr_user_obj->username . ")"; ?>
      </td>
      <td> &nbsp</td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolManualForm['amount_field']->renderLabel(); ?>
      </td>
      <td>
      	$<?php echo $eschoolManualForm['amount_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolManualForm['trans_time']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolManualForm['trans_time']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolManualForm['trans_time']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $eschoolManualForm['amount_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolManualForm['profile_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolManualForm['profile_id']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspand="2" class="symfonyFormError">
      	<?php echo $eschoolManualForm['profile_id']->renderError(); ?>
      </td>
    </tr>
     <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolManualForm['bill_cycle']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolManualForm['bill_cycle']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $eschoolManualForm['bill_cycle']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Submit Manual Payment" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Cancel" onclick="window.location.href='<?php print $return_url; ?>'" />
	   <?php 
	    if ($id = $eschoolManualForm->getObject()->getId())
    	{
    		$delete_url = $CFG->current_app->getUrl() . '/homeadmin/deleteManualPurchase?id=' . $eschoolManualForm->getObject()->getId(); ?>
	      	<input class="button" type="button" value="Delete" onclick="return confirmClick('Are you sure you want to delete this transaction?', '<?php print $delete_url ?>')" />
		<?php 
	    }?>
      </td>	
    </tr>
  </table>
</form>