<?php 
global $CFG, $FULLME;
?>
<div style="display:none" id="edit-fees-dialog-form" title="Edit Purchase Fees">
    <p class="validateTips">Select the new owner and gc fees for this purchase item.</p>

    <form id="editFeesForm" name="editFeesForm" action="<?php print $CFG->current_app->getUrl() . '/purchase/editFees'?>" method="POST">
    <fieldset>
        <input id="edit_fees_return_url" name="edit_fees_return_url" type="hidden" value="<?php print $FULLME; ?>" />
        <input id="edit_fees_purchase_id" name="edit_fees_purchase_id" type="hidden" value="" />
        <label for="edit_fees_owner_fee">Owner Fee %: </label>
        <input type="text" name="edit_fees_owner_fee" id="edit_fees_owner_fee" value="" />
        <label for="edit_fees_gc_fee">GC Fee %: </label>
        <input type="text" name="edit_fees_gc_fee" id="edit_fees_gc_fee" value="" />
        <label for="edit_fees_commission_fee">Commission Fee %: </label>
        <input type="text" name="edit_fees_commission_fee" id="edit_fees_commission_fee" value="" />
    </fieldset>
    </form>
</div>
