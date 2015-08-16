<?php
global $CFG;
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/account_eschool.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
// call the tablesorter plugin
    jQuery("#gc_user_storage_table").tablesorter({sortList: [[0,1]], widgets: ['zebra']});
    jQuery("#gc_user_storage_folder").change( function() 
    {
        document.location.href = '<?php print $CFG->current_app->getUrl() . '/institution/viewUserStorage'; ?>?folder=' + jQuery("#gc_user_storage_folder").val();
    });
    jQuery('.delete_s3_file').click( function()
    {
        var file_id = jQuery(this).attr('file_key');
        document.location.href = '<?php print $CFG->current_app->getUrl() . '/institution/deleteUserStorageFile'; ?>?file=' + file_id + '&key=<?php print $user_storage->getKey(); ?>';
    });
    jQuery('.gc_move_file_form_new_file_id').change( function()
    {
        var index = jQuery(this).attr('gc_move_file_form_index');
        var form_name = 'document.gc_move_file_form_' + index; 
        eval(form_name + '.submit();');
    });
    
    jQuery('#gc_upload_s3_submit').click(function () 
    {
        if (jQuery('#gc_upload_s3_file').val() != '')
        {
            var html = '<h3 style="margin:10px">Uploading, one moment please...</h3>';
            jQuery.colorbox({html: html, fixed: true});
        }
        else
        {
            alert('Please select a file to upload');
            return false;
        }
    });
});
</script>
<div id="left-column" class="sidebar">
<?php
    if ($gc_admin && !$owner)
    {
        include_partial('global/eschooladminsidebar');
    }
    else if ($gc_admin)
    {
        include_partial('global/gcadminsidebar');
    }
    else
    {
        include_partial('global/usersidebar');
    }
?>
</div>
<div id="transaction-history" class="main-column">
    <small>
    <div id="headerOptions">
        <h1>
            Remote File Storage
        </h1>
        <br />
        <?php
        print 'Select Folder: <select id="gc_user_storage_folder" name="gc_user_storage_folder">';
        foreach ($folder_list as $key => $value)
        {
            print '<option value="' . $key . '"';
            if ($key == $current_folder)
            {
                print ' selected="selected"'; 
            }
            print '>' . $value . '</option>';
        }
        print '</select>';
        if ($user_storage->allowsUpload())
        { 
            $html_parameters = $user_storage->getUploadHtml();
        ?>            
            <form style="float:right" action="https://<?php print $html_parameters['form']['action']; ?>"
                method="<?php print $html_parameters['form']['method']; ?>"
                enctype="<?php print $html_parameters['form']['enctype']; ?>">
                Upload File: 
                <?php 
                foreach ($html_parameters['inputs'] as $name => $value)
                {
                    print '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
                } ?>
                <input id="gc_upload_s3_file" class="button" type="file" name="file" />
                <input id="gc_upload_s3_submit" class="button" type="submit" name="upload" value="Upload" />
            </form>
        <?php
        } ?>
    </div>
    <?php
    $file_count = count($file_list);
    ?>
    </small>
    <div style="clear:both">
        <small>
        <?php
        print $file_count . ' File';
        print ($file_count != 1) ? 's' : '';
        ?>
        </small>
    </div>
    
    <?php
    if ($file_count > 0)
    {
        $user_storage_table->printTable();
    }
    ?>
</div>