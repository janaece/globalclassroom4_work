<?php
// This is the eschool account page.
// Ron Stewart 09.29.2010
use_stylesheet('tablesorter/style.css');
use_stylesheet('//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css');
use_stylesheet('account_eschool.css');

global $CFG;
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() {
// call the tablesorter plugin 
    jQuery("#gc_account").tablesorter({sortList: [[0,1]], widgets: ['zebra']});
    jQuery("input.datepicker").datepicker();
    jQuery.getScript("/js/account_edit_fees_form.js");
    jQuery.getScript("/js/account_admin_options_form.js");
    jQuery.getScript("/js/eschool/rightnav.js");
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
    
?>
</div>
<div id="transaction-history" class="main-column">
    <small>
    <div id="accountOverview"> 
        <?php
        if ($gc_admin)
        {?>
            <h2>GlobalClassroom Accounting Administration</h2>
            Platform:
            <select id="platformAccountSelector" style="max-width: 400px">

                <script type="text/javascript">
                jQuery('#platformAccountSelector').change(function () 
                {
                    document.location.href = '<?php print $CFG->current_app->getUrl(); ?>/account/view?eschool=' + jQuery(this).val();
                });
                </script>
                <?php

                $institutions = GcrInstitutionTable::getInstitutions();
                foreach ($institutions as $institution)
                { 
                    $sn = $institution->getShortName();
                    print '<option';
                    if ($sn == $app->getShortName())
                    {
                        print ' selected=selected';
                    } 
                    print ' value="'. $sn . '">' . $institution->getFullName() . ' (' . $sn . ')</option>';
                } ?>
            </select>
            <br />
        <?php
        } 
        else
        { ?>
            <h1><?php
            $tooltip = ' title="' . $app->getFullName() . ' (' . $app->getShortName() . ')"';
            print '<span' . $tooltip . '>' . $app->getFullName() . ': </span>';
            ?>
            Platform Transaction History
            </h1>
        <?php
        } ?>
        <br />
        <form id="eschoolAccountForm" name="eschoolAccountForm" action="<?php print $CFG->current_app->getUrl() . '/account/view?eschool=' . $app->getShortName(); ?>" method="POST">
            <label for="startdate">From date </label>
            <input type="text" name="startdate" id="startdate" value="<?php print date('m/d/Y', $start_ts); ?>" class="datepicker" />
            <label for="enddate">to </label>
            <input type="text" name="enddate" id="enddate" value="<?php print date('m/d/Y', $end_ts); ?>" class="datepicker" />
            <input class="smallButton button" type="submit" value="View Transactions" />
        </form>
    </div>
    <div id="accountTotals">
        <table>
            <tr>
                <td>Total Platform Gross Income:&nbsp</td>
                <td class="total">
                    <?php
                    $total_gross = $account_table->getTotal('total_gross');
                    if(!empty($total_gross))
                    {
                        print GcrPurchaseTable::gc_format_money($total_gross);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Total Fees:&nbsp</td>
                <td class="total"><?php print GcrPurchaseTable::gc_format_money($account_table->getTotal('total_fees')); ?></td>
            </tr>
            <?php
            $account_manager = $app->getAccountManager();
            $account_balance = $account_manager->getAccountBalance();
            if ($withdrawal = $account_manager->getPendingWithdrawal())
            {
                $account_balance -= $withdrawal->getAmount();
            } 
            if ($account_table->hasEclassroom())
            {?>
                <tr>
                    <td>Total Seller Earnings:&nbsp</td>
                    <td class="total"><?php print GcrPurchaseTable::gc_format_money($account_table->getTotal('total_seller')); ?></td>
                </tr>
            <?php
            }?>
            <tr>
                <td>Total Platform Net Income:&nbsp</td>
                <td class="total">
                    <?php
                    $total_earnings = $account_table->getTotal('total_earnings');
                    if(!empty($total_earnings))
                    {
                        print GcrPurchaseTable::gc_format_money($total_earnings);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Available Balance:&nbsp</td>
                <td class="total"><?php print GcrPurchaseTable::gc_format_money($account_balance); ?></td>
            </tr>
            <?php
            if ($withdrawal)
            { ?>
                <tr>
                    <td>Pending Withdrawal:&nbsp</td>
                    <td class="total"><?php print GcrPurchaseTable::gc_format_money($withdrawal->getAmount()); ?></td>
                </tr>
            <?php
            } ?>
         </table>
    </div>
    <div style="clear:both">
    <?php print $account_table->getTotal('record_count'); ?> Transactions
    <?php
    if ($gc_admin)
    {?>
        <span style="float:right"><button class="adminOptionsButton smallButton" user_id="<?php print $user->getObject()->id; ?>" institution_id="<?php print $app->getShortName() ?>">Account Administration</button></span>
    <?php
    }
    else if ($account_manager->canRequestWithdrawal())
    { ?>
    <span style="float:right"><input type="button" class="smallButton button" value="Request Withdrawal" onclick="window.location.href='<?php print $app->getUrl() .
        '/account/withdrawal'; ?>'" /></span>
    <?php
    } ?>
    </div>
    </small>
<?php
if ($account_table->getTotal('record_count') > 0)
{
   $account_table->printTable();
}
include_partial('editFeesForm');
include_partial('adminOptionsForm');
?>
</div>

<script type="text/javascript">
    jQuery('#main-nav ul li a:contains(Configure Site)').parent().addClass('selected');
</script>