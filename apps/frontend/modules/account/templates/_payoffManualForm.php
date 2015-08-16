<?php global $CFG; ?>
<form enctype="multipart/form-data" id="payoffManualForm" name="payoffManualForm" action="/account/doManualPayoff" method="POST">
  <table id="epayoff_manual_form" cellpadding="5">
  	 <input name="payoff_id" id="payoff_id" type="hidden" value="<?php print $payoff_id; ?>" />
    <?php echo $payoffManualForm->renderHiddenFields();?>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['amount']->renderLabel(); ?>
      </td>
      <td>
      	$<?php echo $payoffManualForm['amount']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['amount']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['type']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['type']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['type']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['transtime']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['transtime']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['transtime']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['reference_id']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['reference_id']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['reference_id']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	Name:
      </td>
      <td>
      	<?php print $credentials->getUserFirstName() . ' ' . $credentials->getUserLastName(); ?>
      </td>
      <td> </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	Business:
      </td>
      <td>
      	<?php print $credentials->getUserBusinessName() ?>
      </td>
      <td> </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	Paypal Account Email:
      </td>
      <td>
      	<?php print $credentials->getUserPaypalEmail() ?>
      </td>
      <td> </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	TIN/SSN:
      </td>
      <td>
      	<?php print $credentials->getUserTin() ?>
      </td>
      <td> </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['street1']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['street1']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['street1']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['street2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['street2']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['street2']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['city']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['city']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['city']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['zipcode']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['zipcode']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['zipcode']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['state']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['state']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['state']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['country']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['country']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['country']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffManualForm['description']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['description']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffManualForm['description']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<?php 
      	$submit_text = 'Request Withdrawal';
      	if ($payoff_id)
      	{
      		$submit_text = 'Save Changes';
      	}
      	?>
      	<input type="submit" class="button" value="<?php print $submit_text; ?>" style="margin-top: 15px;" />
      	<?php 
      	if (!$payoff_id)
      	{?>
	      	<a style="text-decoration:none" href="<?php print $CFG->current_app->getUrl() . '/account/paymentInfo?eschool=' .
	      		$credentials->getUserEschoolId() . '&user=' . $credentials->getUserId(); ?>">
	      		<input type="button" value="Update Information"></input>
	      	</a>
	    <?php 
      	} ?>
      	<a style="text-decoration:none" href="<?php print $CFG->current_app->getUrl() . '/account/view?eschool=' .
      		$credentials->getUserEschoolId() . '&user=' . $credentials->getUserId();; ?>">
      		<input type="button" value="Cancel"></input>
      	</a>
      </td>
    </tr>
  </table>
</form>