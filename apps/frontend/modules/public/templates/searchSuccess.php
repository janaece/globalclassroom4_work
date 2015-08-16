<!--
    <div>
        <img src="https://s3.amazonaws.com/static.globalclassroom.us/images/pageheadings/search_pageheading.jpg" />
    </div>
-->
    <p>
    <h1>Use this page to find a Community!</h1><br/>
    </p>
	<p>
		Enter a word or phrase that is associated with your Community's name and search our database for your Community.<br/>
		For example: a part of your Community's name, or the word "school", or any term that is specifically within your Community's name.
	</p>
	<br/>
    <p>
        To search for a Cirrus eSchool, <?php print link_to('click here', 'https://home.globalclassroom.us/search'); ?>.
    </p>
<form id="eschoolSearchForm" name="GcrEschoolSearchForm" action="/public/search" method="POST">
	<?php print $searchForm; ?>

<input type="submit" class="button" value="Search" />
</form>
	
	<?php
    
	global $CFG;
	$hitCount = 0; 
	if (!empty($eschoolList))
	{
		foreach ($eschoolList as $matchCategory)
		{
			foreach ($matchCategory as $institution)
			{
				print '<p>' . ++$hitCount . '. <a href="' . $institution->getAppUrl() . '" target="_blank">' . $institution->getFullName() . '</a></p>';
			}
		}
		if ($hitCount == 0)
		{
			print '<p>Sorry, no Communities were found that match your search criteria.</p>';
		}
	}
	 ?>