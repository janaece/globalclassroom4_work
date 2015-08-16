<?php slot('dynamic_content') ?>
<div id="classic_label">Attention all Classic Users!</div>
<div>
	<div>
		<h4>This is very important: please read!</h4>
		<p>
			Global Classroom has moved to a new version.  <strong>The old version of Global Classroom is no longer publicly accessible.</strong>  
			If you don't want to lose your eSchool, courses, or course content, please call 866-535-3772, or email <?php print mail_to("rwillis@globalclassroom.us", "rwillis@globalclassroom.us", "encode=true"); ?>.
		</p>
		<p>
			To migrate your eSchool and its contents to the new platform, please follow the documentation located on our 
			<?php print link_to("support page", "http://support.globalclassroom.us/guides/classic-to-cirrus-migration"); ?>.
		</p>
	</div>
	<div>
		<h4>How do I know if my eSchool has been migrated and is on the new version?</h4>
		<p>
			Your eSchool has a custom URL, for example, "yourschoolname.globalclassroom.us" or "act48.globalclassroom.us".
		</p>
	</div>
</div>
<?php end_slot() ?>

<?php slot('side_content') ?>
<?php end_slot() ?>