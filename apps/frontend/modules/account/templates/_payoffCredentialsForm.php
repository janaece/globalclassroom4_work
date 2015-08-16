<?php global $CFG; ?>
<form enctype="multipart/form-data" id="payoffCredentialsForm" name="payoffCredentialsForm" action="/account/createPaymentInfo" method="POST">
  <table id="payoff_credentials_form" cellpadding="5">
  	<?php echo $payoffCredentialsForm->renderHiddenFields(); ?>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffCredentialsForm['user_first_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_first_name']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_first_name']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffCredentialsForm['user_last_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_last_name']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_last_name']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffCredentialsForm['user_business_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_business_name']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_business_name']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffCredentialsForm['user_paypal_email']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_paypal_email']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_paypal_email']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $payoffCredentialsForm['user_tin']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_tin']->render(); ?>
      </td>
      <td>
      	<?php echo $payoffCredentialsForm['user_tin']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Save New Payment Information" style="margin-top: 15px;" />
      	<a href="<?php print $return_url; ?>">
      		<input type="button" class="button" value="Cancel"></input>
      	</a>
      </td>
    </tr>
  </table>
</form>