<h1>Sitewide User Management Admin Center</h1>
<div id="adminUserMessage" style="color:red">
	<?php print($message) ?>
</div>
<div id="addAdminAccess" name="addAdminAccess">
	<h2>Sitewide Admin Access - Add User</h2>
	<?php include_partial('addAdminAccess', array('userList' => $userList)) ?>
</div>
<div id="removeAdminAccess" name="removeAdminAccess">
	<h2>Sitewide Admin Access - Remove User</h2>
	<?php include_partial('removeAdminAccess', array('adminList' => $adminList)) ?>
</div>