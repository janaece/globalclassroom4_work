<script type="text/javascript">
function confirmPost(message)
{
	var agree = confirm(message);
	if (agree)
		return true;
	else
		return false;
}
function confirmSQL()
{
	var selector = document.getElementById('schema').value;
	if (selector == '1')
	{
		selector = '[All eSchools]';
	}
	var message = 'Execute SQL: "' + document.getElementById('sqlStatementStart').value + ' ' + selector + '.' + document.getElementById('sqlStatementEnd').value + '" (This action is unreversable)?';
	return confirmPost(message);
}
</script>
<style type="text/css">
    div.gc_small_form {width:90%;margin:20px auto}
    .pageData {margin:25px}
</style>
<h1 class="pageData">Sitewide eSchool Management Admin Center</h1>
<div class="pageData" id="adminEschoolMessage" style="color:red">
	<?php
        if(isset($message))
        {
            print($message);
        }
        ?>
</div>
<div id="setEschoolMessage" name="setEschoolMessage" class="gc_small_form">
    <h2>Set eSchool Message</h2>
    <form action="<?php echo ('eschoolMessage')?>" method="POST">
        <b>eSchool Message:</b>
        <br />
        <textarea type="text" id="eschoolMessage" name="eschoolMessage" rows="5" cols="60"><?php global $CFG; print(trim($CFG->gc_eschool_message));?></textarea>
        <input type="submit" class="button" value="Submit" onclick="return confirmPost('Are you sure you want to SET ESCHOOL MESSAGE?')" />
    </form>	
</div>
<div id="runCron" name="runCron" class="gc_small_form">
	<h2>Run Cron Script</h2>
	<?php include_partial('runCronForm', array('eschoolList' => $eschoolList, 'institutionList' => $institutionList)) ?>
</div>
<div id="setEschoolOrganizationId" name="setEschoolOrganizationId" class="gc_small_form">
	<h2>Set eSchool Organization Id</h2>
	<?php include_partial('setOrganizationId', array('eschoolList' => $eschoolList, 'institutionList' => $institutionList)) ?>
</div>
<div id="endTrial" name="endTrial" class="gc_small_form">
	<h2>End eSchool Trial</h2>
	<?php include_partial('form', array('form' => $form)) ?>
</div>
<div id="turnOffEmail" name="turnOffEmail" class="gc_small_form">
	<h2>Disable Emails (Does not affect EschoolStaff role or higher)</h2>
	<?php include_partial('turnOffEmailsForm', array('institutionList' => $institutionList)) ?>
</div>
<div id="mdlConfigEdit" name="mdlConfigEdit" class="gc_small_form">
	<h2>Set Moodle Config Vars</h2>
	<?php include_partial('MdlConfigForm', array('eschoolList' => $eschoolList, 'configVars' => $mdlConfigVars, 'pluginConfigVars' => $pluginConfigVars)) ?>
</div>
<div id="mhrConfigEdit" name="mhrConfigEdit" class="gc_small_form">
	<h2>Set Mahara Config Vars</h2>
	<?php include_partial('MhrConfigForm', array('institutionList' => $institutionList, 'configVars' => $mhrConfigVars)) ?>
</div>
<div id="ldapSync" name="ldapSync" class="gc_small_form">
	<h2>Sync LDAP Users in Mahara</h2>
	<?php include_partial('ldapSyncForm', array('institutionList' => $institutionList)) ?>
</div>
<div id="autoUpdate" name="autoUpdate" class="gc_small_form">
	<h2>Perform Moodle Auto-updates</h2>
	<?php include_partial('autoUpdates', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="purgeCaches" name="purgeCaches" class="gc_small_form">
	<h2>Perform Moodle Purge Caches</h2>
	<?php include_partial('purgeCaches', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="deleteCaches" name="deleteCaches" class="gc_small_form">
	<h2>Delete Schema Cache Directories</h2>
	<?php include_partial('deleteCacheDirectoriesForm', array('eschoolList' => $eschoolList, 'institutionList' => $institutionList)) ?>
</div>
<div id="resetMdlCacheSettings" name="resetMdlCacheSettings" class="gc_small_form">
	<h2>Reset Moodle Caching Settings To startadmin Values</h2>
	<?php include_partial('resetMdlCacheSettings', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="resetMdlRoles" name="resetMdlRoles" class="gc_small_form">
	<h2>Reset Moodle Roles to Template Permissions</h2>
	<?php include_partial('resetMdlRolesForm', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="resetMdlCourseBlocks" name="resetMdlCourseBlocks" class="gc_small_form">
	<h2>Reset Moodle Course Blocks</h2>
	<?php include_partial('resetMdlCourseBlocksForm', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="refreshMdlMediaelementjsUrls" name="refreshMdlMediaelementjsUrls" class="gc_small_form">
	<h2>Refresh Cloud Storage Urls on Catalog</h2>
	<?php include_partial('refreshMdlMediaelementjsForm', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="mnetReplacement" name="mnetReplacement" class="gc_small_form">
	<h2>Perform MNET Key Replacement on eSchool System (Mahara + all Moodles it owns)</h2>
	<?php include_partial('mnetReplacement', array('institutionList' => $institutionList)) ?>
</div>
<div id="deleteInstitution" name="deleteInstitution" class="gc_small_form">
	<h2>Delete eSchool System (Mahara + all Moodles it owns)</h2>
	<?php include_partial('deleteInstitutionForm', array('institutionList' => $institutionList)) ?>
</div>
<div id="deleteEschool" name="deleteEschool" class="gc_small_form">
	<h2>Delete Moodle</h2>
	<?php include_partial('deleteEschoolForm', array('eschoolList' => $eschoolList)) ?>
</div>
<div id="SQLExecutor" name="SQLExecutor" class="gc_small_form">
	<h2>Execute SQL Statement</h2>
	<?php include_partial('executeSQLForm', array('eschoolList' => $eschoolList, 'institutionList' => $institutionList)) ?>
</div>
<div id="ViewLogFile" name="ViewLogFile" class="gc_small_form">
	<h2>View Log File</h2>
	<?php include_partial('viewLogFileForm', array('logFileList' => $logFileList)) ?>
</div>
<div id="addReservedName" name="addReservedName" class="gc_small_form">
	<h2>Add Reserved Name</h2>
	<?php include_partial('addReservedName') ?>
</div>
<h3 class="pageData"><a href="<?php print GcrEschoolTable::getHome()->getAppUrl(); ?>">Return To Global Classroom Home</a></h3>