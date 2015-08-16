<style>
    .symfonyFormLabel {font-weight:bold}
    .gcFieldDescription {padding-bottom: 15px}
    .symfonyFormError {font-weight:bold;color:red}
    div.gc_small_form {width: 70%}
</style>
<script type="text/javascript">
jQuery(document).ready(function()
{
    jQuery('#eschoolSettingsSubmit').click(function()
    {
       var gc_course_fee = jQuery('#course_gc_fee').val();
       var owner_course_fee = jQuery('#course_owner_fee').val();
       var total_fees = Number(gc_course_fee) + Number(owner_course_fee);
       if (total_fees > 100)
       {
           alert('The total percentage course fees (platform owner + GlobalClassroom) exceeds 100%');
           jQuery('.courseFeeError').html('* Total Fees Exceed 100%');
           return false;
       }
    });
});
</script>    
<form enctype="multipart/form-data" id="eschoolSettingsForm" name="eschoolSettingsForm" action="/homeadmin/doEschoolSettings" method="POST">
  <table id="eschool_settings_form" cellpadding="5">
  	<?php echo $eschoolSettingsForm->renderHiddenFields(); ?>
    <tr>
      <td colspan="3">  	
      	<h3>General Settings</h3>
      </td>
    </tr>
    <tr class="externalEschoolField">
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['course_gc_fee']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['course_gc_fee']->render(); ?>
      </td>
      <td class="symfonyFormError courseFeeError">
      	<?php echo $eschoolSettingsForm['course_gc_fee']->renderError(); ?>
      </td>
    </tr>
    <tr class="externalEschoolField">
        <td colspan="3" class="gcFieldDescription">
            This option allows GlobalClassroom to earn a percentage of all course sales
            earned by an externally owned platform. This percentage is calculated
            from the total course sale amount after any commission fees have been
            taken out. If the course being sold is an eClassroom course, the eClassroom
            owner will receive whatever percentage remains after this percentage and the
            platform owner course fee percentage is subtracted. The sum of this percentage along
            with the platform owner course fee must not exceed %100.
        </td>
    </tr> 
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['is_public']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['is_public']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['is_public']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            Turning this option on allows external platform owners to add this catalog as
            as remote catalog on their platform. Otherwise, only GlobalClassroom administrators
            may add this catalog as a remote catalog.
        </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['is_visible']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['is_visible']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['is_visible']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            This setting should be turned off for catalogs which should not be referenced
            outside of their native platform. If a comprehensive GlobalClassroom Marketplace should ever
            be created, this setting would determine whether its courses would appear there.
        </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
          <?php echo $eschoolSettingsForm['gc_auto_creates_users']->renderLabel(); ?>
      </td>
      <td>
          <?php echo $eschoolSettingsForm['gc_auto_creates_users']->render(); ?>
      </td>
      <td>
          <?php echo $eschoolSettingsForm['gc_auto_creates_users']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            Turning on this setting automatically creates new user records on this 
            catalog when users on connected platforms view the courses tab. If this setting
            is turned off, users will need to be manually added via "migrate users" in 
            order to access this catalog.
        </td>
    </tr>
    <tr>
      <td colspan="3">  	
      	<h3>eClassroom Settings</h3>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['classroom_trial_length']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_trial_length']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_trial_length']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            This determines how many days will pass before a new recurring eClassroom 
            subscription is charged for the first time. If this field is not set, the
            default is <?php print gcr::classroomTrialLengthInDays ?> day(s).
        </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['classroom_cost_month']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_cost_month']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_cost_month']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            This is the price per month for an eClassroom subscription on this catalog.
            If this setting (and/or a yearly price) is turned on, users will be able to purchase eClassrooms
            on this catalog via credit card through eCommerce. Otherwise, eClassrooms
            may only be created by administrators manually.
        </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['classroom_cost_year']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_cost_year']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_cost_year']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            This is the price per year for an eClassroom subscription on this catalog.
            If this setting (and/or a monthly price) is turned on, users will be able to purchase eClassrooms
            on this catalog via credit card through eCommerce. Otherwise, eClassrooms
            may only be created by administrators manually.
        </td>
    </tr>
    <tr class="externalEschoolField">
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['classroom_gc_fee']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_gc_fee']->render(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['classroom_gc_fee']->renderError(); ?>
      </td>
    </tr>
    <tr class="externalEschoolField">
        <td colspan="3" class="gcFieldDescription">
            This option allows GlobalClassroom to earn a percentage of eClassroom subscription
            fees earned by an externally owned platform. This option does not affect the distribution
            of course sales, it only shares eClassroom subscription fees. 
        </td>
    </tr>    
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $eschoolSettingsForm['course_owner_fee']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolSettingsForm['course_owner_fee']->render(); ?>
      </td>
      <td class="symfonyFormError courseFeeError">
      	<?php echo $eschoolSettingsForm['course_owner_fee']->renderError(); ?>
      </td>
    </tr>
    <tr>
        <td colspan="3" class="gcFieldDescription">
            This setting allows the owner of this platform to earn a percentage of all eClassroom course
            sales. The remaining percentage amount is earned by the eClassroom owner. 
        </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input id="eschoolSettingsSubmit" type="submit" class="button" value="Save Changes" style="margin-top: 15px;" />
      	<input type="button" class="button" value="Exit" onclick="window.location.href='<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool'; ?>'" />
      </td>
    </tr>
  </table>
</form>