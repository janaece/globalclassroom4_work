jQuery(document).ready(function() {
// call the tablesorter plugin 
    jQuery("#gc_commission").tablesorter({widgets: ['zebra']});
});
jQuery(function() 
{
    jQuery("#edit-dialog-form").dialog(
    {
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        buttons: 
        {
            'Save': function()
            {
                // 0 to 100 percentage regex
                var rege = /^(^(100(?:\.0{1,2})?))|(?!^0*$)(?!^0*\.0*$)^\d{1,2}(\.\d{1,2})?$|^0$/;
                if (rege.test(jQuery('#edit_commission_rate').val()) && rege.test(jQuery('#edit_commission_rate').val()))
                {
                    document.editCommissionForm.submit();
                    jQuery(this).dialog('close');
                }
            },
            Cancel: function()
            {
                jQuery(this).dialog('close');
            }
        }
    });

    jQuery("#create-dialog-form").dialog(
    {
        autoOpen: false,
        height: 450,
        width: 350,
        modal: true,
        buttons: 
        {
            'Save': function() 
            {
                // 0 to 100 percentage regex
                var rege = /^(^(100(?:\.0{1,2})?))|(?!^0*$)(?!^0*\.0*$)^\d{1,2}(\.\d{1,2})?$|^0$/;
                if (rege.test(jQuery('#commission_rate').val()) && rege.test(jQuery('#commission_rate').val()))
                {
                    document.createCommissionForm.submit();
                    jQuery(this).dialog('close');
                }
            },
            Cancel: function() 
            {
                    jQuery(this).dialog('close');
            }
        }

    });

    jQuery('.editCommissionButton').button().click(function() 
    {
        jQuery('#edit-dialog-form').dialog('open');
        jQuery('#commission_id').val(jQuery(this).attr('value'));
        jQuery('#edit_commission_rate').val(jQuery(this).attr('commission_rate'));
    });

    jQuery('#createCommissionButton').button().click(function() 
    {
        jQuery('#create-dialog-form').dialog('open');
    });
    
    jQuery('#returnButton').button();

    jQuery('.deleteCommissionButton').button().click(function() 
    {
        jQuery('#del_commission_id').val(jQuery(this).attr('value'));
        document.deleteCommissionForm.submit();
    });
});