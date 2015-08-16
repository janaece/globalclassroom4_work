<?php
if ($eschool->isInternal())
{ ?>
    <style type="text/css">
        .externalEschoolField { display:none }
    </style>    
<?php
}
?>
<div class="gc_small_form"> 
    <table class="content" cellpadding="5" >
        <tr>
            <td colspan="2">
                <div>
                    <h1>Catalog Settings: <a href="<?php print $eschool->getAppUrl() ?>"><?php print $eschool->getFullName() ?></a></h1> 
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <?php include_partial('eschoolSettingsForm', array('eschoolSettingsForm' => $eschoolSettingsForm)); ?>
            </td>
            <td> &nbsp</td>
        </tr>
        <?php ($message) ? print '<tr><td colspan="2" style="color:red; font-weight:bold">' . $message . '</td></tr>' : print '';?>
    </table>
</div>