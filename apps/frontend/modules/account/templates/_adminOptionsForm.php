<?php 
global $FULLME;
?>
<div style="display:none" id="admin-options-dialog-form" title="Account Administration">
    <form id="adminOptionsForm" name="adminOptionsForm" action="<?php print GcrEschooltable::getHome()->getUrl() . '/homeadmin/adminOptions'?>" method="POST">
    <fieldset>
        <input id="admin_options_return_url" name="admin_options_return_url" type="hidden" value="<?php print $FULLME; ?>" />
        <input id="admin_options_institution_id" name="admin_options_institution_id" type="hidden" value="" />
        <input id="admin_options_user_id" name="admin_options_user_id" type="hidden" value="" />
        <label for="adminAction">Select an Action: </label>
        <select id="admin_option" name="admin_option" style="width:90%">
            <option value="manual_payoff">Manual Withdrawal</option>
            <option value="manual_course">Manual Course Purchase</option>
            <option value="manual_classroom">Manual eClassroom Purchase</option>
            <option value="manual_eschool">Manual Platform Purchase</option>
            <option value="manual_membership">Manual Membership Purchase</option>
            <option value="manual_sale">Manual Purchase (Miscellaneous)</option>
            <option value="set_chained_payments">Set Chained Payments</option>
            <option value="set_payment_info">Set Payment Credentials</option>
        </select>
    </fieldset>
    </form>
</div>
