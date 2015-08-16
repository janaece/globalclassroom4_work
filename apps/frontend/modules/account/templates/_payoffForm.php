<?php global $CFG; ?>
<form enctype="multipart/form-data" id="payoffForm" name="payoffForm" action="/account/createWithdrawal" method="POST">
  <table id="payoff_form" cellpadding="5">
  	<?php echo $payoffForm->renderHiddenFields(); ?>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffForm['amount']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffForm['amount']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffForm['amount']->renderError(); ?>
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
      <td colspan="2">
      	<input type="submit" class="button" value="Request Withdrawal" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Update Information" onclick="window.location.href='<?php print $CFG->current_app->getUrl() .
      		'/account/paymentInfo?eschool=' . $credentials->getUserEschoolId() . '&user=' . $credentials->getUserId(); ?>'" />
      	<input type="button" class="button" value="Cancel" onclick="window.location.href='<?php print $CFG->current_app->getUrl() .
      		'/account/view'; ?>'" />
      </td>
    </tr>
  </table>
</form>