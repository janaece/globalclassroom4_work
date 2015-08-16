<?php 
// Template email for eclassroom purchase (immediate response to transaction). 
// The credit card gets billed after the trial period is over	
if ($this->params['trial_length'] > 0)
{ ?>
    <p>
        Thank you for creating your <?php print $this->params['trial_length']; ?>-day trial <strong><em>eClassroom</em></strong> on
        <a href="<?php print Doctrine::getTable('GcrInstitution')->findOneByShortName($this->params['institution_short_name'])->getAppUrl(); ?>"><?php print $this->params['institution_full_name']; ?></a>.
        Your trial period ends on <?php print $this->params['trial_end_date']; ?>. If you do not cancel your eClassroom before that date,
        you will be billed <?php print $this->params['purchaseAmount']; ?> on a recurring <?php print $this->params['cycle_text']; ?> basis.
    </p>
<?php
}
else
{ ?>
    <p>
        Thank you for purchasing your eClassroom on
        <a href="<?php print Doctrine::getTable('GcrInstitution')->findOneByShortName($this->params['institution_short_name'])->getAppUrl(); ?>"><?php print $this->params['institution_full_name']; ?></a>.
        You will be billed <?php print $this->params['purchaseAmount']; ?> on a recurring <?php print $this->params['cycle_text']; ?> basis.
    </p>

<?php
} ?>

<p>
    If you have any questions, please don't hesitate to contact me.
</p>
<p> </p>
<p>Sincerely,</p>
<p> </p>
<?php print $this->params['contact']; ?>
<p> </p>
<p> </p>
<br />
<?php print $this->params['powered_by_GC']; ?>
