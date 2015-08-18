<p>
	Thank you for subscribing on
	<a href="<?php print Doctrine::getTable('GcrInstitution')->findOneByShortName($this->params['institution_short_name'])->getAppUrl(); ?>"><?php print $this->params['institution_full_name']; ?></a>.
	You will be billed <?php print $this->params['purchaseAmount']; ?> on a recurring <?php print $this->params['cycle_text']; ?> basis.
</p>

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
