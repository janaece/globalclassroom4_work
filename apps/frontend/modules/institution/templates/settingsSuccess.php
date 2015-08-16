<style type="text/css">
    div.gc_small_form {margin-top: 25px;
                      margin-right: auto;
                      margin-bottom: auto;
                      margin-left: auto; }
</style>
<div id="left-column" class="sidebar">
<?php
    global $CFG;
    $current_user = $CFG->current_app->getCurrentUser();
    if ($current_user->getRoleManager()->hasPrivilege('GCUser'))
    {
        include_partial('global/gcadminsidebar');
    }
    else if ($current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
    {
        include_partial('global/eschooladminsidebar');
    }
    else
    {
        include_partial('global/usersidebar');
    }
?>
</div>
<div id="customize_dashboard">
    <div id="customize_box_color" class="main-column gc_small_form">
        <h3>Community Background Color</h3>
        <p>
            Select a custom background color for your Community using this Color Picker.
            Click the color box to expand the picker, click
            white space to close the picker.
        </p>
        <div id="color_picker_holder"><span id="color_selector"></span></div><br />
        <div class="preference_selection">
                <button onclick="saveColor();">Save Color</button><button onclick="syncColorToCatalogs();">Sync Color to Catalogs</button>
                <br />
                <img id="set_color_loader" src="/lib/leightbox/loader.gif" style="display: none" />
        </div>
    </div>
    <div id="customize_texture" class="main-column gc_small_form">
    <h3>Catalog Background Texture</h3>
    <p>
        Select a custom background texture for your Community.
    </p>
    Choose Texture:
    <select id="texture_select">
        <option value="brick-trans_bg.png">Brick</option>
        <option value="diamond-trans_bg.png">Diamond</option>
        <option value="fabric-trans_bg.png">Fabric</option>
        <option value="jitter-trans_bg.png">Jitter</option>
        <option value="thatch-trans_bg.png">Thatch</option>
        <option value="weave-trans_bg.png">Weave</option>
        <option value="none">None</option>
    </select>
    <br /><br />
    <div class="preference_selection">
        <button onclick="saveTexture();">Save Texture</button><button onclick="syncTextureToCatalogs();">Sync Texture to Catalogs</button>
        <br />
        <img id="texture_loader" src="/lib/leightbox/loader.gif" style="display: none" />
    </div>
    </div>
</div>
<?php 
    global $CFG;
    use_stylesheet('jpicker/jPicker.css');
    use_stylesheet('jpicker/jPicker-1.1.6.css');
?>
<script type="text/javascript" src="/js/jpicker/jpicker-1.1.6.js"></script>
<script type="text/javascript" src="/js/settings.js"></script>
<script type="text/javascript" defer="defer">
var texture = '<?php print $CFG->current_app->getConfigVar('gc_background_image'); ?>';
var bgcolor = '<?php print $CFG->current_app->getConfigVar('gc_background_color'); ?>';
jQuery(document).ready(function() 
{
    jQuery.getScript("/js/eschool/rightnav.js");
    jQuery('#main-nav ul li a:contains(Configure Site)').parent().addClass('selected');
});
</script>