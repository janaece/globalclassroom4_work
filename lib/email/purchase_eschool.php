<?php 
// Template email for eschool purchase (immediate response to transaction). The credit card gets billed after 24 hours

print $this->params['header_image']; 	
?>
<p>
	Thank you for purchasing your <strong><em>Stratus</em></strong> Platform from Global Classroom!
</p>
<p>
	Other services we provide to empower your Platform include:
</p>
<ol>
	<li>Course development and instructional design</li>
	<li>Training for course developers</li>
	<li>Training for online teachers.</li>
</ol>
<ul>
</ul>
<p>
	If you have any questions, please don't hesitate to contact me.
</p>
<p>
	Sincerely,
</p>
<p> </p>
<?php print $this->params['contact']; ?>

