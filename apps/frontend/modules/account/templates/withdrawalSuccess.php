<div class="gc_small_form"> 
    <?php
    global $CFG;
    
    // Build a return URL to the Accounting Page
    $eschool_short_name = false;
    $user_id = false;
    if (isset($_GET['eschool']))
    {
        $eschool_short_name = $_GET['eschool'];
    }
    if (isset($_GET['user']))
    {
        $user_id = $_GET['user'];
    }
    $char = '?';
    if ($eschool_short_name)
    {
        $return_url = $char . 'eschool=' . $eschool_short_name;
        $char = '&';
    }
    if ($user_id)
    {
        $return_url .= $char . 'user=' . $user_id;
    }

    ?>
    <table class="content" cellpadding="5" >
        <tr>
            <td colspan="2">
                <div>
                    <h1><?php echo $institution->getFullName(); ?> Account Withdrawal</h1>
                    <p>
                        Please enter the withdrawal amount you would like to request. Payments will normally be
                        issued to the PayPal account email listed below within 3-5 business days. If any of the
                        information listed below is incorrect, please click the "Update Information" button to
                        correct it before requesting a withdrawal.
                    </p>
                    <p>
                        Based on your available account balance of <?php print GcrPurchaseTable::gc_format_money($account_balance); ?>,
                        your maximum withdrawal amount is currently <?php print GcrPurchaseTable::gc_format_money($max_withdrawal); ?>.
                        This reflects a required minimum balance of <?php print GcrPurchaseTable::gc_format_money($min_balance); ?>.
                    </p>
                </div>
            </td>
        </tr>
        <tr>
            <td style="float:right">
                <?php include_partial('payoffForm', array('payoffForm' => $payoffForm, 'credentials' => $credentials, 'url' => $return_url)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>