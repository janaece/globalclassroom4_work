<?php
global $CFG;
$current_user = $CFG->current_app->getCurrentUser();
$enrol_count = count($current_user->getEnrolments());
$course_creator = $CFG->current_app->hasPrivilege('EclassroomUser');
?>
<link rel="stylesheet" href="/js/masonry/css/style.css" />
<link rel="stylesheet" href="/css/course_view.css" />
<link rel="stylesheet" href="/css/course_detail.css" />
<section id="content">
<div id="gc_course_list">
    <div id="gc_course_list_settings" style="margin: 0 70px">
        <div id="gc_course_list_title"><h1>Courses</h1></div>
        <div id="gc_my_course_toggle" <?php print ($CFG->current_app->hasPrivilege('Student')) ? '' : ' style="display:none"';?>>
            <h3>
            <input id="gc_search_courses" type="radio" name="gc_my_course_toggle" checked="checked" value="search" /><label for="gc_search_courses">Search Courses</label>
            <input id="gc_my_courses" type="radio" name="gc_my_course_toggle"
                <?php print ($params['mode'] == 'Student') ? ' checked="checked"' : ''; ?> value="my" /><label for="gc_my_courses">My Courses</label>
            <?php
            if ($course_creator)
            {
                ?>
                <input id="gc_create_course_button" type="radio" name="gc_my_course_toggle" value="create" /><label for="gc_create_course_button">Create New Course</label>
                <?php
            } ?>
            </h3>
        </div>
        <div class="clearfix"></div>

            <div id="gc_course_catalog_selector" class="gc_course_list_filter">
                Catalog:<br/> 
                <select id="gc_course_catalogs">
                    <option value="1">
                        All Course Catalogs
                    </option>
                    <?php
                    $eschool_array = [];
                    foreach($CFG->current_app->getMnetEschools() as $eschool)
                    {
                        if (GcrEschoolTable::authorizeEschoolAccess($eschool, true))
                        {
                            $eschool_array[$eschool->getFullName()] = $eschool;
                        }
                    } 
                    ksort($eschool_array);
                    foreach($eschool_array as $eschool) 
                    {
                        print '<option';
                        print ($params['catalog'] == $eschool->getShortName()) ? ' selected="selected"' : '';
                        print ' value="' . $eschool->getShortName() . '">' . $eschool->getFullName() . '</option>';
                    }
                    ?>
                </select>
                <br />
                <select id="gc_course_categories">
                    <option value="All">All Categories</option>
                </select>
            </div>
            <div id="gc_search_string" class="gc_course_list_filter">Keyword:<br /><input type="text" id="gc_course_list_search_string" value="" /></div>
            </br >
            <button class="gc_course_list_filter" id="gc_reload">Search</button>

        <small>
         <div id="gc_course_visibility_selector"<?php print (!$course_creator) ? ' style="display:none"' : ''; ?>>
            <input id="gc_visible_courses" type="radio" name="gc_course_visibility_selector" checked="checked" value="visible" /><label for="gc_visible_courses">Active Courses</label>
            <input id="gc_hidden_courses" type="radio" name="gc_course_visibility_selector" 
                <?php print ($params['archived'] == '1') ? 'checked="checked"' : ''; ?> value="hidden" /><label for="gc_hidden_courses">Archived Courses</label>
        </div>
        </small>
        <input id="gc_course_id" type="hidden" value="<?php print ($course) ? $course->getObject()->id : ''; ?>">
    </div>
    <div id="gc_course_list_container" class="transitions-enabled infinite-scroll clearfix centered"></div>
    
    <nav id="page-nav">
      <a href="/course/getHTMLCourses?page=2"></a>
    </nav>

    <script src="/js/masonry/jquery.masonry.min.js"></script>
    <script src="/js/masonry/js/jquery.infinitescroll.js"></script>
    <script src="/js/course_view.js"></script>

