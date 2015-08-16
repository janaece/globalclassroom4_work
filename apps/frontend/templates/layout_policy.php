<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <style type="text/css">
        body
        {
            background-color: #FFF;
        }
    </style>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>    
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
</head>
<body>
    <div id="gc_policy">
   	<?php echo $sf_content ?>
    </div>
    <?php include_slot('user_policy') ?>
    <script type="text/javascript">
        // a check if policy.html is included in the page, otherwise display our site policy in full
        if(jQuery("#user_policy").length != 0)
        {
                jQuery("#gc_policy").width("49%");
                jQuery("#gc_policy").css("float", "left");
        };
    </script>
    <script type="text/javascript" language="javascript">
        var hs_portalid=108225;
        var hs_salog_version = "2.00";
        var hs_ppa = "globalclassroom.app10.hubspot.com";
        document.write(unescape("%3Cscript src='" + document.location.protocol + "//" + hs_ppa + "/salog.js.aspx' type='text/javascript'%3E%3C/script%3E"));
    </script>
</body>
</html>