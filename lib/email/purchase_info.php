<?php 
// Template email for purchase info for Global Classroom.

print $this->params['header_image'];
?>
<h3>Successful Paypal Transaction</h3>
<p>
	Purchase ID#: <?php print $this->params['purchase_id']; ?>
	<br />
	Time: <?php print $this->params['time']; ?>
	<br />
	User Info: <?php print $this->params['user_info']; ?>
	<br />
	Catalog Info: <?php print $this->params['eschool_info']; ?>
	<br />
	<?php if (!empty($this->params['product_info']))
	{?>
	Product Info: <?php print $this->params['product_info'];
	}?>
</p>
<hr>
<p>
	Sales Info: <?php print $this->params['sales_info']; ?>
	<br />
        <?php if (!empty($this->params['cc_address']))
	{?>
	Billing Address: <?php print $this->params['cc_address'];
	}?>
</p>