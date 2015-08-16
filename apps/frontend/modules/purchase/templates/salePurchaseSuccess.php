<table id="purchase">
    <tr>
        <td id="banner">
            <div>
                Please complete the form below and click the submit button at the<br/>
                bottom to complete your transaction.
            </div>
        </td>
        <td>
            <img src="https://s3.amazonaws.com/static.globalclassroom.us/marketing/Stratus/poweredby_blk-trans.png" alt="powered by globalclassroom" />
        </td>
    </tr>
    <tr>
        <td>
            <div style="padding-left: 20px;">
                <p>This is a secure web site that respects your privacy. We use SSL (encryption and certificates) for your protection.</p>
                <br />
                <h1>Having Trouble?</h1>
                We'll help you with your problem.<br/>
                Email <a href="mailto:support@globalclassroom.us">student services</a> or call
                <br/>to speak with a live person: 866-535-3772
            </div>
        </td>
        <td id="form">
            <h1><?php print $purchaseObject->description ?></h1>

            <h2><?php print 'Cost: $' . number_format($purchaseObject->cost, 2); ?></h2>
            <?php include_partial('form', array('form' => $form, 'formErrors' => $formErrors)) ?>
            </td>
    </tr>
    <tr>
        <td id="footer" colspan="2">
            All transactions made via:<br/>
            <img src="https://s3.amazonaws.com/static.globalclassroom.us/images/paypal.cards.jpg" />
        </td>
    </tr>
</table>