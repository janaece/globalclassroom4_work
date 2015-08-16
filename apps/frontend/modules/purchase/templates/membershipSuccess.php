<div id="membershippaymentoptions" class="gc_small_form">
    <?php
    global $CFG;
    $institution = $CFG->current_app;
    $trial_length = $institution->getMembershipTrialLength();
    if ($trial_length > 0)
    {
        ?>
        <h2>Set Up Your Free <?php print $trial_length; ?>-Day Trial Membership!</h2>
        <p>
            In order to start your free <?php print $trial_length; ?>-day trial membership, <?php print $institution->getFullName(); ?> requires a valid credit card.
            After the <?php print $trial_length; ?>-day trial period is completed, you will be charged on either a monthly or yearly basis.
            You may cancel this subscription at any time by contacting
            <a target="contact info" href="" onclick="this.target='contact'; return openpopup('/custom/contact.php', 'contact', 'menubar=0,location=0,scrollbars,status,resizable,width=300,height=250', 0);">Member Support</a>.
            <b>If you cancel your membership before the end of the <?php print $trial_length; ?>-day trial period, your credit card will not be charged.</b>
            <br /><br />Please choose which payment schedule will be applied to your membership subscription:
        </p>
    <?php
    }
    else
    { ?>
        <h2>Purchase Your Membership!</h2>
        <p>
            To purchase your membership on <?php print $institution->getFullName(); ?> please choose one of the available payment schedules.
            You may cancel this subscription at any time by contacting
            <a target="contact info" href="" onclick="this.target='contact'; return openpopup('/custom/contact.php', 'contact', 'menubar=0,location=0,scrollbars,status,resizable,width=300,height=250', 0);">Member Support</a>.
            <br /><br />Please choose which payment schedule will be applied to your membership subscription:
        </p>
    <?php
    } ?>
    <br />
    <form name="membership" id="membership" method="POST" action="<?php echo $CFG->current_app->getUrl() . '/purchase/membershipPurchase'; ?>">
        <?php
        if ($monthly_cost)
        {
            print '<input type="radio" name="bill_cycle" id="monthly_bill_cycle" value="Month" checked="checked" /> $ ' .
                    number_format($monthly_cost, 2) . ' per month <br />';
        }
        if ($yearly_cost)
        {
            print '<input type="radio" name="bill_cycle" id="yearly_bill_cycle" value="Year" checked="checked" /> $ ' .
                    number_format($yearly_cost, 2) . ' per year';
            if ($monthly_cost)
            {
                print ' &nbsp( Savings of $ ' . number_format(($monthly_cost * 12) - $yearly_cost, 2) . ' )<br />';
            }
        }
        ?>
        <br />
        <input type="submit" class="button" value="Continue" />
    </form>
</div>
<script type="text/javascript">
    jQuery('#site-logo').hide();
</script>