<?php global $CFG; ?>
<table class="content">
    <tr>
        <td>
            <?php
            try
            {
                include_partial('creditsform', array('params' => $params));
            }
            catch (Exception $e)
            {
                $CFG->current_app->gcError('Error loading partial class: ' . $e->getMessage(), 'gcdatabaseerror');
            }
            ?>
        </td>
    </tr>
</table>