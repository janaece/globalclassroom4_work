<div id="classroompaymentoptions" class="gc_small_form">	
    <?php
    global $CFG;
    $trial_length = $eschool->getClassroomTrialLength();
    if ($trial_length > 0)
    {
        ?>
        <h2>Set Up Your Free <?php print $trial_length; ?>-Day Trial eClassroom!</h2>
        <p>
            In order to start your free <?php print $trial_length; ?>-day trial eClassroom, <?php print $eschool->getFullName(); ?> requires a valid credit card.
            After the <?php print $trial_length; ?>-day trial period is completed, you will be charged on either a monthly or yearly basis.
            You may cancel this subscription at any time by contacting
            <a target="contact info" href="mailto:support@globalclassroom.us">eClassroom Support</a>.
            <b>If you cancel your eClassroom before the end of the <?php print $trial_length; ?>-day trial period, your credit card will not be charged.</b>
            <br /><br />Please choose which payment schedule will be applied to your eClassroom subscription:
        </p>
    <?php
    }
    else
    { ?>
        <h2>Purchase Your eClassroom!</h2>
        <p>
            To purchase your eClassroom on <?php print $eschool->getFullName(); ?> please choose one of the available payment schedules.
            You may cancel this subscription at any time by contacting
            <a target="contact info" href="mailto:support@globalclassroom.us">eClassroom Support</a>.
            <br /><br />Please choose which payment schedule will be applied to your eClassroom subscription:
        </p>
    <?php
    } ?>
    <br />
    <form name="eClassroom" id="eClassroom" method="POST" action="<?php echo $CFG->current_app->getUrl() . '/purchase/classroomPurchase'; ?>">
        <input type="hidden" name="eschool_id" id="eschool_id" value="<?php print $eschool->getShortName(); ?>" />
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
                print '  ( Savings of $ ' . number_format(($monthly_cost * 12) - $yearly_cost, 2) . ')<br />';
            }
        }
        ?>
        <input type="submit" class="button" value="Continue" />
    </form>
</div>