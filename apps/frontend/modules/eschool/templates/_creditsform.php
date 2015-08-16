<div id="saleCreditsInfo">
    <div id="snhu-image-container">
        <?php print image_tag('https://s3.amazonaws.com/static.globalclassroom.us/images/snhu.gif'); ?>
    </div>
    <br />
    <h1>Purchase Professional Development Graduate Credits from Southern New Hampshire University</h1>
</div>
<div id="creditsoptions" class="gc_small_form">	
<?php 
global $CFG;

$item1 = Doctrine::getTable('GcrPurchaseItem')->find('credits_1');
$item2 = Doctrine::getTable('GcrPurchaseItem')->find('credits_2');
$item3 = Doctrine::getTable('GcrPurchaseItem')->find('credits_3');
?>
    <h2>Get Graduate Credits for your <?php print $CFG->current_app->getFullName(); ?> course</h2>
    <p>
        Choose the number of Graduate Credits associated with your course:
    </p>
    <form name="sale" id="sale" method="POST" action="<?php echo $CFG->current_app->getUrl() . '/purchase/salePurchase'; ?>">
        <input type="radio" name="purchase_item" id="credits1" value="<?php print $item1->getShortName(); ?>" checked="checked" />
                1 Credit Course ($<?php print number_format($item1->getAmount(), 2) ?> fee)<br />
        <input type="radio" name="purchase_item" id="credits2" value="<?php print $item2->getShortName(); ?>" />
                2 Credit Course ($<?php print number_format($item2->getAmount(), 2) ?> fee)<br />
        <input type="radio" name="purchase_item" id="credits3" value="<?php print $item3->getShortName(); ?>" />
                3 Credit Course ($<?php print number_format($item3->getAmount(), 2) ?> fee)<br /><br />
        <input type="hidden" name="token" id="token" value="<?php print GcrEschoolTable::generateRandomString(); ?>" />
        NOTE: Selecting the wrong number of credits could result in a delay of the issuing of your transcript.<br/><br/>
        Contact <?php print mail_to("support@globalclassroom.us", "support@globalclassroom.us", array('encode' => 'true', 'class' => 'email_link')); ?>
        with any questions.<br/><br/>
        <input type="submit" class="button" value="Continue" />
    </form>
</div>