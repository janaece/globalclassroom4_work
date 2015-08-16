<?php
// GcrUserEmailer class.
// Created by Ron Stewart
// July, 11, 2011
//
// This class extends GcrEmailer to send an email to someone with
// and account on our system. This way, they will also be sent
// a copy of the message to their inbox, and potentially only there
// depending on their messaging settings and what kind of message
// is being sent.

class GcrUserEmailer extends GcrEmailer
{
    protected $users;
    protected $from_user;
    protected $message_type;

    public function __construct($template_name, $mhr_users, $subject, $params = null,
            $from = null, $replyto = null, $message_type = false, $from_mhr_user = false)
    {
        if (!$this->message_type = $message_type)
        {
            // set to system message by default
            $this->message_type = 'maharamessage';
        }
        $this->from_user = $from_mhr_user;
        if (is_array($mhr_users))
        {
            $this->users = $mhr_users;      
        }
        else
        {
            $this->users = array($mhr_users);
        }
        $to = array();
        foreach($this->users as $user)
        {
            if ($user->getMhrUsrActivityPreference($this->message_type) == 'email')
            {
                array_push($to, $user->getObject()->email);
            }
        }
        parent::__construct($template_name, $to, $subject, $params, $from, $replyto);
    }
    public function sendHtmlEmail()
    {
        $send_email = false;
        foreach($this->users as $user)
        {
            if (!$user->addMessageToInbox($this->subject, $this->body, $this->body,
                $this->from_user, $this->message_type))
            {
                $send_email = true;
            }
        }
        if ($send_email)
        {
            parent::sendHtmlEmail();
        }
        return true;
    }
}

?>
