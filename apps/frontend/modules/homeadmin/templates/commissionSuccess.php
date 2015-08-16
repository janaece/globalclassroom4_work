<?php
global $CFG;
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/homeadmin.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="/js/homeadmin_commission.js" defer="defer"></script>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() 
{
    jQuery.getScript("/js/eschool/rightnav.js");
});
</script>
<button id="createCommissionButton" class="commissionButton">Create New Commission</button>
<a href="<?php print $CFG->current_app->getUrl() ?>"><button id="returnButton" class="returnButton">Exit</button></a>
<table id="gc_commission" class="tablesorter" cellspacing="1">
    <thead>
        <tr>
            <th>Platform Name</th>
            <th>Catalog Name</th>
            <th>Catalog Platform</th>
            <th>Commission Rate</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($commissions as $commission)
        {
            $institution = $commission->getInstitution();
            $eschool = $commission->getEschool();
            $eschool_institution = $eschool->getInstitution();
            
            $commission_rate = $commission->getCommissionRate();
            $platform_name = '<a href="' . $institution->getUrl() . '">' . $institution->getFullName() . '</a>';
            $catalog_name = '<a href="' . $eschool->getAppUrl() . '">' . $eschool->getFullName() . '</a>';
            $catalog_platform_name = '<a href="' . $eschool_institution->getUrl() . 
                    '">' . $eschool_institution->getFullName() . '</a>';
        ?>
            <tr>
                <td><?php print $platform_name; ?></td>
                
                <td><?php print $catalog_name ?></td>
                <td><?php print $catalog_platform_name ?></td>
                <td><?php print $commission_rate  . '%'?></td>
                <td>
                    <button class="editCommissionButton commissionButton" value="<?php print $commission->getId(); ?>">Edit</button>
                    <button class="deleteCommissionButton commissionButton" value="<?php print $commission->getId(); ?>">Delete</button>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div style="display:none" id="edit-dialog-form" title="Edit Commission">
    <p class="validateTips">Select the new start and end dates for this trial.</p>

    <form id="editTrialForm" name="editCommissionForm" action="<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/editCommission'?>" method="POST">
    <fieldset>
        <input id="commission_id" name="commission_id" type="hidden" value="" />
        <label for="commission_rate">Commission Rate %: </label>
        <input type="text" name="edit_commission_rate" id="edit_commission_rate" value="" />
    </fieldset>
    </form>
</div>
<div style="display:none" id="create-dialog-form" title="Create New Commission">
    <p class="validateTips">Select the platform where users exist, and the catalog where those users may purchase courses at the specified commission rate, paid to the selected platform.</p>

    <form id="createCommissionForm" name="createCommissionForm" action="<?php print GcrEschoolTable::getHome()->getUrl() . '/homeadmin/createCommission'?>" method="POST">
    <fieldset>
        <label for="institution">Platform: </label>
        <select id="institution" name="institution" style="width:95%">
        <?php
            foreach(GcrInstitutionTable::getInstitutions() as $institution)
            {
                print "<option value={$institution->getShortName()}>{$institution->getFullName()} ({$institution->getShortName()})</option>";
            }
        ?>
        </select>
        <label for="institution">Catalog: </label>
        <select id="eschool" name="eschool" style="width:95%">
        <?php
            foreach(GcrEschoolTable::getEschools() as $eschool)
            {
                print "<option value={$eschool->getShortName()}>{$eschool->getFullName()} ({$eschool->getShortName()})</option>";
            }
        ?>
        </select>
        <label for="commission_rate">Commission Rate %: </label>
        <input type="text" name="commission_rate" id="commission_rate" value="" />       
    </fieldset>
    </form>
</div>
<form id="deleteCommissionForm" name="deleteCommissionForm" value="-1" action="<?php print GcrEschoolTable::getHome()->getUrl(); ?>/homeadmin/deleteCommission" method="POST">
    <input id="del_commission_id" name="del_commission_id" type="hidden" />
</form>  