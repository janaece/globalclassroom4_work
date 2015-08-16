<div id="eschoolsearch">
	<h1>eSchool Search</h1>
	<p>
		<strong>Below you can enter a word or phrase that is associated with your eSchool's name and search our database for your eSchool.</strong><br/>
		For example: a part of your eSchool's name, or the word "school", or any term that is specifically within your eSchool's name.
	</p>
	<?php 
	global $CFG;
	$hitCount = 0;
	?>
	<form id="eschoolSearchForm" name="eschoolSearchForm" action="<?php print $CFG->current_app->getUrl() . '/eschool/search'; ?>" method="POST">
	<?php print $searchForm; ?>
		<input type="submit" class="button" value="Search" />
	</form>
	<?php 
	if ($eschoolList)
	{
		foreach ($eschoolList as $matchCategory)
		{
			foreach ($matchCategory as $app)
			{
				print '<p>' . ++$hitCount . '. <a href="' . $app->getAppUrl() . '">' . $app->getFullName() . '</a></p>';
			}
		}
		if ($hitCount == 0)
		{
			print '<p>Sorry, no eSchools were found that match your search criteria.</p>';
		}
	}
	?>
</div>