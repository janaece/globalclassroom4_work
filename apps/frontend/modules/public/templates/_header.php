<div class="header">
		<?php print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/gc-new_banner.jpg",
							array(
								'id' => 'header_gc-logo',
                                                                'alt' => 'Global Classroom'
							)), "http://globalclassroom.us"); ?>
		<?php print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/login_button.jpg",
							array(
								'id' => 'header_login-btn',
                                                                'alt' => 'Global Classroom Login'
							)), "http://globalclassroom.us/login"); ?>
                <?php print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/contact-us_button.jpg",
							array(
								'id' => 'header_contact-btn',
                                                                'alt' => 'Contact Us'
							)), "http://globalclassroom.us/info/contact"); ?>
<!--    <div id="facebook-like">
        <iframe src="https://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fglobalclassroom.us&amp;
                layout=button_count&amp;show_faces=true&amp;width=475&amp;action=like&amp;
                font=verdana&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0"
                style="border:none; overflow:hidden; width:80px; height:25px; float:right;" allowTransparency="true"></iframe>
    </div>
-->
	<div id="menu">
		<span class="preload1"></span>
		<span class="preload2"></span>
		
		<div class="page">
		
		<ul id="nav">
			<li class="top"><a id="home" class="top_link"><span class="down">What is Global Classroom?</span></a>
				<ul class="sub">
					<!--<li><?php //print link_to("Home", "public/index"); ?></li>-->
                                        <li><?php print link_to("The Stratus Platform", "http://globalclassroom.us/solutions/strtus"); ?></li>
					<li><?php print link_to("eClassrooms", "http://globalclassroom.us/solutions/eclassroom"); ?></li>
                                        <li><?php print link_to("Our Team", "http://globalclassroom.us/info/team"); ?></li>
					<li><?php print link_to("About Us", "http://globalclassroom.us/info/about"); ?></li>
                                        <li><?php print link_to("Contact Us", "http://globalclassroom.us/info/contact"); ?></li>
                    <!--<li><?php //print link_to("What is eLearning?", "/"); ?></li>-->
				</ul>
			</li>
            <!--
			<li class="top"><a id="products" class="top_link"><span class="down">Join a Social Network</span></a>
				<ul class="sub">
					<li><?php //print link_to("Stratus", "public/stratus"); ?></li>
					<li><?php //print link_to("eClassrooms on Stratus", "public/eclassroomSolutions"); ?></li>
                    <li><?php //print link_to("Services", "public/servicesSolutions"); ?></li>
                    <li><?php //print link_to("Global Marketplace", "https://marketplace.globalclassroom.us", array('popup'=>'true')); ?></li>
                    <li><?php //print link_to("Act 48", "https://act48center.globalclassroom.us", array('popup'=>'true')); ?></li>
                    <li><?php //print link_to("NEISTE Virtual", "https://neistevirtual.globalclassroom.us", array('popup'=>'true')); ?></li>
                    <li><?php //print link_to("NY Virtual", "https://nyvirtual.globalclassroom.us", array('popup'=>'true')); ?></li>
				</ul>
			</li>
            -->
            <li class="top"><a id="solutions" class="top_link"><span class="down">Create your Social Network</span></a>
                <ul class="sub">
                    <!--<li><?php //print link_to("Marketplace", "public/marketplace"); ?></li>-->
                    <!--<li><?php //print link_to("Teacher Professional Development", "public/professionalDevelopment"); ?></li>-->
                    <li><?php print link_to("Private Social Network", "http://globalclassroom.us/solutions/strtus"); ?></li>
                    <li><?php print link_to("Business", "http://globalclassroom.us/solutions/business"); ?></li>
                    <li><?php print link_to("Membership Organizations", "http://globalclassroom.us/solutions/membership_organizations"); ?></li>
                    <li><?php print link_to("Government Agencies", "http://globalclassroom.us/solutions/government"); ?></li>
                    <li><?php print link_to("Non-Profits", "http://globalclassroom.us/solutions/nonprofit"); ?></li>
                    <li><?php print link_to("Education", "http://globalclassroom.us/solutions/education"); ?></li>
                    <!--
                    <li><?php //print link_to("Interest Groups", "public/interestGroupSolutions"); ?></li>
                    -->
                </ul>
            </li>
            <!--<li class="top"><a id="services" class="top_link"><span class="down">Services</span></a>
                <ul class="sub">
                    <li><?php //print link_to("Instructional Design", "public/services?id=instructional_design"); ?></li>
                    <li><?php //print link_to("Customized Training", "public/services?id=customized_training"); ?></li>
                    <li><?php //print link_to("eCommerce Support", "public/services?id=ecommerce_support"); ?></li>
                    <li><?php //print link_to("Branding Support", "public/services?id=branding"); ?></li>
                    <li><?php //print link_to("Full Platform Management", "public/services?id=full_platform_management"); ?></li>
                    <li><?php //print link_to("Marketing Your Platform", "public/services?id=marketing_your_platform"); ?></li>
                </ul>
            </li>-->
			<li class="top"><a id="partners" class="top_link"><span class="down">Clients</span></a>
				<ul class="sub">
					<!--<li><?php //print link_to("ACT 48 Center", "http://act48.com", array('popup' => true)); ?></li>-->
                    <li><?php print link_to("Business", "http://globalclassroom.us/clients/featured/business"); ?></li>
                    <li><?php print link_to("Membership Organizations", "http://globalclassroom.us/clients/featured/membership_organizations"); ?></li>
                    <li><?php print link_to("Government Agencies", "http://globalclassroom.us/clients/featured/government"); ?></li>
                    <li><?php print link_to("Non-Profits", "http://globalclassroom.us/clients/featured/nonprofit"); ?></li>
                    <li><?php print link_to("Education", "http://globalclassroom.us/clients/featured/education"); ?></li>
				</ul>
			</li>
			<li class="top"><a id="help" class="top_link"><span class="down">Help</span></a>
				<ul class="sub">
				<li><?php print link_to("Search", "http://marketplace.globalclassroom.us/search"); ?></li>
				<li><?php print link_to("Support", "http://support.globalclassroom.us", array('popup' => 'true')); ?></li>
                                <li><?php print link_to("Contact Us", "http://globalclassroom.us/info/contact"); ?></li>
				</ul>
			</li>
		</ul>
		</div>
	</div>
</div>