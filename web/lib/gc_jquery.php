<?php
global $CFG;
?>
<script type="text/javascript" src="/js/jquery-migrate-1.0.0.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.10.3.custom.min.js"></script>
<style type="text/css">@import "/css/smoothness/jquery-ui-1.10.3.custom.min.css";</style>
<script type="text/javascript" src="/js/jquery.colorbox-min.js"></script>
<style type="text/css">@import "/css/colorbox/colorbox.css";</style>
<script type="text/javascript" src="/js/smartupdater-3.1.00.js"></script>
<script type="text/javascript" src="/js/mediaelement-and-player.min.js"></script>
<style type="text/css">@import "/css/mediaelementjs/mediaelementplayer.css";</style>
        
<script type="text/javascript">
    var gc_current_app_id = '<?php print $CFG->current_app->getShortName(); ?>';
    function gcrGetAppUrl(app_id, is_eschool)
    {
        var domain = (is_eschool) ? '<?php print gcr::moodleDomain ?>' : '<?php print gcr::maharaDomain ?>';
        return 'https://' + app_id + '.' + domain;
    }
    function gcrLoadMediaelementjs()
    {
        jQuery('video,audio').each(function(i){
            var file_url = '';
            var sources = jQuery(this).find('source');
            sources.each(function(j)
            {
                file_url = jQuery(this).attr('src');
                file_url = file_url.replace(/\+/g, '%20'); // + must be changed to %20 to pass security test in mediaelementjs
                jQuery(this).attr('src', file_url);
            });
            var width = jQuery(this).attr('width');
            var height = jQuery(this).attr('height');
            if (width == undefined)
            {
                width = '350';
            }
            if (height == undefined)
            {
                height = '250';
            }
            file_url = file_url.replace(/&/g, '%26'); // & must be url encoded for flashvars
            jQuery(this).append('<object width="' + width + '" height="' + height + '" type="application/x-shockwave-flash" data="https://' 
                + document.domain + '/js/mediaelementjs/flashmediaelement.swf"><param name="allowFullScreen" value="true" />'
                + '<param name="movie" value="https://' + document.domain + '/js/mediaelementjs/flashmediaelement.swf" />'
                + '<param name="flashvars" value="controls=true&file=' + file_url + '" /></object>');
        }); 
        
        // Load all mediaelementjs videos
        jQuery('video,audio').mediaelementplayer({
                /*@cc_on
                @if (@_jscript_version >= 9)
                            mode: 'shim',
                @end
                @*/
                <?php 
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
                {
                    print 'mode: \'shim\',';
                } ?>
                pluginPath: '<?php print $CFG->current_app->getUrl(); ?>/js/mediaelementjs/',
                plugins: ['flash','silverlight'],
                // name of flash file
                flashName: 'flashmediaelement.swf',
                // name of silverlight file
                silverlightName: 'silverlightmediaelement.xap'        
            });
    }
    jQuery('.gc-collapsable-header').click(function ()
    {
        jQuery(this).next().toggle("slow");
        return false;
    });
    jQuery(".gc_buttonset").buttonset();
    gcrLoadMediaelementjs();
    
    // Inject any javascript code to overwrite default moodle/mahara application content
    <?php print GcrJavascriptInjections::getAll(); ?>
</script>