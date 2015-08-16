<?php
// This is the user account page.
// Ron Stewart 09.30.2010
global $CFG;
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/account_eschool.css";</style>
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
    $current_user = $CFG->current_app->getCurrentUser();
    if ($current_user->getRoleManager()->hasPrivilege('GCUser'))
    {
        include_partial('global/gcadminsidebar');
    }
    else if ($current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
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
    <small>
    <div id="accountOverview">
        <h1>
            <?php
            $mhr_user_obj = $user->getObject();
            $role_manager = $CFG->current_app->getCurrentUser()->getRoleManager();
            $eclassroom_user = $user->getRoleManager()->hasRole('EclassroomUser');
            $tooltip = $mhr_user_obj->username . ', email: ' . $mhr_user_obj->email;
            print '<span title="' . $tooltip . '"><a href="' .
                    $user->getHyperlinkToProfile() . '" target="_blank">' . $mhr_user_obj->firstname . ' ' .
                    $mhr_user_obj->lastname . ':</a></span>';
            if ($gc_admin || $owner)
            {
                print ' <a href="' . $CFG->current_app->getUrl() . '/account/view?eschool=' .
                    $app->getShortName() . '">' . $app->getFullName() . '</a>';
            }
            if ($eclassroom_user)
            {
                print ' eClassroom';
            }
            ?>
            Account
        </h1>
        <br />
        <?php
        $form_action_param_string = '';
        if ($gc_admin || $owner)
        {
            $form_action_param_string = '?eschool=' . $user->getApp()->getShortName() . '&user=' . $mhr_user_obj->id;
        }
        ?>
        <form id="userAccountForm" name="userAccountForm" action="<?php print $CFG->current_app->getUrl() . '/account/view' . $form_action_param_string; ?>" method="POST">
            <label for="startdate">From date </label>
            <input type="text" name="startdate" id="startdate" value="<?php print date('m/d/Y', $start_ts); ?>" class="datepicker" />
            <label for="enddate">to </label>
            <input type="text" name="enddate" id="enddate" value="<?php print date('m/d/Y', $end_ts); ?>" class="datepicker" />
            <input type="submit" class="smallButton button" value="View Transactions" />
        </form>
    </div>
    <div id="accountTotals">
        <table>
            <?php
            $total_gross = $account_table->getTotal('total_gross');
            $total_fees = $account_table->getTotal('total_fees');
            $total_seller = $account_table->getTotal('total_seller');
            $total_earnings = $account_table->getTotal('total_earnings');
            $record_count = $account_table->getTotal('record_count');
            if ($eclassroom_user)
            {
                $account_manager = $user->getAccountManager();
                $account_balance = $account_manager->getAccountBalance();

                if ($withdrawal = $account_manager->getPendingWithdrawal())
                {
                    $account_balance -= $withdrawal->getAmount();
                }
                ?>
                <tr>
                    <td>Total eClassroom Gross Income:&nbsp</td>
                    <td class="total"><?php print GcrPurchaseTable::gc_format_money($total_gross); ?></td>
                </tr>               
                <tr>
                    <td>Total Fees Paid from Income:&nbsp</td>
                    <td class="total"><?php print GcrPurchaseTable::gc_format_money($total_fees); ?></td>
                </tr>
                <tr>
                    <td>Total Net eClassroom Income:&nbsp</td>
                    <td class="total"><?php print GcrPurchaseTable::gc_format_money($total_seller); ?></td>
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
                }
            }
            else
            { ?>       
                <tr>
                    <td>Total of Purchases:&nbsp</td>
                    <td class="total"><?php print GcrPurchaseTable::gc_format_money($total_gross); ?></td>
                </tr>
            <?php 
            }?>
         </table>
    </div>
    <div style="clear:both">
        <?php
        print $record_count . ' Transaction';
        print ($record_count > 1 || $record_count == 0) ? 's' : '';
        if ($gc_admin)
        {?>
            <span style="float:right"><button class="adminOptionsButton smallButton" user_id="<?php print $user->getObject()->id; ?>" institution_id="<?php print $app->getShortName() ?>">Account Administration</button></span>
        <?php
        }
        else if ($user->getAccountManager()->canRequestWithdrawal())
        {
        ?>
        <span style="float:right">
            <a style="text-decoration:none" href="<?php print $CFG->current_app->getUrl() .
                '/account/withdrawal?eschool=' . $user->getApp()->getShortName() . '&user=' . $mhr_user_obj->id; ?>">
                <button class="smallButton">Request Withdrawal</button>
            </a>
        </span>
        <?php
        } ?>
    </div>
    </small>
    <?php
    if ($record_count > 0)
    {
        $account_table->printTable();
    }
    include_partial('editFeesForm');
    include_partial('adminOptionsForm');
    ?>
</div>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() {
    jQuery('#main-nav ul li a:contains(Users)').parent().addClass('selected');
});
</script>