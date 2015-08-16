<?php
global $CFG;
?>
<style type="text/css">@import "/css/tablesorter/style.css";</style>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<style type="text/css">@import "/css/account_eschool.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() {
// call the tablesorter plugin
    jQuery("#gc_course_history").tablesorter({sortList: [[0,1]], widgets: ['zebra']});
    jQuery("input.datepicker").datepicker();
});
</script>
<div id="left-column" class="sidebar">
<?php
    if ($gc_admin && !$owner)
    {
        include_partial('global/eschooladminsidebar');
    }
    elseif ($gc_admin)
    {
        include_partial('global/gcadminsidebar');
    }
    else
    {
        include_partial('global/usersidebar');
    }
?>
</div>
<div id="transaction-history" class="main-column">
    <small>
    <div id="accountOverview">
        <h1>
            Course History
        </h1>
        <br />
        <?php
        $form_action_param_string = '';
        if ($gc_admin || $owner)
        {
            if(isset($mhr_user_obj))
            {
                $form_action_param_string = '?user=' . $mhr_user_obj->id;
            }
        }
        ?>
        <form id="courseHistoryForm" name="courseHistoryForm" action="<?php print $CFG->current_app->getUrl() . '/course/history' . $form_action_param_string; ?>" method="POST">
            <label for="startdate">From date </label>
            <input type="text" name="startdate" id="startdate" value="<?php print date('m/d/Y', $start_ts); ?>" class="datepicker" />
            <label for="enddate">to </label>
            <input type="text" name="enddate" id="enddate" value="<?php print date('m/d/Y', $end_ts); ?>" class="datepicker" />
            <input type="submit" class="button" value="View Courses" />
        </form>
    </div>
    <?php
    $record_count = $course_history_table->getTotal('record_count');
    if ($record_count > 0 && $CFG->current_app->getConfigVar('gc_course_history_show_averages') == 'on')
    {
        $average_grade_letter = $course_history_table->getAverageGradeLetter();
        $average_grade = $course_history_table->getTotal('average_grade');
        ?>
        <div id="accountTotals">
            <table>
                <tr>
                    <td>Average Grade:&nbsp</td>
                    <td class="total"><?php print number_format($average_grade, 1, '.', ''); ?>%</td>
                </tr>
                <tr>
                    <td>Average Grade Letter:&nbsp</td>
                    <td class="total"><?php print $average_grade_letter; ?></td>
                </tr>      
            </table>
        </div>
    <?php
    } ?>
    </small>
    <form id="transcriptItems" name="transcriptItems" method="POST" action="/course/transcript">
    <div style="clear:both">
        <small>
        <?php
        print $record_count . ' Course';
        print ($record_count != 1) ? 's' : '';
        ?>
        <span style="float:right">
            <input type="submit" class="button" value="Get Transcript" />
        </span>  
        </small>
    </div>
   
    <?php
    if ($record_count > 0)
    {
        $course_history_table->printTable();
    }
    ?>
    </form>
</div>
<script type="text/javascript" defer="defer">
jQuery(document).ready(function() {
    jQuery('#main-nav ul li a:contains(Users)').parent().addClass('selected');
});
</script>