<?php
global $CFG;
if($CFG->current_app->isMoodle())
{
    global $PAGE;
    $PAGE->set_url($CFG->httpswwwroot . '/eschool/edit');
}
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "/css/homeadmin.css";</style>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" defer="defer">

jQuery(document).ready(function() {
// call the tablesorter plugin 
    jQuery("#gc_dashboard").tablesorter({sortList: [[0,0]], widgets: ['zebra']});
});
</script>
<script type="text/javascript">
    function setConfigShortName(obj)
    {
        var selected_name = document.getElementById(obj.id + 'EschoolList');
        var form_field = document.getElementById(obj.id + 'Config');
        if (form_field.value != selected_name.value)
        {
            form_field.value = selected_name.value;
            obj.action += "Eschool";
        }
    }
</script>
<div id="eschools_container">
    <small><div id="accountTotals">
        <table>
            <tr>
                <td>All Account Balances:&nbsp</td>
                <td class="total"><?php print GcrPurchaseTable::gc_format_money($totals['balances']); ?></td>
            </tr>

            <tr>
                <td>Total Number of Users:&nbsp</td>
                <td class="total"><?php print $totals['users']; ?></td>
            </tr>
            <tr>
                <td>Total Number of Courses:&nbsp</td>
                <td class="total"><?php print $totals['courses']; ?></td>
            </tr>
         </table>
    </div></small>
    <div>
    <?php print '<a href="' . $CFG->current_app->getInstitution()->getUrl() . '/institution/new' . '"><button>Create a New Trial Platform</button></a>'; ?>
    <br /><br />
    <small><?php print $totals['eschools']; ?> Platforms</small>
    </div>
    <?php
    if (count($data_array) > 0)
    {
    ?>
        <table id="gc_dashboard" class="tablesorter" cellspacing="1">
        <thead>
            <tr>
                <th>Platform Name</th>
                <th>Owner User</th>
                <th>Account Balance</th>
                <th>Creation Date</th>
                <th>Status</th>
                <th>Renewal Date</th>
                <th>Users</th>
                <th>Courses</th>
                <th>Enrol</th>
                <th>Enrol (30 days)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($data_array as $data_item)
        {
            if (strtotime($data_item['date']) > 2090000000)
            {
                $date_class = ' class="nullDateField"';
            }
            else if (strtotime($data_item['date']) < time())
            {
                $date_class = ' class="expiredDateField"';
            }
            else
            {
                $date_class = '';
            }
            if ($data_item['name'] == '')
            {
                $name_text = $data_item['eschool']->getShortName();
            }
            else
            {
                $name_text = $data_item['name'];
            }
            $owner_html = '<a target="_blank" href="' . $CFG->current_app->getUrl() . '/homeadmin/setOwner?id=' . 
                        $data_item['eschool']->getShortName() . '"';
            if ($data_item['owner'])
            {
                $owner_html .= ' title="' . $data_item['owner']->getObject()->email . '">' .
                        $data_item['owner']->getFullnameString() . '</a>';
            }
            else
            {
                 $owner_html .= ' style="color:red;font-weight:bold">ERROR: No Owner User Set</a>';
            }
        ?>
            <tr>
                <td>
                    <a href="<?php print "https://" . $data_item['eschool']->getShortName() . "." . gcr::domainName ?>" target="_blank">
                        <?php print $name_text; ?>
                    </a>
                </td>
                <td><?php print $owner_html ?></td>
                <td style="width:70px;"><?php print GcrPurchaseTable::gc_format_money($data_item['balance']); ?></td>
                <td style="width:70px;"><?php print $data_item['created']; ?></td>
                <td><?php print $data_item['status']; ?></td>
                <td style="width:70px;"><span<?php print $date_class; ?>><?php print $data_item['date']; ?></span></td>
                <td><?php print $data_item['num_users']; ?></td>
                <td><?php print $data_item['num_courses']; ?></td>
                <td><?php print $data_item['num_enrol']; ?></td>
                <td><?php print $data_item['num_enrol_month']; ?></td>
                <td>
                    <a target="_blank" href="
                       <?php print $CFG->current_app->getUrl() . '/account/view?eschool=' .
                               $data_item['eschool']->getShortName(); ?>">
                       <button type='button'>$</button>
                    </a>
                </td>
                <?php
                print '<td><form id="' . $data_item['eschool']->getShortName() . '" action="' . $CFG->current_app->getUrl() .
                        '/homeadmin/config' . '" onsubmit="return setConfigShortName(this)" method="POST" target="_blank"><input id="' . $data_item['eschool']->getShortName() . 'Config" name="eschoolList" type="hidden" value="' .
                        $data_item['eschool']->getShortName() . '" /><input class="button" type="submit" value="Settings"</></form></td>';
                ?>
                <td style="text-align:left;">
                
                <form action="<?php print $CFG->current_app->getUrl(); ?>/homeadmin/adminAccess" method="POST" target="_blank">
                    <table>
                        <tr>
                            <td>
                                <select name="eschoolList" id="<?php print $data_item['eschool']->getShortName(); ?>EschoolList" style="width:150px;" >
                                    <option value="<?php print $data_item['eschool']->getShortName(); ?>"><?php print $data_item['eschool']->getShortName(); ?></option>
                                    <?php
                                        if(!empty($data_item['moodles']))
                                        {
                                            $moodles = array();
                                            foreach($data_item['moodles'] as $key => $eschool)
                                            {
                                                $moodles[] = $eschool->getShortName();
                                            }
                                            asort($moodles);
                                            foreach($moodles as $short_name)
                                            {
                                                print '<option value="' . $short_name . '">' . $short_name . '</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                            
                            <?php
                            if ($gc_home_admin)
                            { ?>
                                <td><input style="float:right" class="button" type="submit" value="Login" /></td>
                            <?php
                            } ?>
                        </tr>
                    </table>
                </form>
                
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        </table>
    </div>
    <?php
    }
else
{
    print '<div style="text-align:center"><h3>You do not currently have any Platforms</h3>';
    print 'Click the "Create a new trial Platform" button above to create your free, trial Stratus Platform.</div><br />';
}
?>