<script>
jQuery(function()
{
    var $container = jQuery('#gc_course_list_container');
    var category_array = [];
    
    <?php
    GcrJavascriptBlockFactory::printBlock('CourseCategoryArray');
    ?>
            
    jQuery('#gc_course_visibility_selector').css('visibility', 'visible');
    
    jQuery("input[name='gc_course_visibility_selector']").change(function()
    {
        reloadCourseList();
    });
    
    jQuery('#gc_my_course_toggle').change(function ()
    {
        var my_course_toggle = jQuery('input:radio[name=gc_my_course_toggle]:checked').val();
        if (my_course_toggle == 'create')
        {
            document.location.href = '<?php print $CFG->current_app->getAppUrl(); ?>artefact/courses/create.php';
        }
        else
        {
            if (my_course_toggle == 'my')
            {
                jQuery('#gc_course_catalog_selector').hide();
            }

            else
            {
                jQuery('#gc_course_catalog_selector').show();
            }

            // reset catalog selector to "all catalogs"
            jQuery('#gc_course_catalogs').val('1');

            // Reset visibility toogle to active courses
            jQuery("input[name=gc_course_visibility_selector][value=visible]").attr('checked', 'checked');
            jQuery("#gc_course_visibility_selector").buttonset("refresh");

            resetCategoryList();
            reloadCourseList();
        }
    });        
    jQuery('#gc_course_catalogs').change(function ()
    {
        resetCategoryList();
        reloadCourseList();
    });
    jQuery('#gc_course_categories').change(function ()
    {
        reloadCourseList();
    });
    jQuery("#gc_course_visibility_selector, #gc_my_course_toggle").buttonset();

    jQuery('#gc_reload').click(function()
    {
         reloadCourseList();
    });
    
    function resetCategoryList()
    {
        var index = jQuery('#gc_course_catalogs option:selected').val();
        var category_selector = jQuery('#gc_course_categories');
        if (index == '1')
        {
            category_selector.hide();
        }
        else
        {
            category_selector.children().remove();
            category_selector.append('<option value="All">All Categories</option>');
            for (var i in category_array[index])
            {
                category_selector.show();
                category_selector.append('<option value="' + 
                    i + '">' + category_array[index][i] + '</option>');
            }
            
        }
    }
    
    function reloadCourseList()
    {
        $gc_course_viewer.start_index = 0;
        $container.masonry('destroy');
        $container.html('');
        $container.masonry(
        {
            itemSelector: '.gc_course_list_item',
            columnWidth: 230,
            gutterWidth: 20,
            isFitWidth: true,
            containerStyle: { position: 'relative' }
        });
        $container.infinitescroll('bind');
        $container.infinitescroll('reset');
        $container.infinitescroll('retrieve');
    }
    
    $container.imagesLoaded(function()
    {
        $container.masonry(
        {
            itemSelector: '.gc_course_list_item',
            columnWidth: function( containerWidth ) {
                return containerWidth / 3;
              },
            gutterWidth: 0,
            isFitWidth: true,
            containerStyle: { position: 'relative' }
        });
        jQuery('#gc_course_list_container').css('visibility', 'visible');
    });

    $container.infinitescroll(
    {
        navSelector  : '#page-nav',    // selector for the paged navigation 
        nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
        itemSelector : '.gc_course_list_item',     // selector for all items you'll retrieve
        autofill : true,
        loading: 
        {
            finishedMsg: 'No more courses to load.',
            msgText: 'Loading courses...',
            img: '<?php print $CFG->current_app->getUrl(); ?>/images/throbber.gif'
        }
    },
    // trigger Masonry as a callback
    function( newElements ) 
    {
        // hide new items while they are loading
        var $newElems = jQuery( newElements ).css({ opacity: 0 });
        $gc_course_viewer.start_index = jQuery(newElements).find('span').attr('end_index');
        // ensure that images load before adding to masonry layout
        $newElems.imagesLoaded(function()
        {
            // show elems now they're ready
            $newElems.animate({ opacity: 1 });
            $container.masonry( 'appended', $newElems, false );
            $gc_course_viewer.addNewCourseListItemsEventListeners();
        });
    });
    reloadCourseList();
});
</script>
</div>
</section> <!-- #content -->

