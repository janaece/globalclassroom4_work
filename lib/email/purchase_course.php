<div>
	Thank you for purchasing a course from <b><i><?php print $this->params['eschool_full_name']; ?>!</i></b>
	<br /><br />
	<table cellspacing='10px'>
		<tr>
			<td>Your credit card:</td>
			<td><?php print $this->params['creditCardString']; ?></td>
		</tr>
		<tr>
			<td>was charged:</td>
			<td>$<?php print $this->params['amountCharged']; ?></td>
		</tr>
		<tr>
			<td>on date:</td>
			<td><?php print date('M d, Y'); ?></td>
		</tr>
		<tr>
			<td>for course:</td>
			<td><?php print $this->params['course']; ?></td>
		</tr>
		<tr>
			<td>from eSchool:</td>
			<td><a href="<?php print Doctrine::getTable('GcrEschool')->findOneByShortName($this->params['eschool_short_name'])->getAppUrl(); ?>"><?php print $this->params['eschool_full_name']; ?></a></td>
		</tr>
	</table>
	<br />
	We greatly appreciate the chance to serve your online educational needs! 
	If you have any questions about this billing, please contact your <b><i>Stratus</i></b> Platform
	Administrator, or contact <b><i>eSchool Services</b></i> at 866-535-3772 or
	eschoolservices@globalclassroom.us
	<br /><br />
	Thank You!
	<br /><br />
	<p>
		<?php print $this->params['powered_by_GC']; ?>
	</p>
</div>