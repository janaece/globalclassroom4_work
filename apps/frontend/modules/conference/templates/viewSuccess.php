<?php
global $CFG;
$availability_status = $user->getAvailabilityStatus();
$friends = $user->getFriends();
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<script type="text/javascript" src="/js/jquery.periodicalupdater.js"></script>
<script type="text/javascript" src="/js/jquery.titlealert.min.js"></script>
<script src="<?php print API_Config::API_URL; ?>/v1.1/js/TB.min.js" type="text/javascript" charset="utf-8"></script>

<style type="text/css">
    #gc_invite_dialog, #gc_confirm_dialog { display:none;}
    #gc_invite_dialog select { width: 95%; }
    #gc_chat_toolbar { float:left; cursor:pointer; position:fixed; top:0px; left:0px;}  
</style>
<script type="text/javascript">
    var session_id = '<?php print $session_id ?>';
    var token = '<?php print $token ?>';
    var gc_chat_session_id = '<?php print $gc_chat_session_id; ?>';
    
    var session = TB.initSession(session_id);
    session.addEventListener('sessionConnected', sessionConnectedHandler);
    session.addEventListener('streamCreated', streamCreatedHandler);
    session.addEventListener('streamDestroyed', streamDestroyedHandler);
    
    session.connect('<?php print API_Config::API_KEY ?>', token);
 
    var publisher;
    var subscribers = new Array();
    var subscriber_count = 0;
    
    function sessionConnectedHandler(event) 
    {
        var publisherProps = {width: 100, height: 80};
        publisher = TB.initPublisher('<?php print API_Config::API_KEY ?>', 'myPublisherDiv', publisherProps);
        session.publish(publisher);
        var publisherObject = document.getElementById(publisher.id);
        publisherObject.style.position = 'fixed';
        publisherObject.style.right = '5px';
        publisherObject.style.top = '5px';
        publisherObject.title = '<?php print $user->getFullnameString(); ?>';
        // Subscribe to streams that were in the session when we connected
        subscribeToStreams(event.streams);
    }
    function streamDestroyedHandler(event) 
    {
        setScreenLayout(--subscriber_count);
        if (subscriber_count < 1)
        {
            jQuery.post("<?php print $CFG->current_app->getUrl() . '/conference/delete'; ?>", {id: gc_chat_session_id });
            closeChatDialog();
        }
    }
     
    function closeChatDialog()
    {
        jQuery( "#gc_chat_closed" ).dialog({
            height: 200,
            modal: true,
            buttons: 
            {
                'Close Window': function () 
                {
                    window.close();
                }
            }
        });
    }
    function streamCreatedHandler(event) 
    {
        // The layout can't handle more than 16 windows currently
        if (subscriber_count < 16)
        {
            // Subscribe to any new streams that are created
            subscribeToStreams(event.streams);
        }
    }
   
   function getConnectionData(connection) {
    try {
        connectionData = JSON.parse(connection.data);
    } catch(error) {
        connectionData = eval("(" + connection.data + ")" );
    }
    return connectionData;
}
     
    function subscribeToStreams(streams) 
    {
        var subscriber;
        for (var i = 0; i < streams.length; i++) 
        {
            // Make sure we don't subscribe to ourself
            if (streams[i].connection.connectionId == session.connection.connectionId)
            {
                return;
            }
            var connectionData = getConnectionData(streams[i].connection);
            var subscriberProps = setScreenLayout(++subscriber_count);
            // Create the div to put the subscriber element in to
            var outerDiv = document.createElement('div');
            outerDiv.setAttribute('title', connectionData.username);
            var div = document.createElement('div');
            div.setAttribute('id', 'stream' + streams[i].streamId);
            outerDiv.appendChild(div)
            document.body.appendChild(outerDiv);
            // Subscribe to the stream
            subscriber = session.subscribe(streams[i], div.id, subscriberProps); 
            subscribers[subscriber.id] = document.getElementById(subscriber.id);
            jQuery('#gc_chat_status_waiting').hide();
        }
    }


    function setScreenLayout(countChat)
    {
        var windowHeight = jQuery(window).height() * .93;
        var windowWidth = jQuery(window).width() * .97;
        var marginRight = 100;
        if (countChat > 1 && countChat <= 4)
        {
            windowHeight *= .5;
            windowWidth *= .5;
            marginRight *= .5;
        }
        else if (countChat > 4 && countChat <= 9)
        {
            windowHeight *= .33;
            windowWidth *= .33;
            marginRight *= .33;
        }
        else if (countChat > 9 && countChat <= 16)
        {
            windowHeight *= .25;
            windowWidth *= .25;
            marginRight *= .25;
        }
        windowWidth = Math.floor(windowWidth - marginRight);
        windowHeight = Math.floor(windowHeight);
        for (var index in subscribers)
        {
            subscribers[index].setAttribute('height', windowHeight);
            subscribers[index].setAttribute('width', windowWidth);
        }
        return {width: windowWidth, height: windowHeight};
    }
    
    jQuery(function()
    {
        jQuery(window).resize(function() 
        {
            setScreenLayout(subscriber_count);
        });
        // Element to click and invite users to chat
        jQuery('.gc_user_invite_button').bind('click.gc_user_invite_button', function()
        {
            inviteUserDialog();
        });
        jQuery('#gc_close_chat').click(function()
        {
            jQuery("#gc_confirm_dialog").dialog
            ({
                autoOpen:true,
                height: 220,
                width: 330,
                modal: true,
                resizable: false,
                buttons:
                {
                    'End Conference': function()
                    {
                        jQuery(this).dialog('close');
                        jQuery.post("<?php print $CFG->current_app->getUrl() . '/conference/delete'; ?>", {id: gc_chat_session_id }, 
                            function() 
                            {
                                window.close();
                            });              
                    },
                    'Cancel': function()
                    {
                        jQuery(this).dialog('close');
                    }
                }
            });
        });
        function inviteUserDialog()
        {
            jQuery( "#gc_invite_dialog" ).dialog(
            {
                autoOpen: true,
                modal: true,
                buttons:
                {
                    Invite: function()
                    {
                        inviteUser(gc_chat_session_id, jQuery('#gc_invite_user_select').val());
                        jQuery( this ).dialog( "close" );
                    },
                    Cancel: function()
                    {
                        jQuery( this ).dialog( "close" );
                    }
                }
            });
        }
        function inviteUser(session_id, user_id)
        {
            jQuery.post("<?php print $CFG->current_app->getUrl() . '/conference/invite'; ?>", {id: session_id, user: user_id }, "json");
        }
        jQuery.PeriodicalUpdater(
        {
            url : '<?php print $CFG->current_app->getUrl() . '/conference/getUpdate'; ?>',
            minTimeout : 5000,
            maxTimeout : 60000,
            type: "json"
        },
        function(data)
        {
            gc_update_data = data;
            processUpdate();
        });
        function processUpdate()
        {
            checkForTermination();
            updateFriendStatuses();
        }
        function checkForTermination()
        {
            if (gc_update_data.user_chat_sessions.length < 1)
            {
                jQuery('#gc_chat_status_waiting').html('<p><h3><?php print $mhr_user->getFullnameString(); ?> has declined your request to join this video conference.<h3></p>');
                closeChatDialog();
            }
        }
        function updateFriendStatuses()
        {
            for (var i in gc_update_data.friends)
            {
                var invite_dialog_option = jQuery('#gc_invite_friend_option_' + gc_update_data.friends[i].id);

                // Enable or disable invitations
                if (gc_update_data.friends[i].status == 'offline')
                {

                    invite_dialog_option.attr('disabled', 'disabled');
                    if (invite_dialog_option.attr('selected') == 'selected')
                    {
                        invite_dialog_option.removeAttr('selected');
                    }
                }
                if (gc_update_data.friends[i].status != 'offline')
                {
                    if (jQuery('#gc_invite_user_select:selected').length < 1)
                    {
                        invite_dialog_option.attr('selected', 'selected');
                    }
                    jQuery('#gc_invite_friend_option_' + gc_update_data.friends[i].id).removeAttr('disabled');
                }
            }
        }
        jQuery( "#gc_chat_new" ).button(
        {
            text: true
        });	
        jQuery( "#gc_close_chat" ).button(
        {
            text: true
        });
        jQuery( "#gc_whiteboard" ).button(
        {
            text: true
        });	
        jQuery('#gc_chat_status_waiting').show();
    });
