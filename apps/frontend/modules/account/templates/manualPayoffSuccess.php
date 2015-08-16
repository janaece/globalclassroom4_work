<div class="gc_small_form" style="width:50%"> 
    <table class="content" cellpadding="10" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Manual Account Withdrawal</h1>
                    <p>
                        Please enter the total amount withdrawn, the check number or paypal transaction ID, and address information if applicable.
                    </p>
                    <p>
                        <?php print $user->getFullNameString(); ?> has an account balance of <?php print GcrPurchaseTable::gc_format_money($account_balance); ?>,
                        with a maximum withdrawal amount of <?php print GcrPurchaseTable::gc_format_money($max_withdrawal); ?>.
                        This reflects a required minimum balance of <?php print GcrPurchaseTable::gc_format_money($min_balance); ?>.
                    </p>
                    <p><b>Submitting this form WILL NOT trigger an automatic payment via PayPal.</b></p>
                    <p> If the funds still need to be transferred, either do:
                    <ol>
                        <li>Make a standard withdrawal request for the user and then approve it.</li>
                        <li>Use PayPal's Virtual Terminal to send payment, and then save the transaction details here.</li>
                        <li>Send a check by mail and save the payment details here.</li>
                    </ol>
                    </p>
                </div>
            </td>
        </tr>
        <tr>
            <td style="float:right">
                <?php include_partial('payoffManualForm', array('payoffManualForm' => $payoff_form,
                                                                'user' => $user,
                                                                'credentials' => $credentials,
                                                                'payoff_id' => $payoff_id)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>