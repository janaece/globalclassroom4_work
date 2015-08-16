<div class="gc_small_form"> 
    <table class="content" cellpadding="5" >
        <tr>
            <td colspan="2">
                <p>
                    <h1>Payment Information Profile</h1>
                    Please supply the financial information we need in order to process withdrawals
                    from your account. Currently, Global Classroom Inc. only processes payments through
                    PayPal. If you do not have an account with PayPal, please <a href="http://www.paypal.com">
                    Sign Up for a PayPal Account</a> so we may process payments to your PayPal account.
                </p>
                <p>
                    We also require either:
                    <ol>
                        <li>Your registered business name with a TIN (United States Tax ID Number)</li>
                        <li>Your full legal name with a SSN (Social Security Number)</li>
                    </ol>
                </p>
            </td>
        </tr>
        <tr>
            <td style="float:right">
                    <?php include_partial('payoffCredentialsForm', array('payoffCredentialsForm' => $payoff_credentials_form, 'return_url' => $return_url)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>