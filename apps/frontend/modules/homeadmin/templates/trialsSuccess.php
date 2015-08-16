<?php
global $CFG;
if($CFG->current_app->isMoodle())
{
    global $PAGE;
    $PAGE->set_url($CFG->httpswwwroot . '/eschool/edit');
}
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/homeadmin.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/js/homeadmin_trials.js" defer="defer"></script>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() {
    jQuery.getScript("/js/eschool/rightnav.js");
});
</script>
<button id="createTrialButton" class="trialButton">Create New Trial</button>
<a href="<?php print $CFG->current_app->getUrl() ?>"><button id="returnButton" class="returnButton">Exit</button></a>
<table id="gc_dashboard" class="tablesorter" cellspacing="1">
    <thead>
        <tr>
            <th>Platform Name</th>
            <th>Platform Owner</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Expiration Date</th>
            <th>Users</th>
            <th>Courses</th>
            <th>Last Activity</th>
        </tr>
    </thead>
    <tbody>
        <?php
        define('NULL_DATE', 2100000000);

        foreach($data_array as $data_item)
        {
            $trial = $data_item['trial'];
            $exp_ts = $trial->getExpirationTime();
            if ($end_ts = $trial->getEndDate())
            {
                $exp_date = date('m/d/Y', NULL_DATE);
                $exp_date_class = ' class="nullDateField"';
                $button_text = 'Restart';
                $end_date_class = '';
                $end_date = date('m/d/Y', $end_ts);
            }
            else
            {
                $end_date = date('m/d/Y', NULL_DATE);
                $end_date_class = ' class="nullDateField"';
                $button_text = 'End Trial';
                $exp_date = date('m/d/Y', $exp_ts);
                if ($exp_ts < time())
                {
                        $exp_date_class = ' class="expiredDateField"';
                }
                else
                {
                        $exp_date_class = '';
                }
            }
            $mhr_owner_obj = $data_item['owner']->getObject();
            $owner_tooltip = 'username:' . $mhr_owner_obj->username . ', email: ' .
                    $mhr_owner_obj->username;
        ?>
            <tr>
                <td><?php print $data_item['name']; ?></td>
                <td title="<?php print $owner_tooltip; ?>">
                    <?php print $data_item['owner']->getFullNameString(); ?>
                </td>
                <td><?php print date('m/d/Y', $trial->getStartDate()); ?></td>
                <td><span<?php print $end_date_class; ?>><?php print $end_date; ?></span></td>
                <td><span<?php print $exp_date_class; ?>><?php print $exp_date; ?></span></td>
                <td><?php print $data_item['num_users']; ?></td>
                <td><?php print $data_item['num_courses']; ?></td>
                <td><?php print $data_item['last_activity']?></td>
                <td>
                    <button class="editTrialButton trialButton" value="<?php print $trial->getId(); ?>" start_date="<?php print $trial->getStartDate(); ?>" end_date="<?php print $trial->getEndDate(); ?>">Edit</button>
                </td>
                <td>
                    <button class="deleteTrialButton trialButton" value="<?php print $trial->getId(); ?>">Delete</button>
                </td>
                <td>
                    <?php
                    if(isset($login_link))
                    {
                        print $login_link;
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div style="display:none" id="edit-dialog-form" title="Edit Trial">
    <p class="validateTips">Select the new start and end dates for this trial.</p>

    <form id="editTrialForm" name="editTrialForm" action="<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/editTrial'?>" method="POST">
    <fieldset>
        <input id="trial_id" name="trial_id" type="hidden" value="" />
        <label for="startdatepicker">Start Date: </label>
        <input type="text" name="startdatepicker" id="startdatepicker" class="datepicker text ui-widget-content ui-corner-all" />
        <label for="enddatepicker">End Date: </label>
        <input type="text" name="enddatepicker" id="enddatepicker" class="datepicker text ui-widget-content ui-corner-all" />
    </fieldset>
    </form>
</div>
<div style="display:none" id="create-dialog-form" title="Create New Trial">
    <p class="validateTips">Select the Platform and start date for this new trial.</p>

    <form id="createTrialForm" name="createTrialForm" action="<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/createTrial'?>" method="POST">
    <fieldset>
        <label for="eschool">Platform: </label>
        <select id="eschool" name="eschool" style="width:95%">
        <?php
            foreach(GcrInstitutionTable::getInstitutions() as $institution)
            {
                print "<option value={$institution->getShortName()}>{$institution->getFullName()}</option>";
            }
        ?>
        </select>
        <label for="newstartdatepicker">Start Date: </label>
        <input type="text" name="newstartdatepicker" id="newstartdatepicker" class="datepicker text ui-widget-content ui-corner-all" />
    </fieldset>
    </form>
</div>
<form id="deleteTrialForm" name="deleteTrialForm" value="-1" action="<?php print GcrEschoolTable::getHome()->getUrl(); ?>/homeadmin/deleteTrial" method="POST">
    <input id="del_trial_id" name="del_trial_id" type="hidden" />
</form>  