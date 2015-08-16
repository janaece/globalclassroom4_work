<?php 
// Template email for all recurring payment transactions.

print $this->params['header_image']; 	
?>
<br /> 
	Thank you for your recent purchase of <?php print $this->params['cycle_text1']; ?> <strong><em><?php print $this->params['type_text1']; ?></em></strong> subscription from Global Classroom. We greatly appreciate the chance to serve your online educational needs!
	<br /><br />
</p>
	<table cellspacing="10px">
		<tbody>
			<tr>
				<td>Your credit card:</td>
				<td><?php print $this->params['creditCardString']; ?></td>
			</tr>
			<tr>
				<td>was charged:</td>
				<td><?php print $this->params['amountCharged']; ?></td>
			</tr>
			<tr>
				<td>on date:</td>
				<td><?php print date('M d, Y'); ?></td>
			</tr>
			<tr>
				<td><?php print $this->params['type_text2']; ?></td>
				<td><a href="<?php print Doctrine::getTable('GcrEschool')->findOneByShortName($this->params['eschool_short_name'])->getAppUrl(); ?>"><?php print $this->params['eschool_full_name']; ?></a></td>
			</tr>
		</tbody>
	</table>
<p>
	Since you have chosen <?php print $this->params['cycle_text1']; ?> subscription, <?php print $this->params['cycle_text2']; ?>.* If you wish to change credit cards, you must notify us at  least 15 days in advance!
	<br /><br />
	If you have any questions about this billing, or wish to notify us of a payment change, please contact <strong><em>eSchool Services</em></strong> at 866-535-3772 or <a href="mailto:eschoolservices@globalclassroom.us" target="_blank">eschoolservices@globalclassroom.us</a>
	<br /><br />
	Sincerely,
</p>
<p> </p>
<?php print $this->params['contact']; ?>
<p>
	<small>*Global Classroom reserves the right to charge for subscriptions at the current published (<a href='http://globalclassroom.us'>http://globalclassroom.us</a>) subscription rate.</small>
</p>