<?php
$institution = $app->getInstitution();
$contact = $app->getPersonObject();
$base_cost = $course->getCost();
?>
<br />
<div id="saleInfo" style="margin:auto;max-width:50%">
    <div id="banner-container">
        <?php print image_tag($institution->getAppUrl() . 'local/printBanner.php'); ?>
    </div>
    <br />
    <h1>Non-member payment process</h1>

<p>
    If you are not a member of <?php print $institution->getFullName(); ?>, You need to pay a total of 
    $<?php print number_format($item->getAmount() + $base_cost, 2) ?> to enroll. Please button below to pay the
    remaining $<?php print number_format($item->getAmount(), 2) ?> by credit card. If you have any questions, please
    contact <?php print $contact->getFullName() . ' at ' . $contact->getPhone1() . ', or ' . $contact->getEmail(); ?>. 
</p>
<form name="sale" id="sale" method="POST" action="<?php echo $institution->getUrl() . '/purchase/salePurchase'; ?>">
    <input type="hidden" name="purchase_item" id="nonmember" value="<?php print $item->getShortName(); ?>" />
    <input type="hidden" name="token" id="token" value="<?php print GcrEschoolTable::generateRandomString(); ?>" />
    <input type="submit" class="button" value="Continue" />
</form>
</div>
<br />