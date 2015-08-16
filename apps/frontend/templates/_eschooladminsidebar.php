<?php
    $app = gcr::getApp();
    if ($app->isMoodle())
    {
        $app = $app->getInstitution();
    }
?>
<div id="sb-configuresite" class="sideblock sideblock-1">
    <h3>Configure Site</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-siteoptions">
                    <a href="<?php echo $app->getAppUrl(); ?>artefact/eschooladmin/settings.php">
                        <img src="/images/icons/siteoptions.png" alt="Site Options"/>&nbsp;Site options
                    </a>
                </li>
                <li id="sb-accounting">
                    <a href="<?php echo $app->getUrl(); ?>/account/view">
                        <img src="/images/icons/eschool-accounting.png" alt="accounting"/>&nbsp;Accounting
                    </a>
                </li>
                <li id="sb-eclassrooms_management">
                    <a href="<?php $app->getUrl(); ?>/institution/eclassrooms">
                        <img src="/images/icons/institutions.png" alt="accounting"/>&nbsp;eClassroom Administration
                    </a>
                </li> 
                <li id="sb-institutionsettings">
                    <a href="<?php echo $app->getUrl(); ?>/institution/settings">
                        <img src="/images/icons/custom-banner.png" alt="custom banner"/>&nbsp;Edit Settings
                    </a>
                </li>
                <li id="sb-institutioninformation">
                    <a href="<?php echo $app->getUrl(); ?>/eschool/edit">
                        <img src="/images/icons/editinformation.png"/>&nbsp;Edit Information
                    </a>
                </li>
                <li id="sb-institutioncontact">
                    <a href="<?php echo $app->getUrl(); ?>/eschool/editContact">
                        <img src="/images/icons/editcontact.png"/>&nbsp;Edit Contact
                    </a>
                </li>
                <li id="sb-pages">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/site/pages.php">
                        <img src="/images/icons/site-pages.png" alt="site pages"/>&nbsp;Site Pages
                    </a>
                </li>
                <li id="sb-sitestatistics">
                    <a href="<?php echo $app->getAppUrl();?>artefact/eschooladmin/statistics.php">
                        <img src="/images/icons/sitestatistics.png" alt="site statistics"/>&nbsp;Site Statistics
                    </a>
                </li>
            </ul>
        </div>
</div>

<div id="sb-manageusers" class="sideblock sideblock-2">
    <h3>Manage Users</h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-usersearch">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/search.php">
                        <img src="/images/icons/user-search.png" alt="User Search" />&nbsp;User Search
                    </a>
                </li>
                <li id="sb-suspendedusers">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/suspended.php">
                        <img src="/images/icons/suspend-user.png" alt="suspended users"/>&nbsp;Suspended Users
                    </a></li>
                <li id="sb-adduser"><a href="<?php echo $app->getAppUrl(); ?>admin/users/add.php">
                    <img src="/images/icons/add-user.png" alt="add user"/>&nbsp;Add User
                    </a></li>
                <li id="sb-addusersbycsv">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/uploadcsv.php">
                        <img src="/images/icons/add-user-csv.png" alt="add user" />&nbsp;Add Users by CSV
                    </a></li>
                <li id="sb-migrateusers">
                    <a href="<?php echo $app->getAppUrl(); ?>artefact/eschooladmin/migrateusers.php">
                        <img src="/images/icons/migrate_users.png" alt="migrate users" />&nbsp;Migrate Users to Catalog
                    </a>
                </li>
            </ul>
        </div>
</div>

<div id="sb-manageinstitutions" class="sideblock sideblock-3">
    <h3>Manage Institutions
        <a title="Edit Settings" class="fr" href="<?php echo $app->getAppUrl(); ?>admin/users/institutions.php">
        <img src="<?php $app->getUrl(); ?>/images/settings.png" alt="edit eschool settings" /></a>
    </h3>
        <div class="sideblock-content">
            <ul>
                <li id="sb-members">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutionusers.php">
                        <img src="/images/icons/members.png" alt="members"/>&nbsp;Members
                    </a>
                </li>
                <li id="sb-institutionstaff">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutionstaff.php">
                        <img src="/images/icons/institution-staff.png" alt="institution staff"/>&nbsp;Institution Staff
                    </a>
                </li>
                <li id="sb-institutionadministrators">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/institutionadmins.php">
                        <img src="/images/icons/institution-admin.png" alt="institution administrators"/>&nbsp;Institution Administrators
                    </a>
                </li>
                <li id="sb-adminnotifications">
                    <a href="<?php echo $app->getAppUrl(); ?>admin/users/notifications.php">
                        <img src="/images/icons/admin-notification.png" alt="admin notifications"/>&nbsp;Admin Notifications
                    </a>
                </li>
                <li id="sb-views">
                    <a href="<?php echo $app->getAppUrl(); ?>view/institutionviews.php">
                        <img src="/images/icons/pages.png" alt="pages"/>&nbsp;Institution Pages
                    </a>
                </li>
                <li id="sb-share">
                    <a href="<?php echo $app->getAppUrl(); ?>view/institutionshare.php">
                        <img src="/images/icons/share.png" alt="share"/>&nbsp;Shared Pages
                    </a>
                </li>
                <li id="sb-files">
                    <a href="<?php echo $app->getAppUrl(); ?>artefact/file/institutionfiles.php">
                        <img src="/images/icons/files.png" alt="files"/>&nbsp;Institution Files
                    </a>
                </li>
            </ul>
        </div>
</div>