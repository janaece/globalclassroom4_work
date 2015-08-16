<?php global $CFG; ?>
<form enctype="multipart/form-data" id="courseManualForm" name="saleManualForm" action="/homeadmin/doManualSale" method="POST">
  <table id="course_manual_form" cellpadding="5">
    <?php echo $saleManualForm->renderHiddenFields();?>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $saleManualForm['purchase_user_field']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $saleManualForm['purchase_user_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $saleManualForm['purchase_user_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $saleManualForm['purchase_type_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $saleManualForm['purchase_type_id']->render(); ?>
      </td>
    </tr>
    <tr colspan="2" class="symfonyFormError">
      <td>
      	<?php echo $saleManualForm['purchase_type_id']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $saleManualForm['amount_field']->renderLabel(); ?>
      </td>
      <td>
      	$<?php echo $saleManualForm['amount_field']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="symfonyFormError">
      	<?php echo $saleManualForm['amount_field']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $saleManualForm['trans_time']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $saleManualForm['trans_time']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $saleManualForm['trans_time']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $saleManualForm['profile_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $saleManualForm['profile_id']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspand="2" class="symfonyFormError">
      	<?php echo $saleManualForm['profile_id']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Submit Manual Payment" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Cancel" onclick="window.location.href='<?php print $return_url; ?>'" />
      	<?php 
	    if ($id = $saleManualForm->getObject()->getId())
            {
    		$delete_url = $CFG->current_app->getUrl() . '/homeadmin/deleteManualPurchase?id=' . $saleManualForm->getObject()->getId(); ?>
	      	<input class="button" type="button" value="Delete" onclick="return confirmClick('Are you sure you want to delete this transaction?', '<?php print $delete_url ?>')" />
		<?php 
	    }?>
      </td>
    </tr>
  </table>
</form>