<style type="text/css">
    .symfonyFormLabel {font-weight:bold}
    .gcFieldDescription {padding-bottom: 15px}
    div.gc_small_form {width: 70%}
</style>
<script type="text/javascript">
jQuery(function() 
{
    
    function displayMembershipFields()
    {
        jQuery('#force_membership').is(':checked') ? jQuery('.membership_field').show() : jQuery('.membership_field').hide();
    }
    function displayExternalEschoolFields()
    {
        jQuery('#is_internal').is(':checked') ? jQuery('.external_eschool_field').hide() : jQuery('.external_eschool_field').show();
    }
    jQuery('#is_internal').change(function()
    {
        displayExternalEschoolFields();
    });
    jQuery('#force_membership').change(function()
    {
        displayMembershipFields();
    });
    displayExternalEschoolFields();
    displayMembershipFields();
});
</script>
<div class="gc_small_form">
    <table class="content" cellpadding="5" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Platform Settings: <a href="<?php print $institution->getUrl() ?>"><?php print $institution->getFullName() ?></a></h1> 
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <?php include_partial('settingsForm', array('settingsForm' => $settingsForm)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
        <?php ($message) ? print '<tr><td colspan="2" style="color:red; font-weight:bold">' . $message . '</td></tr>' : print '';?>
    </table>
</div>