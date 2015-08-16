jQuery("#admin-options-dialog-form").dialog(
{
    autoOpen: false,
    height: 250,
    width: 300,
    modal: true,
    buttons:
    {
        'OK': function()
        {   
            document.adminOptionsForm.submit();
            jQuery(this).dialog('close');
        },
        Cancel: function()
        {
            jQuery(this).dialog('close');
        }
    }
});
jQuery('.adminOptionsButton').click(function()
{
    jQuery('#admin-options-dialog-form').dialog('open');
    jQuery('#admin_options_user_id').val(jQuery(this).attr('user_id'));
    jQuery('#admin_options_institution_id').val(jQuery(this).attr('institution_id'));
});