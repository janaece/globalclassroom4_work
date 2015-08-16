<?php
if (!$params['pending'])
{ ?>
<div id="receiptcontainer">
    <h1>Payment Successful</h1>
    <h3>
        <p>Thank you for your purchase!</p>
        <p>Please print a copy of your receipt shown below for your records.</p>
    </h3>
    <?php $receipt->printReceipt(); ?>
    <h3><a href="<?php print $receipt->getProductUrl() . $category; ?>"><?php print $receipt->getProductUrlDescription(); ?></a></h3>
</div>
<?php
}
else
{ ?>
<div id="receiptcontainer">
    <h1>Your Payment is Being Processed</h1>
    <h3>
        <p>Thank you for your purchase!</p>
        <p>Your payment is currently being processed. 
           Once the payment has been confirmed, you will receive a message in your 
           <a href="<?php print $params['user']->getApp()->getAppUrl(); ?>account/activity">Inbox</a> 
           to notify you have been enrolled in the course listed below. We apologize for this delay. If you do not receive a confirmation message,
           please contact <a href="http://globalclasssroom.us/info/contact">customer services</a> for further assistance.
        <p>Please print a copy of your receipt shown below for your records.</p>
    </h3>
    <?php $receipt->printReceipt(); ?>
</div>
<script type="text/javascript">
jQuery(function ()
{
    function timedRefresh(timeoutPeriod) 
    {
        setTimeout("location.reload(true);", timeoutPeriod);
    }
    timedRefresh(5000);
});  
</script>
<?php
} ?>