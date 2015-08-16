<?php
global $CFG;
if($CFG->current_app->isMoodle())
{
    global $PAGE;
    $PAGE->set_url($CFG->httpswwwroot . '/eschool/edit');
}
?>
<div id="left-column" class="sidebar">
<?php
    global $CFG;
    $current_user = $CFG->current_app->getCurrentUser();
    $role_manager = $current_user->getRoleManager();
    if ($role_manager->hasPrivilege('GCUser'))
    {
        include_partial('global/gcadminsidebar');
    }
    else if ($role_manager->hasPrivilege('EschoolAdmin'))
    {
        include_partial('global/eschooladminsidebar');
    }
    else
    {
        include_partial('global/usersidebar');
    }
?>
</div>
<div id="edit_information" class="main-column">
    <table class="content">
        <tr>
            <td>
                <div id="editEschoolInfo">
                    <h1>Edit Your Stratus Platform</h1>
                    Here you can change the information you first provided Global Classroom with when you created your Platform.
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <?php include_partial('editform', array('eschoolForm' => $eschoolForm, 'formErrors' => $formErrors,)) ?>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    jQuery('#main-nav ul li a:contains(Configure Site)').parent().addClass('selected');
</script>