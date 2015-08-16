<form enctype="multipart/form-data" id="eschoolSettingsForm" name="eschoolSettingsForm" action="/homeadmin/doInstitutionSettings" method="POST">
  <table id="eschool_settings_form" cellpadding="5">
  	<?php echo $settingsForm->renderHiddenFields(); ?>
    <tr>
      <td colspan="3">
      	<h3>General Settings</h3>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['is_internal']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['is_internal']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['is_internal']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            This is a flag to set this platform as being owned and managed by
            GlobalClassroom.
        </td>
    </tr> 
    <tr class="external_eschool_field">
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['eschool_min_balance']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['eschool_min_balance']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['eschool_min_balance']->renderError(); ?>
      </td>
    </tr>
    <tr class="external_eschool_field">
        <td colspan="3" class="gcFieldDescription">
            When platform owners issue a request to withdraw earned funds via
            the accounting system, the maximum allowable withdrawal amount
            will equal their current account balance less this amount. Platform
            owners will not be able to leave less than this amount as their resulting
            account balance after a withdrawal.
        </td>
    </tr> 
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['force_membership']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['force_membership']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['force_membership']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            If this setting is turned on, all users must purchase a membership subscription
            before they are able to use the platform for any purpose. User accounts without
            a membership subscription belong to "No Institution" until they purchase their
            membership. 
        </td>
    </tr>
    <tr class="membership_field">
      <td colspan="3">
      	<h3>Membership Settings</h3>
      </td>
    </tr>
    <tr class="membership_field">
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['membership_includes_eclassroom']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_includes_eclassroom']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_includes_eclassroom']->renderError(); ?>
      </td>
    </tr>
    <tr class="membership_field">
        <td colspan="3" class="gcFieldDescription">
            If this setting is turned on, users will automatically be granted eClassroom
            owner access on the platform, and a new eClassroom will be created for them.
        </td>
    </tr>
    <tr class="membership_field">
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['membership_cost_month']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_cost_month']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_cost_month']->renderError(); ?>
      </td>
    </tr>
    <tr class="membership_field">
        <td colspan="3" class="gcFieldDescription">
            This is the price per month for a membership subscription on this platform. Both
            a monthly price and a yearly price may be set as payment plan options. Typically,
            the yearly price offers a discount per year over the monthly rate.
        </td>
    </tr>
    <tr class="membership_field">
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['membership_cost_year']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_cost_year']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_cost_year']->renderError(); ?>
      </td>
    </tr>
    <tr class="membership_field">
        <td colspan="3" class="gcFieldDescription">
            This is the price per year for a membership subscription on this platform. Both
            a monthly price and a yearly price may be set as payment plan options. Typically,
            the yearly price offers a discount per year over the monthly rate.
        </td>
    </tr>
    <tr class="membership_field">
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['membership_fee_percent']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_fee_percent']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_fee_percent']->renderError(); ?>
      </td>
    </tr>
    <tr class="membership_field">
        <td colspan="3" class="gcFieldDescription">
            This option allows GlobalClassroom to earn a percentage of all membership 
            subscription fees earned by an externally owned platform.
        </td>
    </tr>
    <tr class="membership_field">
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['membership_trial_length']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_trial_length']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['membership_trial_length']->renderError(); ?>
      </td>
    </tr>
    <tr class="membership_field">
        <td colspan="3" class="gcFieldDescription">
            This determines how many days will pass before a new recurring membership 
            subscription is charged for the first time. If this field is not set, the
            default is <?php print gcr::membershipTrialLengthInDays ?> day(s).
        </td>
    </tr>
    <tr>
      <td colspan="3">
      	<h3>eClassroom Settings</h3>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['eclassroom_min_balance']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['eclassroom_min_balance']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['eclassroom_min_balance']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            When eClassroom owners issue a request to withdraw earned funds via
            the accounting system, the maximum allowable withdrawal amount
            will equal their current account balance less this amount. eClassroom
            owners will not be able to leave less than this amount as their resulting
            account balance after a withdrawal.
        </td>
    </tr> 
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $settingsForm['eclassroom_create_institution']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['eclassroom_create_institution']->render(); ?>
      </td>
      <td>
      	<?php echo $settingsForm['eclassroom_create_institution']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            When this setting is turned on, eClassroom owners are provided with an institution
            and are assigned as an administrator on that institution. This allows them to create
            and manage new users as members of their institution.
        </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" class="button" value="Save Changes" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Exit" onclick="window.location.href='<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool'; ?>'" />
      </td>
    </tr>
  </table>
</form>