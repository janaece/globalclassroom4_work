<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php global $CFG; ?>
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
    <script type="text/javascript">jQuery.noConflict();</script>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
</head>
<body>
    <?php echo $sf_content ?>
    <!-- Print all JavaScript code at the bottom of the body -->
    <script type="text/javascript" language="javascript">
        var hs_portalid=108225;
        var hs_salog_version = "2.00";
        var hs_ppa = "globalclassroom.app10.hubspot.com";
        document.write(unescape("%3Cscript src='" + document.location.protocol + "//" + hs_ppa + "/salog.js.aspx' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        jQuery('body').css('background-color', '<?php echo $CFG->current_app->getConfigVar('gc_background_color'); ?>');
        if('<?php echo $CFG->current_app->getConfigVar('gc_background_image'); ?>' != 'none')
        {
            jQuery('body').css('background-image', 'url(<?php echo $CFG->current_app->getUrl() . '/images/' . $CFG->current_app->getConfigVar('gc_background_image'); ?>)');
        }
    </script>
</body>
</html>