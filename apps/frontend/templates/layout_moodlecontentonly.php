<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php global $CFG; ?>
    <style type="text/css">
        body
	{
            background-color: <?php echo $CFG->current_app->getConfigVar('gc_background_color'); ?>;
	}
    </style>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?> 
    <!-- IE PNG Fix -->
    <!--[if lt IE 7]>
	<style type="text/css">
            img, a, input
            {
		behavior: url("<?php //print $CFG->symfonyroot ?>/lib/iepngfix/iepngfix.htc")
            }
	
            body
            {
		background-image: url("pix/transparent_bg.gif");
            }
	</style>
    <![endif]-->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
</head>
<body>
    <?php echo $sf_content ?>
    <script type="text/javascript" language="javascript">
        var hs_portalid=108225;
        var hs_salog_version = "2.00";
        var hs_ppa = "globalclassroom.app10.hubspot.com";
        document.write(unescape("%3Cscript src='" + document.location.protocol + "//" + hs_ppa + "/salog.js.aspx' type='text/javascript'%3E%3C/script%3E"));
    </script>
</body>
</html>