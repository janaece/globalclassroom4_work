<?php
global $CFG;
if($CFG->current_app->isMoodle())
{
    global $PAGE;
    $PAGE->set_url($CFG->httpswwwroot . '/eschool/edit');
}
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/homeadmin_eschool.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/js/lib.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
// call the tablesorter plugin 
    jQuery("#gc_payoffs").tablesorter({widgets: ['zebra']});
});
</script>
<table id="gc_payoffs" class="tablesorter" cellspacing="1">
    <thead>
        <tr>
            <th>Request Date</th>
            <th>Recipient</th>
            <th>User</th>
            <th>eSchool</th>
            <th>Status</th>
            <th>Payment Date</th>
            <th>Payoff Type</th>
            <th>Account Bal.</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        define('NULL_DATE', 2100000000);
        foreach($payoffs as $payoff)
        {
            if ($purchase = $payoff->getPurchase())
            {
                $payment_date = date('m/d/Y', $purchase->getTransTime());
                $amount = $purchase->getAmount();
            }
            else
            {
                $payment_date = date('m/d/Y', NULL_DATE);
                $payment_date_class = ' class="nullDateField"';
                $amount = $payoff->getAmount();
            }
            $institution = $payoff->getInstitution();
            $user = $payoff->getUser();
            $credentials = $payoff->getCredentials();
            $account_balance = $user->getAccountManager()->getAccountBalance();

            $mhr_user_obj = $user->getObject();
            $user_tooltip = $mhr_user_obj->username . ', email: ' . $mhr_user_obj->email;
            $user_text = '<span title="' . $user_tooltip . '"><a href="' . $CFG->current_app->getUrl() .
            '/account/view?eschool=' . $user->getApp()->getShortName() . '&user=' . $mhr_user_obj->id .
            '&startdate=' . $start_date . '&enddate=' . $end_date . '" target="_blank">' . $mhr_user_obj->firstname .
            ' ' . $mhr_user_obj->lastname . '</a></span>';
        ?>
            <tr>
                <td><?php print date('m/d/Y', $payoff->getTransTime()); ?></td>
                <td>
                    <a href="<?php print $CFG->current_app->getUrl() . '/account/paymentInfo?eschool=' .
                            $institution->getShortName() . '&user=' . $mhr_user_obj->id . '&id=' . $credentials->getId(); ?>">
                        <?php print $payoff->getRecipientString(); ?>
                    </a>
                </td>
                <td><?php print $user_text; ?></td>
                <td><a href="<?php print $CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName(); ?>">
                        <?php print $institution->getFullName(); ?>
                    </a>
                </td>
                <td><?php print ucfirst($payoff->getPayoffStatus()); ?></td>
                <td><span<?php print $payment_date_class; ?>><?php print $payment_date; ?></span></td>
                <td><?php print $payoff->getPayoffTypeString();?></td>
                <td><?php print GcrPurchaseTable::gc_format_money($account_balance); ?></td>
                <td><?php print GcrPurchaseTable::gc_format_money($amount); ?></td>
                <?php
                if ($payoff->isPending())
                { ?>
                    <td>
                        <form action="<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/approvePayoff?id=' . $payoff->getId(); ?>" method="POST">
                            <button type="submit" class="approvePayoffButton form_button button">Approve</button>
                        </form>
                    </td>
                <?php
                }
                else
                {
                    print '<td> </td>';
                }
                ?>
                <td>
                    <input type="button" class="form_button button" value="Delete" onclick="return confirmClick('Are you sure you want to delete this transaction?', '<?php print GcrEschoolTable::getHome()->getUrl() .
                            '/homeadmin/deletePayoff?id=' . $payoff->getId(); ?>')" />
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>