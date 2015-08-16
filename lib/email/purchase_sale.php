<?php
// Template email for eschool purchase (immediate response to transaction). The credit card gets billed after 24 hours

print $this->params['header_image'];
?>
<p>
	Thank you for purchasing your Graduate Credits from Global Classroom!
</p>
	<br /><br />
	<table cellspacing='10px'>
		<tr>
			<td>Description:</td>
			<td><?php print $this->params['sale_description']; ?></td>
		</tr>
		<tr>
			<td>your credit card:</td>
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
	</table>
<p>
	If you have any questions, please don't hesitate to contact me.
</p>
<p>
	Sincerely,
</p>
<p> </p>
<?php print $this->params['contact']; ?>