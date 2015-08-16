<?php 
// Template email for expired trial notification to user.

print $this->params['header_image'];
?>
<p>
	Thank you for exploring a <strong><em>Stratus</em></strong>Platform with Global Classroom!
	<strong><span style="text-decoration: underline;">Unfortunately,</span></strong> your
    <?php print $this->params['trial_length']; ?>-day <strong><em>free trial</em></strong> for
    <?php print $this->params['eschool_full_name']; ?> <strong><span style="text-decoration: underline;">has expired!</span></strong>
</p>
<p>
    <strong>If you would like to extend your trial,</strong> please contact me.
</p>
<ul>
	<li style="margin-top: 0.5em; margin-bottom: 0.5em;"><strong>If you would like to purchase your Stratus Platform</strong>, <a href="<?php print $this->params['default_eschool_url'] ?>/custom/frontend.php?url=/eschool/activation" target="_blank"><u>click here</u></a>.</li>
	<li style="margin-top: 0.5em; margin-bottom: 0.5em;">There are other solutions that you might want to consider:<br />
		<br />
		<ul>
			<li style="margin-top: 0.5em; margin-bottom: 0.5em;"><em>Global<strong>K12</strong></em> allows a single K12 teacher to teach up to five courses for $5/month (or $50/year -- two months free!). <a href="http://globalclassroom.us/solutions/eclassroom/globalk12" target="_blank"><u>Learn more</u></a>.</li>
			<li style="margin-top: 0.5em; margin-bottom: 0.5em;"><em>Global<strong>Expert</strong></em> allows a single instructor or subject expert to teach up to five courses for $25/month (or $250/year -- two months free!). <a href="http://globalclassroom.us/solutions/eclassroom/globalexpert" target="_blank"><u>Learn more</u></a>.</li>
		</ul>
	</li>
</ul>
<p>
	It is important that we hear from you, before your Stratus Platform is deleted from our system.  Contact me today.  I look forward to helping you.
</p>
<p>
	Sincerely,
</p>
<p> 
</p>
<?php print $this->params['contact']; ?>