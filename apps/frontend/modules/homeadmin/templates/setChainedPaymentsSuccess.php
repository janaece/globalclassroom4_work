<?php
global $CFG;
?>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() 
{
    jQuery("#chained_payments_user_id").change(function ()
    {
        if (jQuery('#chained_payments_user_id option:selected').attr('uses_chained_payments') == 'true')
        {
            jQuery('#use_chained_payments').attr('checked', 'checked');
        }
        else
        {
            jQuery('#use_chained_payments').removeAttr('checked');
        }
    });
});
</script>
<div class="gc_small_form" style="width:50%"> 
    <table class="content" cellpadding="10" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Enable/Disable Paypal Chained Payments</h1>
                    Please select the user account, and set checkbox for enabling chained payments.
                </div>
                <br />
            </td>
        </tr>
        <tr>
            <td style="float:right">
                <form id="setChainedPayments" name="setChainedPayments" action="<?php print $CFG->current_app->getUrl(); ?>/homeadmin/doSetChainedPayments" method="POST">
                    <input id="chained_payments_institution_id" name="chained_payments_institution_id" type="hidden" value="<?php print $institution->getShortName(); ?>" />
                    <select id="chained_payments_user_id" name="chained_payments_user_id">
                        <?php
                        foreach($userArray as $key => $value)
                        {
                            print '<option value="' . $key . '" uses_chained_payments="' . 
                                    $uses_chained_payments_array[$key] . '"';
                            if ($key == $default_user_id)
                            {
                                print ' selected=selected';
                            }     
                            print '>' . $value . '</option>'; 
                        } ?>
                    </select><br /><br />
                    <label for="use_chained_payments">Paypal Chained Payments Enabled:</label>
                    <input id="use_chained_payments" name="use_chained_payments" type="checkbox" <?php ($uses_chained_payments_array[$default_user_id] == 'true') ? print ' checked="checked"' : print ''; ?>></input><br />
                    <?php ($message) ? print '<span style="color:red; font-weight:bold">' . $message . '</span>' : print '';?><br />
                    <input type="submit" class="button" value="Save Changes" />
                    <input type="button" class="button" value="Exit" onclick="window.location.href='<?php print $CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName() . '&user=' . $default_user_id; ?>'" />
                </form>    
            
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>
