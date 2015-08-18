<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php use_stylesheet('stratus.css');?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php global $CFG, $USER; ?>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?> 
    <!-- IE PNG Fix -->
    <!--[if lt IE 7]>
        <style type="text/css">
            img, a, input
            {
                behavior: url("/lib/iepngfix/iepngfix.htc")
            }

            body
            {
                background-image: url("pix/transparent_bg.gif");
            }
        </style>
    <![endif]-->
    <script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/js/html5shiv.js"></script>
    <script type="text/javascript">jQuery.noConflict();</script>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
</head>
<body>
    <?php
    if($CFG->current_app->isMoodle())
    {
        $app = $CFG->current_app->getInstitution();
    }
    else
    {
        $app = $CFG->current_app;
        if($USER->get('parentuser'))
        {
            echo '<div class="sitemessage">';
            echo '<img src="../images/icon_problem.gif" alt="">';
            echo 'You are masquerading as '. $USER->get('firstname') . ' ' . $USER->get('lastname') . '. ';
            echo '<a href="' . $app->getAppUrl() . 'admin/users/changeuser.php?restore=1">Become '. $USER->get('parentuser')->name . ' again</a>';
            echo '</div>';
        }
    }
    ?>
    <?php $CFG->current_app->printHeader(); ?>
    <?php /* <div id="messages"><?php echo $CFG->current_app->getConfigVar('gc_eschool_message'); ?></div> */ ?>
    <?php echo $sf_content ?>
    <?php $CFG->current_app->printFooter(); ?>

    <!-- Print all JavaScript code at the bottom of the body -->
    <?php $CFG->current_app->includeGCJQueryLib(); ?>
    <script type="text/javascript">
        jQuery(document).ready( function ()
        {
            jQuery('body').css('background-color', '<?php echo $CFG->current_app->getConfigVar('gc_background_color'); ?>');
            if('<?php echo $CFG->current_app->getConfigVar('gc_background_image'); ?>' != 'none')
            {
                jQuery('body').css('background-image', 'url(<?php print '/images/' . $CFG->current_app->getConfigVar('gc_background_image'); ?>)');
            }
            jQuery('#site-logo img').hide();
            jQuery('#site-logo img').delay('1500').fadeIn('slow');
        });
        jQuery.getScript("/js/eschool/rightnav.js");
    </script>
    <script type="text/javascript" language="javascript">
        var hs_portalid=108225;
        var hs_salog_version = "2.00";
        var hs_ppa = "globalclassroom.app10.hubspot.com";
        document.write(unescape("%3Cscript src='" + document.location.protocol + "//" + hs_ppa + "/salog.js.aspx' type='text/javascript'%3E%3C/script%3E"));
    </script>
    </body>
</html>