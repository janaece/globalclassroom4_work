<?php 
// Template email for verifying new payoff credentials. 
?>
<p>
    Your payment information on <?php print $this->params['institution']->getFullName(); ?> has been successfully changed.
</p>
<p>
    To verify your new payment information, please click the link below. Funds will not be sent to you until your new
    information has been verified via this email link.
</p>
<p>
    <a href="<?php print $this->params['institution']->getUrl() . '/account/newPaymentInfo?id=' .
        $this->params['credentials']->getId() . '&verify=' . $this->params['credentials']->getVerifyHash(); ?>">
        <?php print $this->params['institution']->getUrl() . '/account/newPaymentInfo?id=' .
        $this->params['credentials']->getId() . '&verify=' . $this->params['credentials']->getVerifyHash(); ?>
    </a>
</p>
<p> </p>
<br />
<?php print $this->params['powered_by_GC']; ?>
