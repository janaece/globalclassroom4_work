<div id="eschoolactivation" class="gc_small_form">
    <?php global $CFG; ?>
    <h1>Purchase Your eSchool</h1>
    <div>GlobalClassroom currently offers monthly and yearly payment schedules for eSchools.
    You may cancel this subscription at any time by contacting
    <a target="contact info" href="" onclick="this.target='contact'; return openpopup('/custom/contact.php', 'contact', 'menubar=0,location=0,scrollbars,status,resizable,width=300,height=250', 0);">eClassroom Support</a>.
    <br /><br />Please choose which payment schedule will be applied to your eClassroom subscription:</div>
    <br />
    <form name="eschoolActivation" id="eschoolActivation" method="POST" action="<?php echo $CFG->current_app->getUrl() . '/purchase/eschoolPurchase'; ?>">
        <input type="radio" name="bill_cycle" id="monthly_bill_cycle" value="Month" checked="checked" /> $ <?php print number_format($monthly_cost, 2) ?> per month <br />
        <input type="radio" name="bill_cycle" id="yearly_bill_cycle" value="Year" /> $ <?php print number_format($yearly_cost, 2) ?> per year &nbsp( Savings of $ <?php print number_format(($monthly_cost * 12) - $yearly_cost, 2) ?> )<br /><br />
        <input type="hidden" name="token" id="token" value="<?php print GcrEschoolTable::generateRandomString(); ?>" />
        <input type="submit" class="button" value="Purchase eSchool" />
    </form>
</div>
