function install_language()
{
    var langpack = jQuery('#pack').val();
    var langoutput = jQuery('#pack :selected').text();
    var sesskey = jQuery('#sesskey').val();
    jQuery("#install_lang_loader").css("display", "");
    jQuery.ajax(
    {
        type: "POST",
        url: "installLangPack",
        data: "pack="+langpack,
        success: function()
        {
            jQuery("#install_lang_loader").css("display", "none");
            alert("Language pack installed");
            jQuery('#install_'+langpack).remove();
            jQuery('<option id="'+langpack+'" value="'+langpack+'">'+langoutput+'</option>').appendTo(jQuery('#installed_packs'));
            jQuery('<option id="delete_'+langpack+'" value="'+langpack+'">'+langoutput+'</option>').appendTo(jQuery('#pack_to_delete'));
        }
    });
}

function delete_language()
{
    var langpack = jQuery('#pack_to_delete :selected').val();
    if(installed_lang != langpack)
    {
        var langoutput = jQuery('#pack_to_delete :selected').text();
        var sesskey = jQuery('#sesskey').val();
        jQuery("#delete_lang_loader").css("display", "");
        jQuery.ajax(
        {
            type: "POST",
            url: "deleteLangPack",
            data: "uninstalllang="+langpack,
            success: function()
            {
                jQuery("#delete_lang_loader").css("display", "none");
                alert("Language pack deleted");
                jQuery('#'+langpack).remove();
                jQuery('#delete_'+langpack).remove();
                jQuery('<option id="install_'+langpack+'" value="'+langpack+'">'+langoutput+'</option>').appendTo(jQuery('#pack'));
            }
        });
    }
    else
    {
        alert("Cannot delete the default language");
    }
}

function set_default_language()
{
    var lang = jQuery('#installed_packs').val();
    var langoutput = jQuery('#installed_packs :selected').text();
    jQuery("#default_lang_loader").css("display", "");
    jQuery.ajax(
    {
        type: "POST",
        url: "setConfigVar",
        data: "lang="+lang,
        success: function()
        {
            jQuery("#default_lang_loader").css("display", "none");
            installed_lang = langoutput;
            alert(langoutput+" set as default language");
        }
    });
}

function saveTexture()
{
    var texture = jQuery('#texture_select :selected').val();
    var texture_output = jQuery('#texture_select :selected').text();
    jQuery("#texture_loader").css("display", "");
    jQuery.ajax(
    {
        type: "POST",
        url: "setConfigVar",
        data: "texture="+texture,
        success: function()
        {
            jQuery("#texture_loader").css("display", "none");
            alert(texture_output+" set as the background texture");
            if(texture != 'none')
            {
                jQuery('body').css('background-image', 'url("../../images/'+texture+'")');
            }
            else
            {
                jQuery('body').css('background-image', 'none');
            }
        }
    });
}

function syncTextureToCatalogs()
{
    var texture = jQuery('#texture_select :selected').val();
    var texture_output = jQuery('#texture_select :selected').text();
    jQuery("#texture_loader").css("display", "");
    jQuery.ajax(
    {
        type: "POST",
        url: "setConfigVar",
        data: "texture="+texture,
    });
    if(texture != 'none')
    {
        jQuery('body').css('background-image', 'url("../../images/'+texture+'")');
    }
    else
    {
        jQuery('body').css('background-image', 'none');
    }
    jQuery.ajax(
    {
        type: "POST",
        url: "setCatalogConfigVar",
        data: "texture="+texture,
        success:function()
        {
            jQuery("#texture_loader").css("display", "none");
            alert('Texture synced to all Catalogs');
        }
    });
}

function saveColor()
{
    //use ajax to call a script to perform the action of saving the value in a text document
    var color = RGBtoHEX(jQuery('body').css('background-color'));
    
    jQuery("#set_color_loader").css("display", "");
    jQuery.ajax(
    {
        type: "POST",
        url: "setConfigVar",
        data: "color="+color,
        success: function()
        {
            jQuery("#set_color_loader").css("display", "none");
            alert("Background color set to "+color);
        }
    });
}

function syncColorToCatalogs()
{
    var color = RGBtoHEX(jQuery('body').css('background-color'));
    jQuery("#set_color_loader").css("display", "");
    jQuery.ajax(
    {
        type: "POST",
        url: "setConfigVar",
        data: "color="+color,
    });
    jQuery.ajax(
    {
        type: "POST",
        url: "setCatalogConfigVar",
        data: "color="+color,
        success: function()
        {
            jQuery("#set_color_loader").css("display", "none");
            alert("Background color synced to all Catalogs");
        }
    });
}

function toHex(N) 
{
    if (N==null) return "00";
    N=parseInt(N); if (N==0 || isNaN(N)) return "00";
    N=Math.max(0,N); N=Math.min(N,255); N=Math.round(N);
    return "0123456789ABCDEF".charAt((N-N%16)/16) + "0123456789ABCDEF".charAt(N%16);
}

function RGBtoHEX(str)
{
    //check that string starts with 'rgb'
    if (str.substring(0, 3) == 'rgb')
    {
        var arr = str.split(",");
        var r = arr[0].replace('rgb(','').trim(), g = arr[1].trim(), b = arr[2].replace(')','').trim();
        var hex = [toHex(r), toHex(g), toHex(b)];
        return "#" + hex.join('');
    }
    else
    {
        //string not rgb so return original string unchanged
        return str;
    }
}
jQuery(document).ready(function() {
    
    jQuery('#texture_select option').each(function() {
        if(jQuery(this).val() == texture)
        {
            jQuery(this).attr('selected', 'selected');
        }
    });

    jQuery('#color_selector').jPicker(
    {
        window:
        {
            title: 'Select a New Background Color',
            expandable: true,
            effects:
            {
                type: 'fade'
            },
            position:
            {
                x: 'screenCenter', // acceptable values "left", "center", "right", "screenCenter", or relative px value
                y: 'center' // acceptable values "top", "bottom", "center", or relative px value
            }
        },
        color:
        {
            active: new jQuery.jPicker.Color({ hex: bgcolor})
        },
        images:
        {
            clientPath: '/js/jpicker/images/' // Path to image files
        }
    }, 
    function(color, context)
    {
        var hex = color.val('hex');
        jQuery('body').css(
        {
            backgroundColor: hex && '#' + hex || 'transparent'
        }); // prevent IE from throwing exception if hex is empty
    });
});