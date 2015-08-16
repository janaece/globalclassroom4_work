<?php 
    use_stylesheet('jpicker/jPicker.css');
    use_stylesheet('jpicker/jPicker-1.1.6.css');
    global $CFG, $PAGE;
    $PAGE->set_url($CFG->httpswwwroot . '/eschool/settings');
    require_once($CFG->libdir.'/filelib.php');
?>
<style type="text/css">
    div.gc_small_form {margin-top: 25px;
                      margin-right: auto;
                      margin-bottom: auto;
                      margin-left: auto; }
</style>
<div id="left-column" class="sidebar">
<?php
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
        <h3>Catalog Background Color</h3>
        <p>
            Select a custom background color for your Catalog using this Color Picker.
            Click the color box to expand the picker, click
            white space to close the picker.
        </p>
        <div id="color_picker_holder"><span id="color_selector"></span></div><br />
        <div class="preference_selection">
            <button onclick="saveColor();">Set Color</button>
            <br />
            <img id="set_color_loader" src="/lib/leightbox/loader.gif" style="display: none" />
        </div>
    </div>
    <div id="customize_texture" class="main-column gc_small_form">
        <h3>Catalog Background Texture</h3>
        <p>
            Select a custom background texture for your Catalog.
        </p>
        <select id="texture_select">
            <option value="brick-trans_bg.png">Brick</option>
            <option value="diamond-trans_bg.png">Diamond</option>
            <option value="fabric-trans_bg.png">Fabric</option>
            <option value="jitter-trans_bg.png">Jitter</option>
            <option value="thatch-trans_bg.png">Thatch</option>
            <option value="weave-trans_bg.png">Weave</option>
            <option value="none">None</option>
        </select>
        <div class="preference_selection">
            <br />
            <button onclick="saveTexture();">Set Texture</button>
            <br />
            <img id="texture_loader" src="/lib/leightbox/loader.gif" style="display: none" />
        </div>
    </div>
    <div id="customize_box_languages" class="main-column gc_small_form">
            <h3>Catalog Languages</h3>
            <p>
                Select a default language.  If a language isn't listed, install that language below.
            </p>
            <div class="preference_selection">

            <?php
                echo '<select name="installed_packs" id="installed_packs">';
                $mdlconfig_lang = $CFG->current_app->getConfigVar('lang');
                print '<script type="text/javascript">var installed_lang = "' . $mdlconfig_lang . '"</script>';
                $installedlangs = get_list_of_languages(true, true);
                foreach ($installedlangs as $value => $output)
                {
                    if ($mdlconfig_lang != $value)
                    {
                        echo '<option id="'.$value.'" value="'.$value.'">'.$output.'</option>';
                    }
                    else
                    {
                        echo '<option id="'.$value.'" value="'.$value.'" selected>'.$output.'</option>';
                    }
                }
                echo "</select>";
                echo '<input class="button" type="button" id="default_language" onclick="set_default_language()" value="Set" />';
            ?>
            <br />
            <img id="default_lang_loader" src="/lib/leightbox/loader.gif" style="display: none" />
            </div>
            <p>
                    Select a language to install.
            </p>
            <div class="preference_selection">
            <?php
        //attempting to manually do language pack administration, set up code is from /admin/langimport.php
        //the ajax calls langimport.php to install the language pack

        if ($availablelangs = $CFG->current_app->getRemoteListOfLanguages())
        {
            $remote = 1;
        }
        else
        {
            $remote = 0;    //flag for reading from remote or local
            $availablelangs = '';
        }
        if ($remote)
        {
            echo '<input name="sesskey" id="sesskey" type="hidden" value="'.sesskey().'" />';
            echo '<select name="pack[]" id="pack">';
        }

        foreach ($availablelangs as $alang)
        {
            if ($alang[0] == '')
            {
                continue;
            }
            if (trim($alang[0]) != "en")
            {
                if ($remote)
                {
                $shortlang = $alang[0];

                    if (!$CFG->current_app->isInstalledLang($alang[0], $alang[1]))
                    {    //if not already installed
                        echo '<option id="install_'.$alang[0].'" value="'.$alang[0].'">'.$alang[2].' ('.$shortlang.')</option>';
                    }
                }
                else
                {    //print list in local format, and instruction to install
                    echo "<p>There was a problem obtaining the proper languages.  Please contact Global Classroom for assistance.</p>";
                    break;
                }
                $empty = 0;
            }
        }
        if ($remote)
        {
            echo '</select>';
            echo '<input class="button" type="button" onclick="install_language();" value="Install" />';
        }
            ?>
            <br />
            <img id="install_lang_loader" src="/lib/leightbox/loader.gif" style="display: none" />
            </div>
    <div class="preference-selection">
        <p>
            Select a language to delete.
        </p>
        <?php
            echo '<input name="sesskey" id="sesskey" type="hidden" value="'.sesskey().'" />';
            echo '<select name="pack_to_delete[]" id="pack_to_delete">';
            foreach ($installedlangs as $value => $output)
            {
                if($value != "en")
                {
                    echo '<option id="delete_'.$value.'" value="'.$value.'">'.$output.'</option>';
                }
            }
            echo "</select>";
            echo '<input class="button" type="button" id="default_language" onclick="delete_language();" value="Delete" />';
        ?>
        <br/>
        <img id="delete_lang_loader" src="/lib/leightbox/loader.gif" style="display: none" />
    </div>
    </div>
    <br /><br />
    <div class="continue">
            <?php print button_to('Catalog Home', $CFG->wwwroot, array('class' => 'button')); ?>
    </div>
    <div id="gc_input"></div><br />
</div>
<script type="text/javascript" src="/js/jpicker/jpicker-1.1.6.js"></script>
<script type="text/javascript" src="/js/settings.js"></script>
<script type="text/javascript">
var texture = '<?php print $CFG->current_app->getConfigVar('gc_background_image'); ?>';
var bgcolor = '<?php print $CFG->current_app->getConfigVar('gc_background_color'); ?>';
</script>