<?php
include_partial('recurringPurchaseForm',
        array('form' => $form, 'formErrors' => $formErrors, 'purchaseObject' => $purchaseObject, 'paypal_error' => $paypal_error))
?>
<script type="text/javascript">
    jQuery('#site-logo').hide();
</script>