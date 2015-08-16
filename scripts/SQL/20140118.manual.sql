// In super admin area, use SQL tool for:

update

<all moodles>

mdl_config_plugins set value = 'wrap,formatselect,wrap,bold,italic,wrap,bullist,numlist,wrap,link,unlink,wrap,image,gcrcloudstorage,media

undo,redo,wrap,underline,strikethrough,sub,sup,wrap,justifyleft,justifycenter,justifyright,wrap,outdent,indent,wrap,forecolor,backcolor,wrap,ltr,rtl,wrap,nonbreaking,charmap,table

fontselect,fontsizeselect,code,search,replace,wrap,cleanup,removeformat,pastetext,pasteword,wrap,fullscreen' where plugin = 'editor_tinymce' and "name" = 'customtoolbar'


update

<all moodles>

mdl_config_plugins set value = 'moodlemedia' where plugin = 'editor_tinymce' and "name" = 'disabledsubplugins'