jQuery(document).ready(function() {
// call the tablesorter plugin 
    jQuery("#gc_dashboard").tablesorter({sortList: [[4,0]], widgets: ['zebra']});
});
jQuery(function() {
	
	jQuery("input.datepicker").datepicker();
	
	
	jQuery("#edit-dialog-form").dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: 
		{
			'Save': function() 
			{
				// mm/dd/yyyy or null regex
				var rege = /^(0[1-9]|1[012]|[1-9])\/(0[1-9]|[12][0-9]|3[01]|[1-9])\/(19|20)\d\d$|^$/;
				if (rege.test(jQuery('#startdatepicker').val()) && rege.test(jQuery('#enddatepicker').val()))
				{
					if (jQuery('#startdatepicker').val() != '')
					{
						document.editTrialForm.submit();
						jQuery(this).dialog('close');
					}
				}
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
	
	jQuery("#create-dialog-form").dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: 
		{
			'Save': function() 
			{
				// mm/dd/yyyy regex
				var rege = /^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d$/;
				if (rege.test(jQuery('#newstartdatepicker').val()))
				{
					document.createTrialForm.submit();
					jQuery(this).dialog('close');
				}
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
		
	});
	
	jQuery('.editTrialButton')
		.button()
		.click(function() {
			jQuery('#edit-dialog-form').dialog('open');
			jQuery('#trial_id').val(jQuery(this).attr('value'));
			var start_date = new Date(jQuery(this).attr('start_date') * 1000);
			jQuery('#startdatepicker').val((start_date.getMonth() + 1)+"/"+start_date.getDate()+"/"+start_date.getFullYear());
			var unix_end_date = jQuery(this).attr('end_date');
			if (unix_end_date > 0)
			{
				var end_date = new Date(unix_end_date * 1000);
				jQuery('#enddatepicker').val((end_date.getMonth() + 1)+"/"+end_date.getDate()+"/"+end_date.getFullYear());
			}
		});
	
	jQuery('#createTrialButton')
	.button()
	.click(function() {
		jQuery('#create-dialog-form').dialog('open');
	});
        
        jQuery('#returnButton').button();
	
	jQuery('.deleteTrialButton')
	.button()
	.click(function() {
		jQuery('#del_trial_id').val(jQuery(this).attr('value'));
		document.deleteTrialForm.submit();
	});
});