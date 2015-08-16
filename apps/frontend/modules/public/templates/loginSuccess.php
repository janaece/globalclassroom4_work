<table id="top_login_table">
    <tr>
        <td class="eclassroom_label">

                <div id="cirrus_search_label">Find Your Cirrus eSchool to Login</div>
        </td>
        <td class="eclassroom_label">
 
                <?php print image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/act48cntr_logo.png",
                        array(
                                'width' => '166px',
                                'height' => '50px'));
                ?>
                <br/>
                <strong>Want to take a course?</strong>
                <p>
                The ACT 48 Center is open to all Pennsylvania teachers.
                </p>
        </td>
    </tr>
    <tr>
        <td>
            <?php print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/buttons/search_button.jpg"), "public/search"); ?>
           <br/>
              <br/>
        </td>
        <td>
            <?php print link_to(image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/buttons/act48login_button.jpg"), "http://act48.com"); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php //print link_to("Learn more about the New eSchool", "solutions/eschool"); ?>
        </td>
        <td>
            <?php print link_to("ACT 48 Center Course Catalog", "http://act48.globalclassroom.us/cirrus/course"); ?>
        </td>
    </tr>
</table>

<table id="login_table">
	<tr>
		<td class="eclassroom_label">
			<?php print image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/globalexpert/global-expert-logo.jpg"); ?>
		</td>
		<td class="eclassroom_label">
			<?php print image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/globalk12/global-k12-logo.jpg"); ?>
		</td>
		<td class="eclassroom_label">
			<?php print image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/globalbusiness/global-business-logo.jpg"); ?>
		</td>
	</tr>
	<tr>
		<td>
			<p>
				Click below to access your Cirrus eClassroom on GlobalExpert
			</p>
		</td>
		<td>
			<p>
				Click below to access your Cirrus eClassroom on GlobalK12
			</p>
		</td>
		<td>
			<p>
				Click below to access your Cirrus eClassroom on GlobalBusiness
			</p>
		</td>
	</tr>
	<tr>
		<td class="login_button_link">
			<?php print link_to(
                                image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/buttons/globalexpertlogin_button.jpg"),
                                "https://expert.globalclassroom.us/cirrus/login/index.php", array('popup' => 'true')); ?>
		</td>
		<td class="login_button_link">
			<?php print link_to(
                                image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/buttons/globalk12login_button.jpg"),
                                "https://k12.globalclassroom.us/cirrus/login/index.php", array('popup' => 'true')); ?>
		</td>
		<td class="login_button_link">
			<?php print link_to(
                                image_tag("https://s3.amazonaws.com/static.globalclassroom.us/images/buttons/globalbusinesslogin_button.jpg"),
                                "https://business.globalclassroom.us/cirrus/login/index.php", array('popup' => 'true')); ?>
		</td>
	</tr>
</table>
<script type="text/javascript">
	$(document).ready
    (
      function ()
      {
		$('#header_login-btn').hide();
      }
    );
</script>