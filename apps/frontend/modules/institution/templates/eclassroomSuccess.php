<?php
global $CFG;
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/account_eschool.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/js/lib.js"></script>

<script type="text/javascript" defer="defer">

jQuery(document).ready(function() {
// call the tablesorter plugin 
    jQuery("#gc_eclassroom_user_table").tablesorter({sortList: [[0,0]], widgets: ['zebra']});
});
</script>
<div id="left-column" class="sidebar">
<?php
    if ($CFG->current_app->hasPrivilege('GCUser'))
    {
        include_partial('global/gcadminsidebar');
    }
    else if ($CFG->current_app->hasPrivilege('EschoolAdmin'))
    {
        include_partial('global/eschooladminsidebar');
    }
    else
    {
        include_partial('global/usersidebar');
    }
?>
</div>
<div id="transaction-history" class="main-column">
    <div id="accountOverview">
        <small>
        <h1>
            eClassroom - <?php print $user->getFullnameString(); ?>
        </h1>   
            
        </small>
    </div>
    <div id="accountTotals">
        <br />
        <table>
            <tr>
                <td>Total Number of Enrolments:&nbsp</td>
                <td class="total"><?php print $eclassroom_table->getTotal('total_enrolments'); ?></td>
            </tr>
         </table>
    </div>
    <br /><br />
    <div style="clear:both">
        <small>
            <?php
            $record_count = $eclassroom_table->getTotal('record_count'); 
            print $record_count . ' Course'; 
            print ($record_count != 1) ? 's' : '';
            ?>
             <span style="float:right">
                 <?php print '<a href="' . $CFG->current_app->getInstitution()->getAppUrl() . 'artefact/courses/create.php' . '"><button>Create a New Course</button></a>'; ?>
             </span> 
        </small>
    </div>
    <?php
    if ($record_count > 0)
    {
        $eclassroom_table->printTable();
    } ?>
</div>