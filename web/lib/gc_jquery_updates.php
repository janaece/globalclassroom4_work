<?php
global $CFG;
$current_user = $CFG->current_app->getCurrentUser();
$user = $current_user->getUserOnInstitution();
if (!$user)
{
    $user = $current_user;
}
$availability_status = $user->getAvailabilityStatus();
$friends = $user->getFriends();
?>

<style type="text/css">
    #gc_inbox_message_dialog, #gc_open_chat_window_dialog { display:none;}
    #gc_invite_dialog select { width: 95%; }
    .gc_user_chat, .gc_view_inbox_message { cursor: pointer; }
    .gc_user_chat_profile { text-decoration:none; cursor:pointer; margin: 20px;}
</style>
<script type="text/javascript">
jQuery(function()
{
    var gc_user_friends = new Array();
    var gc_chat_sessions_with_pending_action = new Array();
    var gc_update_data = new Object();
    var gc_user_availability_status = '<?php print $availability_status->getObject()->short_name; ?>';

    function lockChatSession(id)
    {
        var d = new Date();
        if (id in gc_chat_sessions_with_pending_action)
        {
            if (gc_chat_sessions_with_pending_action[id] > d.getTime())
            {
                return false;
            }
        }
        gc_chat_sessions_with_pending_action[id] = d.getTime() + 60;
        return true;
    }
    function unlockChatSession(id)
    {
        gc_chat_sessions_with_pending_action.splice(id, 1);
    }


    function setNewStatus(status_id)
    {
        gc_user_avavilability_status = status_id;
        jQuery.post("<?php print $CFG->current_app->getUrl() . '/conference/setStatus'; ?>", {id: status_id});
    }


    function handleNewChatInvites()
    {
        var invite_div = jQuery('#gc_new_chat_invite');
        if (gc_update_data.chat_invites.length > 0)
        {
           
            var accept_button_html = '<a target="_blank" style="text-decoration:none" href="<?php print $CFG->current_app->getUrl() ?>/conference/view?user_id=' + gc_update_data.chat_invites[0].from_user_id + '"><button>Accept</button></a>';
            var decline_button_html = '<button class="gc_decline_chat_div" gc_chat_session_id="' + gc_update_data.chat_invites[0].id + '">Decline</button>';
            var html = gc_update_data.chat_invites[0].from_user_description_html + accept_button_html + ' ' + decline_button_html;
            jQuery.colorbox({html: html, height: '290px'});
        }
        else
        {
            jQuery.colorbox.close();             
        }
    }
    function processUpdate()
    {
        handleNewChatInvites()
        updateFriendStatuses();
        updateMessageCount();
        assignDynamicListeners();
    }
    function capitaliseFirstLetter(my_str)
    {
        if (my_str != undefined)
        {
            my_str = my_str.substring(0, 1).toUpperCase() + my_str.substring(1);
        }
        return my_str;
    }


    function updateMessageCount()
    {
        jQuery('.navcountunreadmessagecount').html(gc_update_data.message_count);
    }
    function updateFriendStatuses()
    {
        for (var i in gc_update_data.friends)
        {
            var list_item_selector = '#gc_friend_list_item_' + gc_update_data.friends[i].id;

            // Update the status icon
            var fullname = jQuery(list_item_selector + ' .gc_friend_fullname');
            fullname.attr('title', capitaliseFirstLetter(gc_update_data.friends[i].status));
            fullname.css('color', gc_update_data.friends[i].status_color);

            // update new message status
            var message_status = jQuery(list_item_selector + ' .gc_view_inbox_message');
            if (gc_update_data.friends[i].new_message != undefined)
            {
                if (message_status.attr('gc_status') == 'send')
                {
                    message_status.attr('gc_status', 'read');
                    message_status.attr('title', 'Read New Message From ' + gc_update_data.friends[i].full_name);
                    message_status.attr('gc_inbox_message_id', gc_update_data.friends[i].new_message);
                    message_status.attr('href', '');
                    message_status.html('<img class="gc_small_message_icon" src="<?php $CFG->current_app->getAppUrl() ?>/images/icons/email.gif" alt="">');
                }
            }
            else
            {
                if (message_status.attr('gc_status') == 'read')
                {
                    message_status.attr('gc_status', 'send');
                    message_status.attr('title', 'Send New Message To ' + gc_update_data.friends[i].full_name);
                    message_status.attr('gc_inbox_message_id', -1);
                    message_status.attr('href', '<?php $user->getApp()->getAppUrl() ?>user/sendmessage.php?id=' +
                        gc_update_data.friends[i].id + '&returnto=inbox');
                    message_status.html('<img class="gc_small_message_icon" src="<?php $CFG->current_app->getAppUrl() ?>/images/icons/icon-mail.gif" alt="">');
                }
            }

            // update chat icon status
            var chat_icon = jQuery('#gc_friend_list_item_' + gc_update_data.friends[i].id + ' .gc_chat_status_icon');
            if (gc_update_data.friends[i].status != 'offline')
            {
                var chat_element = jQuery('#gc_friend_list_item_' + gc_update_data.friends[i].id + ' .gc_user_chat');
                var session_id = getChatSessionWithFriend(i);
                chat_element.attr('gc_chat_session_id', session_id);     
                if (session_id == -1)
                {
                    chat_icon.attr('src', '<?php $CFG->current_app->getUrl() ?>/images/icons/gc-video-chat.jpeg');
                }
                else
                {
                    chat_icon.attr('src', '<?php $CFG->current_app->getUrl() ?>/images/icons/gc-video-chat-pending.jpeg');
                }
                chat_icon.show();
            }
            else
            {
                chat_icon.hide();
            }

            // Move list items to online or offline if they have switched
            var online_list_item = jQuery('#gc_online_friends_list #gc_friend_list_item_' + gc_update_data.friends[i].id);
            if (gc_update_data.friends[i].status == 'offline')
            {
                if (online_list_item.length > 0)
                {
                    jQuery('#gc_offline_friends_list').append(online_list_item);
                }
                var invite_dialog_option = jQuery('#gc_invite_friend_option_' + gc_update_data.friends[i].id);
                invite_dialog_option.attr('disabled', 'disabled');
                invite_dialog_option.removeAttr('selected');
            }
            var offline_list_item = jQuery('#gc_offline_friends_list #gc_friend_list_item_' + gc_update_data.friends[i].id);
            if (gc_update_data.friends[i].status != 'offline')
            {
                if (offline_list_item.length > 0)
                {
                    jQuery('#gc_online_friends_list').append(offline_list_item);
                }
                jQuery('#gc_invite_friend_option_' + gc_update_data.friends[i].id).removeAttr('disabled');
            }
        }
    }

    function getChatSessionWithFriend(index)
    {
        var session_id = -1;
        if (gc_update_data.friends[index].chat_session != undefined)
        {
            session_id = gc_update_data.friends[index].chat_session;
        }
        else if (gc_update_data.friends[index].chat_invite != undefined)
        {
            session_id = gc_update_data.friends[index].chat_invite;
        }
        else if (gc_update_data.friends[index].chat_invite_to != undefined)
        {
            session_id = gc_update_data.friends[index].chat_invite_to;
        }
        return session_id;
    }

    <?php
    if ($friends)
    {
        foreach ($friends as $friend)
        {
            print 'gc_user_friends[' . $friend->getObject()->id . '] = "' . $friend->getFullNameString() . '"; ';
        } 
    }
    ?>
    // IMPORTANT: any events bound in this function must first
    // be unbound, otherwise, numerous calls will be made to each
    // event handler.
    function assignDynamicListeners()
    {
        // Element to click and decline chat
        jQuery('.gc_decline_chat_div')
            .unbind('click.gc_decline_chat_div')
            .bind('click.gc_decline_chat_div', function()
            {
                jQuery.colorbox.close();
                obj = jQuery(this);
                session_id = obj.attr('gc_chat_session_id');
                jQuery.post("<?php print $CFG->current_app->getUrl() . '/conference/decline'; ?>", {id: session_id});
                jQuery('#gc_new_chat_invite').css('display', 'none');
            });
    }
    /* temp fix 1: Until updating becomes a more important feature, we will 
    * not waste the performance on it.
    jQuery(document).blur(function()
    {
        jQuery('#gc_smart_updater').smartupdaterStop();
    });
    jQuery(document).focus(function()
    {
        jQuery('#gc_smart_updater').smartupdaterRestart();
    });
    
    
    jQuery('#gc_smart_updater').smartupdater(
    {
        url: '<?php //print $CFG->current_app->getUrl() . '/conference/getUpdate'; ?>',
        minTimeout: <?php //print gcr::updatePollingMin; ?>,
        maxTimeout: <?php //print gcr::updatePollingMax; ?>,
        dataType: "json"
    },
    function (data)
    {
        gc_update_data = data;
        jQuery('#gc_smart_updater').smartupdaterSetTimeout(gc_update_data.next_poll_time);
        processUpdate();
        
    });
    */
    jQuery.get('<?php print $CFG->current_app->getUrl() . '/conference/getUpdate'; ?>', function(data) 
    {
        gc_update_data = data;
        processUpdate();
    });
    // end temp fix 1
    jQuery('.gc_set_user_availability').change(function()
    {
        status_id = jQuery(this).val();
        setNewStatus(status_id);
    });

    jQuery('.gc_open_chat_window').click(function()
    {
        if (gc_update_data.user_chat_sessions.length > 0)
        {
            return confirm("Opening a new video conferencing window will restart each of your existing video conferencing sessions, requiring you to reconfigure your video and audio settings.\n\nAre you sure you want to open a new video conferencing window?");
        }
    });
    jQuery('.gc_view_inbox_message').click(function()
    {
        var message_id = jQuery(this).attr('gc_inbox_message_id');
        if (message_id > 0)
        {
            var user_id = jQuery(this).attr('gc_inbox_message_from_user_id');
            jQuery.post("<?php print $CFG->current_app->getUrl() . '/conference/getInboxMessage'; ?>", {id: message_id , user: user_id }, function (inbox_message)
            {
                if (inbox_message.from_user != undefined)
                {
                    var from_user = inbox_message.from_user;
                }
                else
                {
                    var from_user = "<?php print $user->getApp()->getFullName() ?>";
                }
                jQuery('#gc_inbox_message_dialog_from_name').html(inbox_message.from_user);
                jQuery('#gc_inbox_message_dialog_date').html(inbox_message.date);
                jQuery('#gc_inbox_message_dialog_subject').html(inbox_message.subject);
                jQuery('#gc_inbox_message_dialog_text').html(inbox_message.content);
                jQuery("#gc_inbox_message_dialog").dialog
                ({
                    autoOpen: true,
                    height: 400,
                    width: 450,
                    modal: true,
                    resizable: false,
                    buttons:
                    {
                        'Reply': function()
                        {
                            jQuery(this).dialog('close');
                            document.location.href = '<?php print $user->getApp()->getAppUrl() ?>user/sendmessage.php?id=' + user_id + '&replyto=' + message_id + '&returnto=inbox';
                        },
                        'Close': function()
                        {
                            jQuery(this).dialog('close');
                        }
                    }
                });
            }, "json");
            return false;
        }
    });
});
</script>

<div id="gc_inbox_message_dialog" title="Inbox Message">
    <table>
    <tr><td><b>From:</b></td><td><span id="gc_inbox_message_dialog_from_name"></span></td></tr>
    <tr><td><b>Sent:</b></td><td><span id="gc_inbox_message_dialog_date"></span></td></tr>
    <tr><td><b>Subject:</b></td><td><span id="gc_inbox_message_dialog_subject"></span></td></tr>
    <tr><td><b>Message:</b><td> </td></tr>
    </table>
    <br />
    <div id="gc_inbox_message_dialog_text"></div>
</div>
<div id="gc_smart_updater"></div>