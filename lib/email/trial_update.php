<?php 
// Template email for all recurring payment transactions.

print $this->params['header_image']; 	
?>
<p>
	Thank you for creating a trial <b><i>Stratus </i></b>Platform with Global Classroom!
</p>
<p>
	Your <?php print $this->params['trial_length']?>-day <b><i>Stratus</i></b> trial for <?php print $this->params['eschool_full_name']; ?>
	<b><u><?php print $this->params['time_left_text']?></u></b> We hope you have taken the time to explore and use your new 
	<b><i>Stratus</i></b> Platform. <?php print $this->params['directions_text']?>,
    <a href="mailto:studentservices@globalclassroom.us">please contact us today.</a>
</p>
<p>
    <br/>
    <strong>Questions?</strong>
    <br /><br />
	Call us at 866-535-3772 or email <a href="mailto:studentservices@globalclassroom.us"
	target="_blank">studentservices@globalclassroom.us</a>.
</p>
<p>
	<strong>Ready to purchase?</strong>
</p>
<ul>
    <!--<li><a href='<?php //print $this->params['default_eschool_url']; ?>/custom/frontend.php?url=/eschool/activation'>Activate your Stratus Platform online.</a></li>-->
	<li>Email a purchase order to
		<a href="mailto:studentservices@globalclassroom.us" target="_blank">
                    studentservices@globalclassroom.us
		</a>
	</li>
	<li>Fax a purchase order to 802-735-1019.</li>
	<li>Send a check to:</li>
</ul>
<p style="padding-left: 60px;">
	Global Classroom<br />
	125 College St<br />
	Burlington, VT 05401
</p>
<p> 
	Again, thanks for exploring Global Classroom! If you have any questions, please don't hesitate to contact me directly.
</p>
<p>
	Sincerely,
</p>
<p> </p>
<?php print $this->params['contact']; ?>