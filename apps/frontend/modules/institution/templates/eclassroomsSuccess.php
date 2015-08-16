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
    jQuery("#gc_eclassroom_management").tablesorter({sortList: [[0,0]], widgets: ['zebra']});
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
            eClassroom Administration
        </h1>   
            
        </small>
    </div>
    <div id="accountTotals">
        <br />
        <table>
            <tr>
                <td>Total Number of Courses:&nbsp</td>
                <td class="total"><?php print $eclassroom_table->getTotal('total_courses'); ?></td>
            </tr>
            <tr>
                <td>Total Sales:&nbsp</td>
                <td class="total"><?php print GcrPurchaseTable::gc_format_money($eclassroom_table->getTotal('total_sales')); ?></td>
            </tr>
         </table>
    </div>
    <br /><br />
    <div style="clear:both">
        <small>
            <?php
            $record_count = $eclassroom_table->getTotal('record_count'); 
            print $record_count . ' eClassroom'; 
            print ($record_count != 1) ? 's' : '';
            if ($CFG->current_app->hasPrivilege('GCUser'))
            { ?>
             <span style="float:right">
                 <?php print '<a href="' . $CFG->current_app->getInstitution()->getAppUrl() . 'artefact/eschooladmin/eclassroom.php' . '"><button>Create a New eClassroom</button></a>'; ?>
             </span> 
            <?php
            } ?>
        </small>
    </div>
    <?php
    if ($record_count > 0)
    {
        $eclassroom_table->printTable();
    } ?>
</div>