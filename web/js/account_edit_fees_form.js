jQuery("#edit-fees-dialog-form").dialog(
{
    autoOpen: false,
    height: 350,
    width: 350,
    modal: true,
    buttons:
    {
        'Save': function()
        {
            // 0 to 100 percentage regex
            var rege = /^(^(100(?:\.0{1,2})?))|(?!^0*$)(?!^0*\.0*$)^\d{1,2}(\.\d{1,2})?$|^0$/;
            if (rege.test(jQuery('#edit_fees_owner_fee').val()) && rege.test(jQuery('#edit_fees_gc_fee').val()) && rege.test(jQuery('#edit_fees_commission_fee').val()))
            {
                document.editFeesForm.submit();
                jQuery(this).dialog('close');
            }
        },
        Cancel: function()
        {
            jQuery(this).dialog('close');
        }
    }
});
jQuery('.editFeesButton').click(function()
{
    jQuery('#edit-fees-dialog-form').dialog('open');
    jQuery('#edit_fees_purchase_id').val(jQuery(this).attr('value'));
    jQuery('#edit_fees_gc_fee').val(jQuery(this).attr('gc_fee'));
    jQuery('#edit_fees_owner_fee').val(jQuery(this).attr('owner_fee'));
    jQuery('#edit_fees_commission_fee').val(jQuery(this).attr('commission_fee'))
});