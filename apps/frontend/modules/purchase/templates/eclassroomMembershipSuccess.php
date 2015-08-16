<?php global $CFG; ?>
<div class="gc_small_form">
    <h3>Please choose one of the following membership options for <?php print $CFG->current_app->getFullName(); ?>:</h3>
    <form id="eClassroom" name="eClassroom" method="POST" action="<?php print $CFG->current_app->getUrl(); ?>/purchase/classroomPurchase">
        <table class="gc_small_form">
            <th>Learning Institution</th><th>Pricing Options</th>
            <?php
            $options = array();
            $selected = true;
            foreach ($eschools as $eschool)
            {
                $row1 = true;
                print '<tr><td><b>' . $eschool->getFullName() . '</b></td><td>';
                $options['Month'] = $eschool->getConfigVar('gc_classroom_cost_month');
                $options['Year'] = $eschool->getConfigVar('gc_classroom_cost_year');
                foreach ($options as $key => $value)
                {
                    if (!$row1)
                    {
                        print '<br />';
                    }
                    print '<input type="radio" name="eschool_id" ';
                    if ($selected)
                    {
                        print 'checked="checked" ';
                        $selected = false;
                    }
                    print 'value="' . $eschool->getShortName() .'#' . $key . '">';
                    print GcrPurchaseTable::gc_format_money($value) . '/' . $key . '</input>';
                    $row1 = false;
                }
                print '</td></tr>';
            }
            ?>
        </table>
        <br /><br /><input type="submit" class="button" value="Continue" />
    </form>
</div>