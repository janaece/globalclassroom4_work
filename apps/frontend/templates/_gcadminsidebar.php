<?php
    $app = gcr::getApp();
    if ($app->isMoodle())
    {
        $app = $app->getInstitution();
    }
    $home = GcrEschoolTable::getHome();
?>
<div id="sb-configuresite" class="sideblock sideblock-1">
    <h3>Configure Site</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-siteoptions">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/options.php">Site options</a>
                </li>
                <li id="sb-editsitepages">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/pages.php">Edit site pages</a>
                </li>
                <li id="sb-menus">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/menu.php">Menus</a>
                </li>
                <li id="sb-networking">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/networking.php">Networking</a>
                </li>
                <li id="sb-pages">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/views.php">Site Pages</a>
                </li>
                <li id="sb-share">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/shareviews.php">Shared Pages</a>
                </li>
                <li id="sb-files">
                    <a href="<?php echo $app->getAppUrl(); ?>artefact/file/sitefiles.php">Site Files</a>
                </li>
                <li id="sb-institutionsettings">
                    <a href="<?php echo $app->getUrl(); ?>/institution/settings">Edit Settings</a>
                </li>
                <li id="sb-institutioninformation">
                    <a href="<?php $app->getUrl(); ?>/eschool/edit">Edit Information</a>
                </li>
                <li id="sb-institutioncontact">
                    <a href="<?php $app->getUrl(); ?>/eschool/editContact">Edit Contact</a>
                </li>
                <li id="sb-eclassrooms_management">
                    <a href="<?php $app->getUrl(); ?>/institution/eclassrooms">eClassroom Administration</a>
                </li>    
                <li id="sb-addcatalog">
                    <a href="<?php echo $app->getAppUrl(); ?>artefact/courses/catalogs.php">Add/Remove Course Catalogs</a>
                </li>
                <li id="sb-addcollection">
                    <a href="<?php echo $app->getUrl(); ?>/eschool/new">Create New Course Catalog</a>
                </li>
                <li id="sb-accounting">
                    <a href="<?php echo $home->getUrl(); ?>/account/view?eschool=<?php print $app->getShortName(); ?>" target="_blank">Accounting</a>
                </li>
            </ul>
        </div>
</div>

<div id="sb-users" class="sideblock sideblock-2">
    <h3>Users</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-usersearch">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/search.php">User Search</a>
                </li>
                <li id="sb-suspendusers">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/suspended.php">Suspended Users</a>
                </li>
                <li id="sb-sitestaff">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/staff.php">Site Staff</a>
                </li>
                <li id="sb-siteadmins">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/admins.php">Site Admins</a>
                </li>
                <li id="sb-adminnotifications">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/notifications.php">Admin Notifications</a>
                </li>
                <li id="sb-adduser">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/add.php">Add User</a>
                </li>
                <li id="sb-addusersbycsv">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/uploadcsv.php">Add Users by CSV</a>
                </li>
                <li id="sb-migrateusers">
                    <a href="<?php echo $app->getAppUrl(); ?>artefact/eschooladmin/migrateusers.php">Migrate Users to Catalog</a>
                </li>
            </ul>
        </div>
</div>

<div id="sb-groups" class="sideblock sideblock-3">
    <h3>Groups</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-admingroups">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/groups/groups.php">Administer Groups</a>
                </li>
                <li id="sb-groupcategories">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/groups/groupcategories.php">Group Categories</a>
                </li>
            </ul>
        </div>
</div>

<div id="sb-institutions" class="sideblock sideblock-4">
    <h3>Institutions</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-institutionsblock">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutions.php">Institutions</a>
                </li>
                <li id="sb-institutionmembers">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutionusers.php">Members</a>
                </li>
                <li id="sb-institutionstaff">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutionstaff.php">Institution Staff</a>
                </li>
                <li id="sb-institutionadmins">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutionadmins.php">Institutions Administrators</a>
                </li>
                <li id="sb-pages">
                    <a href="<?php echo $app->getAppUrl(); ?>view/institutionviews.php">Institution Pages</a>
                </li>
                <li id="sb-share">
                    <a href="<?php echo $app->getAppUrl(); ?>view/institutionshare.php">Shared Pages</a>
                </li>
                <li id="sb-files">
                    <a href="<?php echo $app->getAppUrl(); ?>file/institutionfiles.php">Institution Files</a>
                </li>
            </ul>
        </div>
</div>

<div id="sb-extensions" class="sideblock sideblock-5">
    <h3>Extensions</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-pluginadministration">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/extensions/plugins.php">Plugin Administration</a>
                </li>
                <li id="sb-htmlfilters">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/extensions/filter.php">HTML Filters</a>
                </li>
            </ul>
        </div>
</div>