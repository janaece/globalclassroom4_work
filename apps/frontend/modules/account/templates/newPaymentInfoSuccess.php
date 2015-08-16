<div class="gc_small_form">
    <h3>Payment Information Profile Verification</h3>
    <?php
    global $CFG;
    if ($credentials->getVerifyStatus() == 'verified')
    { ?>
        <p>
            Thank you!
        </p>
        <p>
            Your payment information has been verified, and may now be used to make a withdrawal.
        </p>
    <?php
    }
    else
    { ?>
        <p>
            Your payment information has been saved, but still needs to be verified.
        </p>
        <p>
            An email has been sent to <b><?php print $user->getObject()->email; ?></b>, entitled
            "Verification of Global Classroom Payment Information". To verify your new payment information,
            click the verification hyperlink in the email which was sent to you.
        </p>
        <p>
            Once your new payment information has been verified, you may make a withdrawal.
        </p>
    
    
        
    <?php
    } ?>
    <p>
        <a href="<?php print $CFG->current_app->getUrl() . '/account/view?eschool=' .
                        $user->getApp()->getShortName() . '&user=' . $user->getObject()->id; ?>">
            Go To My Account History
        </a>
    </p>
</div>