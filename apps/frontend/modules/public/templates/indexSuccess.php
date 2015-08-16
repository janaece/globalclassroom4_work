<?php use_stylesheet('nivo-slider.css') ?>
<?php use_stylesheet('custom-nivo-slider.css') ?>
<?php use_javascript('jquery.nivo.slider.pack.js') ?>
<!--[if lte IE 7]>
		<div>
			We've detected that you're running an old browser.<br/>
			For full functionality and display layout, please upgrade to a newer browser.<br/>
			<a href="/public/requirements">Click here</a> to view our system requirements page, there you can find all the information you need.
		</div>
<![endif]-->
<div>
	<div id="slider">
		<?php print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/nivoslider/homepage-scrollingimgs_stratus2.jpg",
                array('alt' => 'Stratus - Social Media')), "public/stratus"); ?>
        <?php print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/nivoslider/homepage-scrollingimgs_stratus4.jpg",
                array('alt' => 'Stratus - Cloud Computing')), "public/stratus"); ?>
        <?php print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/nivoslider/homepage-scrollingimgs_stratus3.jpg",
                array('alt' => 'Stratus - eLearning')), "public/stratus"); ?>
		<?php print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/nivoslider/homepage-scrollingimgs_stratus5.jpg",
                array('alt' => 'Stratus - Contact us, Schedule a demo')), "public/stratus"); ?>
	</div>
	<div id="greyproductbar">
		<?php print image_tag("https://s3.amazonaws.com/static.globalclassroom.us/greyproductmenu/makesocialmediawork.jpg",
            array('alt' => 'make social media work for you')); ?>
	</div>
	<div id="threeimgmarketing">
		<?php
		$left_image = array("friends_frontpage-small-imgs.jpg");
		$rand_key = array_rand($left_image);
		print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/threebox_left/$left_image[$rand_key]",
                                        array('alt' => 'Stratus - Learning just became social.')),
                        "public/stratus?id=friends"); ?>
		<?php
		$center_image = array("groups_frontpage-small-imgs.jpg");
		$rand_key = array_rand($center_image);
        print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/threebox_center/$center_image[$rand_key]",
                                        array('alt' => 'Stratus groups')),
                        "public/stratus?id=groups"); ?>
		<?php
		$right_image = array("elearning_frontpage-small-imgs.jpg");
		$rand_key = array_rand($right_image);
        print link_to(
            image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/threebox_right/$right_image[$rand_key]",
                                array('alt' => 'Stratus eLearning')),
                        "public/stratus?id=elearning");
        /*
            if ($right_image[$rand_key] == 'act48.jpg')
            {
                print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/threebox_right/$right_image[$rand_key]",
                                                    array(
                                                            'id' => 'threeimgmarketing_right',
                                                            'alt' => 'Act48, act48.com, The largest statewide professional development center for teachers'
                                                    )), "http://act48.com", array('popup' => true));
                } else if($right_image[$rand_key] == 'neiste_frontpageblock.jpg')
            {
                print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/frontpage/threebox_right/$right_image[$rand_key]",
                        array(
                            'id' => 'threeimgmarketing_right',
                                                            'alt' => 'Professional Development for New England, NEISTE eClassrooms'
                        )), "http://neistevirtual.org", array('popup' => true));
            }*/
        ?>
	</div>
	<div id="sitemapgradient">
		<table id="sitemapnav">
			<tr>
				<!--<td>
					<ul>
						<li><strong>eSchool Solutions</strong></li>
						<li><?php //print link_to("Businesses", "public/businessSolutions"); ?></li>
						<li><?php //print link_to("K12 Schools", "public/k12Solutions"); ?></li>
						<li><?php //print link_to("Higher Education", "public/higherEducationSolutions"); ?></li>
						<li><?php //print link_to("Government/Non-Profit", "public/governmentSolutions"); ?></li>
					</ul>
				</td>
				<td>
					<ul>
						<li><strong>eClassroom Solutions</strong></li>
						<li><?php //print link_to("Families", "public/eclassroomSolutions?id=families"); ?></li>
						<li><?php //print link_to("K12 Teachers", "public/eclassroomSolutions?id=k12teachers"); ?></li>
						<li><?php //print link_to("Tutors", "public/eclassroomSolutions?id=tutors"); ?></li>
						<li><?php //print link_to("Small Businesses", "public/eclassroomSolutions?id=smallbusinesses"); ?></li>
						<li><?php //print link_to("Subject Experts", "public/eclassroomSolutions?id=subjectexperts"); ?></li>
					</ul>
				</td>-->
				<td>
					<ul>
						<li><strong>Products</strong></li>
						<li><?php print link_to("Stratus", "public/stratus"); ?></li>
						<li><?php print link_to("eClassrooms on Stratus", "public/eclassroomSolutions"); ?></li>
                        <li><?php print link_to("Services", "solutions/services"); ?></li>
						<!--<li><?php //print link_to("GlobalExpert", "public/eclassroomSolutions?id=globalexpert"); ?></li>
						<li><?php //print link_to("GlobalBusiness", "public/eclassroomSolutions?id=globalbusiness"); ?></li>
						<li><?php //print link_to("GlobalK12", "public/eclassroomSolutions?id=globalk12"); ?></li>-->
					</ul>
				</td>
                <td>
                    <ul>
                        <li><strong>Solutions</strong></li>
                        <li><?php print link_to("Business", "public/businessSolutions"); ?></li>
                        <li><?php print link_to("Membership Organizations", "public/membershipSolutions"); ?></li>
                        <li><?php print link_to("Government Agencies", "public/governmentSolutions"); ?></li>
                        <li><?php print link_to("Non-Profits", "public/nonprofitSolutions"); ?></li>
                        <li><?php print link_to("Education", "public/educationSolutions"); ?></li>
                        <!--
                        <li><?php //print link_to("Interest Groups", "public/interestGroupSolutions"); ?></li>
                        -->
                    </ul>
                </td>
				<td>
					<!--<ul>
						<li><strong>Services</strong></li>
						<li><?php //print link_to("eSchool Services", "solutions/services"); ?></li>
					</ul>
					<br/>-->
					<ul>
						<li><strong>Partners</strong></li>
						<li><?php print link_to("ACT 48 Center", "http://act48.com", array('popup' => 'true')); ?></li>
						<li><?php print link_to("NEISTE Virtual", "http://neistevirtual.org", array('popup' => 'true')); ?>
                        <li><?php print link_to("NY Virtual", "http://nyvirtual.org", array('popup' => 'true')); ?>
						<li><?php print link_to("Featured Clients", "public/featured"); ?></li>
						<!--<li><?php //print link_to("Authorized Resellers", "public/resellers"); ?></li>-->
					</ul>
				</td>
				<td>
					<ul>
						<li><strong>Help</strong></li>
						<li><?php print link_to("Search", "public/search"); ?></li>
						<li><?php print link_to("Support", "http://support.globalclassroom.us", array('popup' => 'true')); ?></li>
						<li><?php print link_to("Contact", "public/contact"); ?></li>
					</ul>
					<br/>
					<ul>
						<li id="sitemapnav-loginlink"><strong><?php print link_to("Login", "public/login"); ?></strong></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<?php include_partial("public/integration"); ?>
	</div>
	<script type="text/javascript">
	$(window).load(function() {
		$('#slider').nivoSlider({
			effect: 'fade', //Specify sets like: 'fold, fade,sliceDown'
			slices: 15,
			animSpeed: 500,
			pauseTime: 7500,
			startSlide: 0, //Set starting Slide (0 index)
			directionNav: true, //Next & prev
			directionNavHide: true, //Only show on hover
			controlNav: true, //1,2,3...
			controlNavThumbs: false, //Use thumbnails for Control Nav
			controlNavThumbsFromRel: false, //Use image rel for thumbs
			controlNavThumbsSearch: '.jpg', //Replace this with...
			controlNavThumbsReplace: '_thumb.jpg', //...this in thumb Image src
			keyboardNav: true, //Use left & right arrows
			pauseOnHover: true, //Stop animation while hovering
			manualAdvance: false, //Force manual transitions
			captionOpacity: 0.8, //Universal caption opacity
			beforeChange: function(){},
			afterChange: function(){},
			slideshowEnd: function(){} //Triggers after all slides have been shown
		});
	});
	</script>
</div>
