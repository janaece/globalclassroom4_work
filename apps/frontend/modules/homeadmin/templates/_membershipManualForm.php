<?php global $CFG; ?>
<form enctype="multipart/form-data" id="membershipManualForm" name="membershipManualForm" action="/homeadmin/doManualMembership" method="POST">
  <table id="membership_manual_form" cellpadding="5">
    <?php echo $membershipManualForm->renderHiddenFields();?>
    <input name="user_institution_id" id="user_institution_id" type="hidden" value="<?php print $eschool_name; ?>" />
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $membershipManualForm['purchase_user_field']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $membershipManualForm['purchase_user_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $membershipManualForm['purchase_user_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $membershipManualForm['amount_field']->renderLabel(); ?>
      </td>
      <td>
      	$<?php echo $membershipManualForm['amount_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $membershipManualForm['amount_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $membershipManualForm['trans_time']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $membershipManualForm['trans_time']->render(); ?>
      </td>
      <td>
      	<?php echo $membershipManualForm['trans_time']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $membershipManualForm['profile_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $membershipManualForm['profile_id']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspand="2" class="symfonyFormError">
      	<?php echo $membershipManualForm['profile_id']->renderError(); ?>
      </td>
    </tr>
     <tr>
      <td class="symfonyFormLabel">
      	<?php echo $membershipManualForm['bill_cycle']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $membershipManualForm['bill_cycle']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $membershipManualForm['bill_cycle']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Submit Manual Payment" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Cancel" onclick="window.location.href='<?php print $return_url; ?>'" />
      	<?php
	    if ($id = $membershipManualForm->getObject()->getId())
            {
    		$delete_url = $CFG->current_app->getUrl() . '/homeadmin/deleteManualPurchase?id=' . $membershipManualForm->getObject()->getId(); ?>
	      	<input class="button" type="button" value="Delete" onclick="return confirmClick('Are you sure you want to delete this transaction?', '<?php print $delete_url ?>')" />
		<?php
	    }?>
      </td>
    </tr>
  </table>
</form>