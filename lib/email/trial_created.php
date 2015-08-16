<?php 
// Template email for expired trial notification to user.

print $this->params['header_image']; 
?>
<p>
	<br /> 
	Your <strong>Global Classroom Stratus Platform</strong> has been created!
	<br /><br /> 
	Your <?php print $this->params['trial_length']; ?>-day trial for 
	<a href="<?php print $this->params['eschool_url']; ?>"><?php print $this->params['eschool_full_name']; ?></a>
	begins today, and will end on <?php print $this->params['trial_end_date']; ?>.
    Now you can customize your home page, including the banner, activities and resources.
</p>
<p>
    <br/>
    <strong>Questions?</strong>
    <br /><br />
	Call us at 866-535-3772 or email <a href="mailto:eSchoolservices@globalclassroom.us"
	target="_blank">eSchoolservices@globalclassroom.us</a>.
</p>
<p>
	<strong>Ready to purchase?</strong>
</p>
<ul>
    <!--<li><a href='<?php //print $this->params['default_eschool_url']; ?>/custom/frontend.php?url=/eschool/activation'>Activate your Stratus Platform online.</a></li>-->
	<li>Email a purchase order to
		<a href="mailto:eSchoolservices@globalclassroom.us" target="_blank">
			eSchoolservices@globalclassroom.us
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
	If you have any questions, please don't hesitate to contact me directly.
</p>
<p> </p>
<p>Sincerely,</p>
<p> </p>
<?php print $this->params['contact']; ?>