</script>
<div id="gc_invite_dialog" title="Invite to Video Conference">
     <p>Please choose a friend to invite.</p>
     
     <select id="gc_invite_user_select">
         <?php
         foreach($friends as $friend)
         {
             $user_obj = $friend->getObject();
             print '<option id="gc_invite_friend_option_' . $user_obj->id . '" value="' .
                     $user_obj->id . '">' . $friend->getFullNameString() . '</option>';
         }
         ?>
     </select>
</div>
<div id="myPublisherDiv"></div>
<span id="gc_chat_toolbar" class="ui-widget-header ui-corner-all">
	<button id="gc_chat_new" class="gc_user_invite_button">Invite Friends</button>
        <a target="_blank" href="<?php print $CFG->current_app->getUrl() . '/conference/whiteboard?id=' . $gc_chat_session_id; ?>"><button id="gc_whiteboard">Open Whiteboard</button></a>
        <button id="gc_close_chat">End Conference</button>
	<br/>
        <div id="gc_chat_status_waiting" style="display:none" class="gc_small_form"><h3>Waiting for <?php print $mhr_user->getFullnameString(); ?> to join this video conference...<h3></div>
</span>
<div id="gc_chat_closed" style="display:none" title="Chat Session Terminated">
	<p>All other users have left this video conference.</p>
</div>
<div id="gc_confirm_dialog" title="End Conference?">
     <p>Do you want to terminate this conferencing session?</p>
</div>