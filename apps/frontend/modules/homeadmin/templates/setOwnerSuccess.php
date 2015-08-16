<?php
global $CFG;
$owner_html = '<a href="' . $owner->getApp()->getAppUrl() . 'user/view.php?id=' . 
                        $owner->getObject()->id . '" title="' . $owner->getObject()->email . '">' .
                        $owner->getFullnameString() . '</a>';
?>
<div class="gc_small_form" style="width:50%"> 
    <table class="content" cellpadding="10" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Set Platform Owner</h1>
                    Please select the user account which will become the new platform owner.
                </div>
                <br />
                <table>
                    <tr><td>Platform Name:</td><td><?php print $institution->getFullName(); ?></td></tr>
                    <tr><td>Platform URL:</td><td><a href="<?php print $institution->getUrl(); ?>"><?php print $institution->getUrl(); ?></a></td></tr>
                    <tr><td>Current Owner:</td><td><?php print $owner_html; ?></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <form id="setOwnerUser" name="setOwnerUser" action="<?php print $CFG->current_app->getUrl(); ?>/homeadmin/doSetOwner" method="POST">
                    <input id="institution_id" name="institution_id" type="hidden" value="<?php print $institution->getShortName(); ?>" />
                    New Owner:
                    <select id="user_id" name="user_id">
                        <?php
                        $owner_id = $owner->getObject()->id;
                        $options = array();
                        foreach($users as $user)
                        {
                            if ($user->id > 1 && $user->id != $owner_id)
                            {
                                $options[ucfirst(trim($user->lastname))] = '<option value="' . $user->id . '">' . $user->firstname . 
                                    ' ' . $user->lastname . ' (' . $user->email . ')</option>'; 
                            }
                        } 
                        ksort($options);
                        foreach ($options as $option)
                        {
                            print $option;
                        }
                        ?>
                    </select><br /><br />
                    <input type="submit" class="button" value="Save Changes" />
                    <input type="button" class="button" value="Exit" onclick="window.location.href='<?php print $CFG->current_app->getUrl() . '/homeadmin/eschool' ?>'">
                </form>    
            
            </td>
            <td> &nbsp</td>
        </tr>
    </table>
</div>
