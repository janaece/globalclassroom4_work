<?php

/**
 * conference actions.
 *
 * @package    globalclassroom
 * @subpackage conference
 * @author     Ron Stewart
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class conferenceActions extends sfActions
{
    public function executeWhiteboard(sfWebRequest $request)
    {
        $this->authorizeRequest($request);
        $this->whiteboard_id = false;
        $session_id = $request->getParameter('id');
        
        if ($session_id)
        {
            if ($chat_session = GcrChatSessionTable::getInstance()->find($session_id))
            {
                $user_chat_session = $chat_session->getSessionUser($this->user);
                if ($user_chat_session)
                {
                    $this->whiteboard_id = substr($chat_session->getRoomId(), -20);
                    $this->whiteboard_id = str_replace('-', '', $this->whiteboard_id);
                }
            }
        }
        if (!$this->whiteboard_id)
        {
            global $CFG;
            $CFG->current_app->gcError('User chat session does not exist', 'gcpageaccessdenied');
        }
        $this->getResponse()->setTitle('Video Conference - Shared Whiteboard');
    }
    public function executeView($request)
    {
        $this->authorizeRequest($request);
        $this->gc_chat_session_id = 0;
        $chat_session = false;
        $user_id = $request->getParameter('user_id');
        if ($user_id)
        {
            if ($this->mhr_user = $this->user->getApp()->getUserById($user_id))
            {
                if ($invites = $this->user->getPendingChatInvites($this->mhr_user))
                {
                    $invites[0]->createUserSession();
                    $this->gc_chat_session_id = $invites[0]->getSessionId();
                }
                else if ($chats = $this->user->getChatSessionsWithUser($this->mhr_user))
                {
                    $this->gc_chat_session_id = $chats[0]->getId();
                }
                else if ($this->mhr_user->getAvailabilityStatus()->showChat())
                {
                    if ($chat_session = $this->user->inviteUserToChat($this->mhr_user))
                    {
                        $this->gc_chat_session_id = $chat_session->getId();
                    }
                }
            }
        }
        if (!$chat_session)
        {
            $chat_session = Doctrine::getTable('GcrChatSession')->find($this->gc_chat_session_id);
            if (!$chat_session)
            {
                global $CFG;
                $CFG->current_app->gcError('Chat session not found', 'gcdatabaseerror');
            }  
        }
          
        gcr::loadSdk('opentok');
        $this->api = new OpenTokSDK(API_Config::API_KEY, API_Config::API_SECRET);
        $this->session_id = $chat_session->getRoomId();
        $metadata = '{"username": "' . $this->user->getFullnameString() . 
                ' (' . $this->user->getObject()->username . ')"}';
        $this->token = $this->api->generate_token($this->session_id, RoleConstants::PUBLISHER, null, $metadata);
        
        $this->getResponse()->setTitle($this->mhr_user->getFullnameString() . ' - Video Conference');
        sfConfig::set('sf_escaping_strategy', false);
    }
    private function authorizeRequest($request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->user = $CFG->current_app->getCurrentUser();
        if ($this->user->getApp()->isMoodle())
        {
            $this->user = $this->user->getUserOnInstitution();
        }
        $this->institution = $this->user->getApp();
    }
    public function executeInvite(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        $this->authorizeRequest($request);
        if ($user_id = $request->getParameter('user'))
        {
            if ($mhr_user = $this->user->getApp()->getUserById($user_id))
            {
                if ($mhr_user->getAvailabilityStatus()->showChat())
                {
                    if ($session_id = $request->getParameter('id'))
                    {
                        if ($chat_session = GcrChatSessionTable::getInstance()->find($session_id))
                        {
                            if (!$chat_session->authorizeUser($this->user))
                            {
                                $chat_session = false;
                            }
                        }
                    }
                    if ($chat_session = $this->user->inviteUserToChat($mhr_user, $chat_session))
                    {
                        $chat_session = $chat_session->toArray();
                    }
                }
                else
                {
                    $chat_session = false;
                }
            }
        }
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        return $this->renderText(json_encode($chat_session));
    }
    
    public function executeGetUpdate(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        $this->authorizeRequest($request);
        
        $return_array = array();
        $user_chat_invites = array();
        $user_chat_sessions = array();
        $user_invite_array = array(); // to remember who invited for friends data
        $user_chat_session_array = array(); // to remember who invited for friends data

        if ($this->user->getPendingChatInvites())
        {
            foreach ($this->user->getPendingChatInvites() as $invite)
            {
                $chat_session = $invite->getChatSession();
                $mhr_user = $invite->getFromUser();
                $user_chat_invite = array();
                $user_chat_invite['id'] = $chat_session->getId();
                $user_chat_invite['room_id'] = $chat_session->getRoomId();
                $user_chat_invite['from_user_description_html'] = $invite->getFromUserHtml();
                $user_chat_invite['from_user_fullname'] = $mhr_user->getFullNameString();
                $user_chat_invite['from_user_id'] = $mhr_user->getObject()->id;
                $user_chat_invites[] = $user_chat_invite;
                $user_invite_array[$mhr_user->getObject()->id] = $chat_session->getId();
            }
        }
        $return_array['chat_invites'] = $user_chat_invites;

        if ($this->user->getChatSessionsActive())
        {
            foreach ($this->user->getChatSessionsActive() as $chat_session)
            {
                $user_chat_session = array();
                $user_array = array();
                $user_chat_session['id'] = $chat_session->getSessionId();

                $mhr_users = $chat_session->getOtherUsers();
                if ($mhr_users)
                {
                    foreach ($mhr_users as $mhr_user)
                    {
                        $user_array[$mhr_user->getObject()->id] = $mhr_user->getFullNameString();
                        $user_chat_session_array[$mhr_user->getObject()->id] = $chat_session->getSessionId();
                    }
                }
                $user_chat_session['users'] = $user_array;
                $user_chat_sessions[] = $user_chat_session;
            }
        }
        
        $return_array['user_chat_sessions'] = $user_chat_sessions;

        $friends = array();
        if ($mhr_friends = $this->user->getFriends())
        {
            foreach ($mhr_friends as $mhr_user)
            {
                $obj = $mhr_user->getObject();
                $friend = array();
                $friend['id'] = $obj->id;
                $status = $mhr_user->getAvailabilityStatus();
                $friend['status_color'] = $status->getDisplayColor();
                if ($status->showOnline())
                {
                    $friend['status'] = $status->getObject()->short_name;
                }
                else
                {
                    $friend['status'] = 'offline';
                }
                $friend['full_name'] = $mhr_user->getFullNameString();
                if ($messages = $this->user->getMailFromUser($mhr_user, true))
                {
                    $friend['new_message'] = $messages[0]->id;
                }
                if (isset($user_chat_session_array[$mhr_user->getObject()->id]))
                {
                    $friend['chat_session'] = $user_chat_session_array[$mhr_user->getObject()->id];
                }
                if (isset($user_invite_array[$obj->id]))
                {
                    $friend['chat_invite'] = $user_invite_array[$obj->id];
                }
                if ($invites_to = $mhr_user->getPendingChatInvites($this->user))
                {
                    $friend['chat_invite_to'] = $invites_to[0]->getSessionId();
                }

                $friends[$obj->id] = $friend;
            }
        }
        $return_array['friends'] = $friends;

        $return_array['message_count'] = $this->user->getUnreadMessages()->count;
        
        $return_array['next_poll_time'] = $this->getNextPollTime();
        
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        return $this->renderText(json_encode($return_array));
        
    }
    protected function getNextPollTime()
    {
        $milliseconds = gcr::updatePollingMin;
        $ratio = GcrDatabaseAccessPostgres::getConnectionCountRatio();
        // We take usage in excess of .5 of the available connections as the
        // point at which we start to slow down polling intervals
        $overuse = ($ratio - .5) * 2; // ratio used of the remaining 50%
        if ($overuse > 0)
        {
            // add on up to an extra minute depending on how close to using the
            // max connections we are.
            $milliseconds += gcr::updatePollingMax * $overuse; 
        }
        return $milliseconds;
    }
    
    public function executeDecline(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        $this->authorizeRequest($request);
        if ($session_id = $request->getParameter('id'))
        {
            if ($chat_session = GcrChatSessionTable::getInstance()->find($session_id))
            {
                $invite = $chat_session->getInvite($this->user);
                $invite->delete();
            }
        }
        return sfView::NONE;
    }
    public function executeGetInboxMessage(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        $this->authorizeRequest($request);
        $return_array = array();
        if ($message_id = $request->getParameter('id'))
        {
            if ($mhr_notification_internal_activity =
                    $this->user->getApp()->selectFromMhrTable('notification_internal_activity', 'id', $message_id, true))
            {
                if ($mhr_notification_internal_activity->usr == $this->user->getObject()->id)
                {
                    if ($mhr_user = $this->user->getApp()->getUserById($mhr_notification_internal_activity->from))
                    {
                        $return_array['from_user'] = $mhr_user->getFullNameString();
                    }
                    $return_array['subject'] = $mhr_notification_internal_activity->subject;
                    $return_array['date'] = $mhr_notification_internal_activity->ctime;
                    $return_array['content'] = $mhr_notification_internal_activity->message;

                    $this->user->getApp()->updateMhrTable('notification_internal_activity', array('read' => 1), array('id' => $message_id));
                }
            }
        }
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        return $this->renderText(json_encode($return_array));
    }
    public function executeSetStatus(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        $this->authorizeRequest($request);
        if ($status_short_name = $request->getParameter('id'))
        {
            foreach ($this->user->getApp()->getAvailabilityStatuses(true) as $status)
            {
                if ($status_short_name == $status->short_name)
                {
                    $this->user->setAvailabilityStatus($status->short_name);
                }
            }
        }
        return sfView::NONE;
    }
    public function executeDelete(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        $this->authorizeRequest($request);
        if ($session_id = $request->getParameter('id'))
        {
            if ($chat_session = GcrChatSessionTable::getInstance()->find($session_id))
            {
                $user_chat_session = $chat_session->getSessionUser($this->user);
                if ($user_chat_session)
                {
                    $user_chat_session->delete();
                }
            }
        }
        return sfView::NONE;
    }

}
