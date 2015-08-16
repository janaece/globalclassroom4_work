<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php use_stylesheet('reset.css');?>
    <?php use_stylesheet('header.css'); ?>
    <?php use_stylesheet('mainstyles.css'); ?>
    <?php use_javascript('header.js'); ?>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
</head>
<body>
    <?php include_partial("public/header"); ?>
    <div class="page_title">
	<h3>eSchool Learning Management System</h3>
    </div>
    <table class="eschool_solutions_table">
        <tr>
            <td>
		<div class="main_content">
                    <?php include_slot('banner'); ?>
                    <?php include_slot('main_content'); ?>
		</div>
            </td>
            <td class="pricing_container">
		<?php include_slot('pricing'); ?>
                <br/>
		<?php include_slot('pricing_notes'); ?>
            </td>
	</tr>
    </table>
    <?php echo $sf_content ?>
    <?php include_partial("public/footer"); ?>
    <script type="text/javascript" language="javascript">
        var hs_portalid=108225;
        var hs_salog_version = "2.00";
        var hs_ppa = "globalclassroom.app10.hubspot.com";
        document.write(unescape("%3Cscript src='" + document.location.protocol + "//" + hs_ppa + "/salog.js.aspx' type='text/javascript'%3E%3C/script%3E"));
    </script>
</body>
</html>