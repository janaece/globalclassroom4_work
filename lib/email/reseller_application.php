<?php 
// Template email for reseller applications

print $this->params['header_image'];
?>
<h3>Reseller Applicant Info</h3>
<p>
	Name: <?php print $this->params['first-name'] . ' ' . $this->params['last-name']; ?>
	<br />
	Email: <?php print $this->params['email']; ?>
	<br />
	Phone: <?php print $this->params['phone']; ?>
	<br />
	<?php if (!empty($this->params['legal-name']))
	{?>
	Legal Name: <?php print $this->params['legal-name'];
	print "<br />";
	}
	if (!empty($this->params['position']))
	{?>
	Position: <?php print $this->params['position'];
	print "<br />";
	}
	if (!empty($this->params['length']))
	{?>
	Length: <?php print $this->params['length'];
	print "<br />";
	} ?>
	Type: <?php print $this->params['type']; ?>
	<br />
	Address 1: <?php print $this->params['address1']; ?>
	<br />
	<?php if (!empty($this->params['address2']))
	{?>
	Address 2: <?php print $this->params['address2'];
	print "<br />";
	}?>
	City: <?php print $this->params['city']; ?>
	<br />
	State: <?php print $this->params['state']; ?>
	<br />
	Zipcode: <?php print $this->params['zipcode']; ?>
	<br />
	Country: <?php print $this->params['country']; ?>
	<br />
	<?php if(!empty($this->params['url']))
	{?>
	URL: <?php print $this->params['url'];
	print "<br />";
	}?>
</p>