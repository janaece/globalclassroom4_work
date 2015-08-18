<?php
// MhrUser class.
// Ron Stewart
// September 10, 2010
//
// This class represents a user from a mdl_user table of a given $this->eschool, and offers methods
// to manipulate this mahara user, without the need to actauly be logged in to that user's mahara.
class GcrMhrUser extends GcrMhrTableRecord
{
    protected $availability_status;
    protected $role_manager;
    
    public function  __construct ($obj, $institution) 
    {
        parent::__construct($obj, $institution);
        $this->availability_status = GcrMhrAvailabilityStatus::getInstance($this);
        $this->role_manager = new GcrUserRoleManager($this);
    }

    public function addMessageToInbox ($subject, $body_text, $body_html = '', $from_mhr_user = false, $message_type = false)
    {
        if (!$message_type)
        {
            $message_type = gcr::moodleMessageTypeName;
        }

        $activity_preference = $this->getMhrUsrActivityPreference($message_type);
        $mhr_activity_type = $this->app->selectFromMhrTable('activity_type', 'name', $message_type, true);

        if ($body_html == '')
        {
            $body = $body_text;
        }
        else
        {
            $body = $body_html;
        }

        // Insert record in to internal activity table (inbox).
        $params = array('id' => gcr::autoNumber,
                        'type' => $mhr_activity_type->id,
                        'usr' => $this->obj->id,
                        'ctime' => date('Y-m-d H:i:s', time()),
                        'subject' => $subject,
                        'message' => $body);

        if ($from_mhr_user)
        {
            if ($from_mhr_user->getApp()->getShortName() == $this->app->getShortName())
            {
                $params['"from"'] = $from_mhr_user->getObject()->id;
            }
        }

        $this->app->insertIntoMhrTable('notification_internal_activity', $params);

        if ($activity_preference == 'emaildigest')
        {
            // insert record for mhr_notification_emaildigest_queue
            $params = array('id' => gcr::autoNumber,
                            'type' => $mhr_activity_type->id,
                            'usr' => $this->obj->id,
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'message' => $body);
            $this->app->insertIntoMhrTable('notification_emaildigest_queue', $params);

            return true;  // don't also send an email
        }
        else if ($activity_preference == 'internal')
        {
            return true;  // don't also send an email
        }
        
        return $this->isMailDisabled(); // send the email if mail is not disabled
    }

    public function getChatInvites ($mhr_user = false)
    {
        if (!$mhr_user)
        {
            $chat_invites = Doctrine::getTable('GcrChatSessionInvite')->createQuery('c')
                ->where('c.user_eschool_id = ?', $this->app->getShortName())
                ->andWhere('c.user_id = ?', $this->obj->id)
                ->orderBy('c.time_created DESC')
                ->execute();
        }
        else
        {
            $chat_invites = Doctrine::getTable('GcrChatSessionInvite')->createQuery('c')
                ->where('c.user_eschool_id = ?', $this->app->getShortName())
                ->andWhere('c.user_id = ?', $this->obj->id)
                ->andWhere('c.from_user_id = ?', $mhr_user->getObject()->id)
                ->andWhere('c.from_user_eschool_id = ?', $mhr_user->getApp()->getShortName())
                ->orderBy('c.time_created DESC')
                ->execute();
        }

        if (count($chat_invites) > 0)
        {
            return $chat_invites;
        }

        return array();
    }

    public function getChatSessions ()
    {
        $chat_sessions = Doctrine::getTable('GcrChatSessionUsers')->createQuery('c')
            ->where('c.user_eschool_id = ?', $this->app->getShortName())
            ->andWhere('c.user_id = ?', $this->obj->id)
            ->orderBy('c.time_created DESC')
            ->execute();

        if (count($chat_sessions) > 0)
        {
            return $chat_sessions;
        }

        return array();
    }

    public function getChatSessionsActive ()
    {
        $active_user_chat_sessions =  array();
        $user_chat_sessions = $this->getChatSessions();

        foreach($user_chat_sessions as $user_chat_session)
        {
            if ($user_chat_session->getOtherUsers())
            {
                $active_user_chat_sessions[] = $user_chat_session;
            }
            else if ($user_chat_session->getChatSession()->getPendingInvites())
            {
                $active_user_chat_sessions[] = $user_chat_session;
            }
            else
            {
                $user_chat_session->delete();
                $user_chat_session->getChatSession()->delete();
            }
        }

        if (count($active_user_chat_sessions) > 0)
        {
            return $active_user_chat_sessions;
        }

        return false;
    }

    public function getChatCount ()
    {
        $session_count = '';

        if ($sessions = $this->getChatSessionsActive())
        {
            $session_count = count($sessions);
        }

        $invites = $this->getChatInvites();

        return $session_count + count($invites);
    }

    public function getChatSessionsWithUser ($mhr_user, $active_only = true)
    {
        $chat_sessions = array();
        $chat_session_users = ($active_only) ? $mhr_user->getChatSessionsActive() : $mhr_user->getChatSessions();

        if($chat_session_users)
        {
            foreach ($chat_session_users as $chat_session_user)
            {
                $chat_session = Doctrine::getTable('GcrChatSession')->find($chat_session_user->getSessionId());

                if ($chat_session->getSessionUser($this) || $chat_session->getInvite($this))
                {
                    $chat_sessions[] = $chat_session;
                }
            }
        }

        if (count($chat_sessions) > 0)
        {
            return $chat_sessions;
        }

        return false;
    }

    public function inviteUserToChat ($mhr_user, $existing_chat_session = false)
    {
        $chat_session = false;

        if ($this->isFriend($mhr_user))
        {
            if ($existing_chat_session)
            {
                if ($existing_chat_session->getSessionUser($this))
                {
                    $chat_session = $existing_chat_session;
                    if (!$chat_session->getInvite($mhr_user))
                    {
                        $chat_session->inviteUser($mhr_user, $this);
                    }
                }
            }
            else
            {
                $invites = $mhr_user->getPendingChatInvites($this);

                if ($invites)
                {
                    $chat_session = $invites[0]->getChatSession();
                }
                else
                {
                    $chat_session = $this->createChatSession();
                    $chat_session->inviteUser($mhr_user, $this);
                }
            }
        }

        return $chat_session;
    }

    // Returns records from mhr_notification_internal_activity.
    // Filters are GcrQueryFilter objects with field, value, and operator properties
    public function getInboxMessages ($filters = array(), $order_by = array())
    {
        $where = new GcrDatabaseQueryFilter('usr', '=', $this->obj->id);
        array_unshift($filters, $where);
        $q = new GcrDatabaseQuery($this->app, 'notification_internal_activity', 'select * from', $filters, $order_by);
        return $q->executeQuery();
    }

    public function getMailFromUser ($mhr_user, $unread = false)
    {
        global $THEME;
        $filters = array();
        $filters[] = new GcrDatabaseQueryFilter('from', '=', $mhr_user->getObject()->id, 'and');

        if ($unread)
        {
            $filters[] = new GcrDatabaseQueryFilter('read', '=', 0, 'and');
        }

        return $this->getInboxMessages($filters);
    }

    public function getMhrUsrActivityPreference ($message_type)
    {
        $mhr_activity_type = $this->app->selectFromMhrTable('activity_type',
                'name', $message_type, true);
        $sql = 'select * from ' . $this->app->getShortName() . '.mhr_usr_activity_preference where ' . 'usr = ? and activity = ?';

        if ($mhr_usr_activity_preference = $this->app->gcQuery($sql, array($this->obj->id, $mhr_activity_type->id), true))
        {
            return $mhr_usr_activity_preference->method;
        }

        return 'email';
    }

    public function addMhrInstitutionMembership ($mhr_institution = false, $change_auth_instance = false)
    {
        if (!$mhr_institution)
        {
            $mhr_institution = $this->app->selectFromMhrTable('institution', 'name', gcr::maharaInstitutionName, true);
        }

        $params = array('usr' => $this->obj->id,
                        'institution' => $mhr_institution->name,
                        'ctime' => date('Y-m-d H:i:s', time()),
                        'studentid' => '');
        $usr_institution = $this->app->insertIntoMhrTable('usr_institution', $params);

        if ($change_auth_instance)
        {
            $mhr_auth_instance = $this->app->getAuthInstanceForMhrInstitution($mhr_institution->name);

            if ($this->obj->authinstance != $mhr_auth_instance->id)
            {
                $this->app->updateMhrTable('usr', array('authinstance' => $mhr_auth_instance->id), array('id' => $this->obj->id));
            }
        }

        return $usr_institution;
    }

    public function getMhrUsrInstitutionRecords ($mhr_institution = false)
    {
        $sql = 'select * from ' . $this->app->getShortName() . '.mhr_usr_institution where usr = ?';
        $params = array($this->obj->id);
        $select_one_record = false;

        if ($mhr_institution)
        {
            $sql .= ' and institution = ?';
            $params[] = $mhr_institution->name;
            $select_one_record = true;
        }

        return $this->app->gcQuery($sql, $params, $select_one_record);
    }

    public function createChatSession ()
    {
        try
        {
            $this->app->beginTransaction();

            // create chat session record
            $chat_session = new GcrChatSession();
            gcr::loadSdk('opentok');
            $api = new OpenTokSDK(API_Config::API_KEY, API_Config::API_SECRET);
            $session = $api->create_session($_SERVER["REMOTE_ADDR"]);
            $chat_session->setRoomId($session->getSessionId());
            $chat_session->setEschoolId($this->app->getShortName());
            $chat_session->setTimeCreated(time());
            $chat_session->save();
            // create chat session users record
            $chat_session->createUserSession($this);
            $this->app->commitTransaction();
        }

        catch (Doctrine_Exception $e)
        {
            $this->app->rollbackTransaction();
            global $CFG;
            $CFG->current_app->gcError($e->getMessage(), 'gcdatabaseerror');
        }

        return $chat_session;
    }

    public function getAccountManager ()
    {
        return new GcrAccountManager($this);
    }

    public function getAuthInstance ()
    {
        return $this->app->selectFromMhrTable('auth_instance', 'id', $this->obj->authinstance, true);
    }

    public function getAvailabilityStatus ()
    {
        return $this->availability_status;
    }

    public function setAvailabilityStatus ($status_name)
    {
        $status = $this->app->selectFromMhrTable('gcr_availability_status', 'short_name', $status_name, true);
        $this->availability_status = $this->availability_status->setStatus($status);
    }

    public function getEclassroom ($eschool)
    {
        return Doctrine::getTable('GcrEclassroom')->createQuery('e')
                   ->where('e.user_institution_id = ?', $this->app->getShortName())
                   ->andWhere('e.user_id = ?', $this->obj->id)
                   ->andWhere('e.eschool_id = ?', $eschool->getShortName())
                   ->fetchOne();
    }
    public function getEclassrooms ($active_only = false)
    {
        if ($active_only)
        {
            $eclassrooms = Doctrine::getTable('GcrEclassroom')->createQuery('e')
		       ->where('e.user_institution_id = ?', $this->app->getShortName())
		       ->andWhere('e.user_id = ?', $this->obj->id)
                       ->andWhere('e.suspended = ?', 'f')
                       ->execute();
        }
        else
        {
            $eclassrooms = Doctrine::getTable('GcrEclassroom')->createQuery('e')
		       ->where('e.user_institution_id = ?', $this->app->getShortName())
		       ->andWhere('e.user_id = ?', $this->obj->id)
                       ->execute();
        }

        if (count($eclassrooms) > 0)
        {
            return $eclassrooms;
        }

        return array();
    }

    public function getEclassroomCourses($eschool = false)
    {
        $courses = array();
        if ($eschool)
        {
            $eschool_eclassroom = $this->getEclassroom($eschool);
            if ($eclassroom)
            {
                $eclassrooms = array($eschool_eclassroom);
            }
        }
        else
        {
            $eclassrooms = $this->getEclassrooms();
        }
        foreach ($eclassrooms as $eclassroom)
        {
            foreach ($eclassroom->getCourses() as $course)
            {
                $courses[] = $course;
            }
        }
        return $courses;
    }
    public function getEnrolments ($eschools = array(), $roleid = false, $visible = false)
    {
        if (!isset($end_ts))
        {
            $end_ts = time();
        }

        if (count($eschools) < 1)
        {
            $eschools = $this->app->getMnetEschools();
        }

        $enrolments = array();

        foreach ($eschools as $eschool)
        {
            if ($mdl_user = $this->getUserOnEschool($eschool))
            {
                $params = array();
                $where = 'WHERE';

                if ($visible !== false)
                {
                    $where .= " c.visible = ? AND";
                    array_push($params, $visible);
                }

                $userid = $mdl_user->getObject()->id;
                $where .= ' ue.enrolid = e.id' .
                          ' AND cx.instanceid = c.id' .
                          ' AND cx.id = ra.contextid' .
                          ' AND ue.userid = ra.userid' .
                          ' AND ra.userid = ?';
                array_push($params, $userid);

                if ($roleid !== false)
                {
                    $where .= ' AND ra.roleid = ?';
                    array_push($params, $roleid);
                }

                $short_name = $eschool->getShortName();
                $sql = "SELECT DISTINCT ue.* FROM " .
                $short_name . ".mdl_course c, " .
                $short_name . ".mdl_context cx, " .
                $short_name . ".mdl_enrol e, " .
                $short_name . ".mdl_role_assignments ra, " .
                $short_name . ".mdl_user_enrolments ue ";
                $sql .= $where;
                $mdl_user_enrolments = $eschool->gcQuery($sql, $params);

                if (count($mdl_user_enrolments) > 0)
                {
                    foreach ($mdl_user_enrolments as $mdl_user_enrolment)
                    {
                        $enrolments[] = new GcrMdlUserEnrolment($mdl_user_enrolment, $eschool);
                    }
                }
            }
        }

        return $enrolments;
    }

    public function getHyperlinkToProfile ()
    {
        return $this->app->getAppUrl() . 'user/view.php?id=' . $this->obj->id;
    }

    public function getProfileIcon ()
    {
        return $this->app->getAppUrl() . 'thumb.php?type=profileiconbyid&maxwidth=80&maxheight=80&id=' . $this->obj->profileicon;
    }

    // This function retrieves all purchases from this user.
    //
    // The $start_ts and $end_ts set a time period to get records from.
    //
    // Parameter $include_all_recurring is a flag to include recurring
    // purchases which fall outside the time period. This is useful because in many cases we
    // want to get all transactions during a time period, but the original purchase records for
    // some of the transactions were created before the time period start date.
    public function getPurchases ($start_ts = 0, $end_ts = null, $include_all_recurring = false)
    {
        if (!$end_ts)
        {
            $end_ts = time();
        }

        $where_string = '(p.trans_time >= ? AND p.trans_time <= ?)';
        $where_array = array($start_ts, $end_ts);

        if ($include_all_recurring)
        {
            $where_string .= ' OR p.bill_cycle != ?';
            $where_array[] = 'single';
        }

        $purchases = Doctrine::getTable('GcrPurchase')
		     ->createQuery('p')
		     ->where('p.user_institution_id = ?', $this->app->getShortName())
		     ->andWhere('p.user_id = ?', $this->obj->id)
		     ->andWhere('p.profile_id != ?', GcrPaypalTable::TXN_PENDING)
		     ->andWhere($where_string, $where_array)
		     ->execute();

        return (count($purchases) > 0) ? $purchases : false;
    }

    public function getEclassroomCourseSales ($start_ts = 0, $end_ts = null)
    {
        if (!$end_ts)
        {
            $end_ts = time();
        }

        $purchases = Doctrine::getTable('GcrPurchase')
		     ->createQuery('p')
		     ->where('p.seller_institution_id = ?', $this->app->getShortName())
		     ->andWhere('p.seller_id = ?', $this->obj->id)
		     ->andWhere('p.trans_time >= ?', $start_ts)
		     ->andWhere('p.trans_time <= ?', $end_ts)
		     ->execute();

        return (count($purchases) > 0) ? $purchases : false;
    }

    public function getFriends ()
    {
        $friends = array();
        $filters = array(new GcrDatabaseQueryFilter('usr1', '=', $this->obj->id),
                         new GcrDatabaseQueryFilter('usr2', '=', $this->obj->id, 'or'));
        $q = new GcrDatabaseQuery($this->app, 'usr_friend', 'select * from', $filters);
        $mhr_user_friends = $q->executeQuery();

        if (count($mhr_user_friends) > 0)
        {
            foreach($mhr_user_friends as $mhr_user_friend)
            {
                $user_id = ($mhr_user_friend->usr1 == $this->obj->id) ? $mhr_user_friend->usr2 : $mhr_user_friend->usr1;

                if ($mhr_user = $this->app->getUserById($user_id))
                {
                    $friends[] = $mhr_user;
                }
            }
        }

        if (count($friends) > 0)
        {
            return $friends;
        }

        return false;
    }

    public function getFullNameString ()
    {
        $full_name = '';
        if (trim($this->obj->firstname) != '')
        {
            $full_name = $this->obj->firstname;
        }

        if (trim($this->obj->lastname) != '')
        {
            if ($full_name)
            {
                $full_name .= ' ';
            }
            $full_name .= $this->obj->lastname;
        }

        return $full_name;
    }

    public function getInstitution ()
    {
        return $this->app;
    }

    public function getInvitedGroups ()
    {
        $sql = 'SELECT g.*, gmi.ctime, gmi.reason FROM ' . $this->app->getShortName() . '.mhr_group g
                JOIN ' . $this->app->getShortName() . '.mhr_group_member_invite gmi on gmi.group = g.id
                WHERE gmi.member = ? and g.deleted = ?';
        $results = $this->app->gcQuery($sql, array($this->obj->id, 0));

        return $results ? count($results) : 0;
    }

    public function getMhrView ($type)
    {
        $sql = 'select * from ' . $this->app->getShortName() . '.mhr_view where owner = ? and "type" = ?';
        $mhr_view_obj = $this->app->gcQuery($sql, array($this->obj->id, $type), true);
        if ($mhr_view_obj)
        {
            return new GcrMhrView($mhr_view_obj, $this->app);
        }
    }

    public function getPendingChatInvites ($mhr_user = false)
    {
        $invites = $this->getChatInvites($mhr_user);
        $pending_invites = array();

        foreach($invites as $invite)
        {
            $chat_is_pending = false;

            if ($chat_session = $invite->getChatSession())
            {
                if ($users_in_chat_session = $chat_session->getSessionUser())
                {
                    foreach($users_in_chat_session as $user_session)
                    {
                        $user = $user_session->getUser();

                        if (!$this->isSameUser($user) && $user->isLoggedIn())
                        {
                            $chat_is_pending = true;
                            $pending_invites[] = $invite;
                            break;
                        }
                    }
                }
            }

            // Either there is no corresponding chat session, no users in that chat anymore,
            // no one is still logged in from that chat session (offline), or this user is already
            // in this chat session. In all these cases, we should delete the invite.
            if (!$chat_is_pending)
            {
                $invite->delete();
            }
        }

        if (count($pending_invites) > 0)
        {
            return $pending_invites;
        }

        return false;
    }

    public function getChatImageSrc ()
    {
        if ($this->getPendingChatInvites())
        {
            return $this->app->getUrl() . '/images/icons/gc-video-chat-pending.jpeg';
        }
        else
        {
            return $this->app->getUrl() . '/images/icons/gc-video-chat.jpeg';
        }
    }

    public function getPendingFriends ()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->app->getShortName() . '.mhr_usr_friend_request WHERE owner = ?';

        return $this->app->gcQuery($sql, array($this->obj->id), true);
    }

    public function getUserOnEschool ($eschool, $create_if_unset = false)
    {
        if (!$mdl_user = $eschool->getUser($this))
        {
            if ($create_if_unset)
            {
                $mdl_user = $eschool->setUser($this);
            }
        }

        return $mdl_user;
    }

    public function getUserOnInstitution ()
    {
        return $this;
    }

    public function getUserGroups ()
    {
        $sql = 'SELECT g.id, g.name, gm.role, g.jointype, g.grouptype, gtr.see_submitted_views, g.category
        FROM ' . $this->app->getShortName() . '.mhr_group g
        JOIN ' . $this->app->getShortName() . '.mhr_group_member gm ON (gm.group = g.id)
        JOIN ' . $this->app->getShortName() . '.mhr_grouptype_roles gtr ON (g.grouptype = gtr.grouptype AND gm.role = gtr.role)
        WHERE gm.member = ?
        AND g.deleted = ?
        ORDER BY g.name DESC, gm.role, g.id';

        return $this->app->gcQuery($sql, array($this->obj->id, 0));
    }

    public function getUserInstitutions ()
    {
        $institutions = array();
        $sql = 'SELECT DISTINCT u.id, ui.institution
		FROM start.mhr_usr_institution ui, start.mhr_usr u
		WHERE u.id = ? AND ui.usr = ?';
        $results = $this->app->gcQuery($sql, array($this->obj->id, $this->obj->id));

        if(count($results) > 0)
        {
            foreach ($results as $result)
            {
                $institutions[] = $result->institution;
            }
        }
        elseif ($results)
        {
            $institutions = $results->institution;
        }

        return $institutions;
    }

    public function getUnreadMessages ()
    {
        $sql = 'SELECT count(*) FROM ' . $this->app->getShortName() . '.mhr_notification_internal_activity ' .
               ' WHERE usr = ? AND read = ?';
        return $this->app->gcQuery($sql, array($this->obj->id, 0), true);
    }

    public function getMhrInstitutions()
    {
        $mhr_usr_institutions = $this->getMhrUsrInstitutionRecords();
        $mhr_institutions = array();
        foreach($mhr_usr_institutions as $mhr_usr_institution)
        {
            $mhr_institution_obj = $this->app->selectFromMhrTable('institution', 'name', $mhr_usr_institution->institution, true);
            $mhr_institutions[] = new GcrMhrInstitution($mhr_institution_obj, $this->app); 
        }
        return $mhr_institutions;
    }
    public function hasAccess ($eschool)
    {
        $mdl_user = $this->getUserOnEschool($eschool);
        if ($mdl_user)
        {
            if ($mdl_user->isAllowed())
            {
                return true;
            }
        }

        return false;
    }
    public function setAccessForMnetEschools()
    {
        $mnet_eschools = $this->app->getMnetEschools();
        foreach($mnet_eschools as $mnet_eschool)
        {
            $this->setAccess($mnet_eschool);
        }
    }
    public function setAccess($eschool)
    {
        $flag = true;
        $has_access = $this->hasAccess($eschool);
        if ($this->app->getConfigVar('gc_restrict_access_by_institution') &&
                (!$this->getRoleManager()->hasPrivilege('EschoolStaff')))
        {
            $flag = false;
            // Check each mhr_institution this user is a member of to see 
            // if one of them allows access to this eschool. 
            // If one does, the user is allowed in.
            foreach($this->getMhrInstitutions() as $mhr_institution)
            {
                if ($mhr_institution->hasAccessToEschool($eschool))
                {
                    $flag = true;
                    break;
                }
            }
        }
        // If the user has mnet access and should not, remove it
        if ($has_access && !$flag)
        {
            $this->getUserOnEschool($eschool)->removeAccess();
        }
        // If the user does not have mnet access, but should, add it
        else if (!$has_access && $flag)
        {
            $this->addAccess($eschool);
        }
        return $flag;
    }

    public function hasDefaultDashboardTemplate()
    {
        $default_text = get_string('dashboarddescription');
        $mhr_view = $this->getMhrView('dashboard');
        // None of the default blocks Mahara adds to the dashboard create a mhr_view_artefact
        // record. If one exists, we know the user must have edited their dashboard.
        $mhr_view_artefact = $this->app->selectFromMhrTable('view_artefact', 'view', $mhr_view->getObject()->id, true);
        if (!$mhr_view_artefact)
        {
            $mhr_view_obj = $mhr_view->getObject();
            return ($mhr_view_obj->description == '' || $mhr_view_obj->description == $default_text);
        }
        return false;
    }

    public function setDashboardToGcrDashboardTemplate ()
    {
        $mhr_view = $this->getMhrView('dashboard');
        $mhr_template_view = $this->app->getDefaultDashboardMhrView();

        if ($mhr_template_view)
        {
            $mhr_view->replaceBlocks($mhr_template_view);
            
            // Append a Recent Activity block
            $inbox_block = new stdClass();
            $inbox_block->blocktype = 'inbox';
            $inbox_block->configdata = 'a:10:{s:8:"feedback";b:1;s:7:"newpost";b:1;s:12:' .
                '"groupmessage";b:1;s:18:"institutionmessage";b:1;s:13:"maharamessage"' .
                ';b:1;s:13:"moodlemessage";b:1;s:11:"usermessage";b:1;s:10:"viewaccess"' .
                ';b:1;s:9:"watchlist";b:1;s:8:"maxitems";s:1:"5";}';
            $inbox_block->title = '';
            $mhr_view->appendBlock($inbox_block);
            
            $obj = $mhr_template_view->getObject();
            $this->app->updateMhrTable('view', 
                array(  'title' => 'Dashboard page', 
                        'description' => $obj->description,
                        'numcolumns' => $obj->numcolumns,
                        'numrows' => $obj->numrows,
                ),
                array('id' => $mhr_view->getObject()->id));
            $mhr_view_rows_columns = $this->app->selectFromMhrTable('view_rows_columns', 'view', $obj->id);
            foreach ($mhr_view_rows_columns as $record)
            {
                $this->app->upsertIntoMhrTable('view_rows_columns', array(
                    'view' => $mhr_view->getObject()->id,
                    '"row"' => $record->row,
                    'columns' => $record->columns),
                    array(
                    'view' => $mhr_view->getObject()->id,
                    '"row"' => $record->row   
                    ));
            }
        }
    }

    public function hasEclassroom ($eschool = false)
    {
        $eclassrooms = $this->getEclassrooms();

        if ($eschool)
        {
            foreach ($eclassrooms as $eclassroom)
            {
                $eclassroom_eschool = $eclassroom->getEschool();
                if ($eclassroom_eschool)
                {
                    if ($eclassroom_eschool->getShortName() == $eschool->getShortName())
                    {
                        return true;
                    }
                }
            }

            return false;
        }

        return (count($eclassrooms) > 0) ? true : false;
    }

    public function isDeleted()
    {
        return ($this->obj->deleted == 1);
    }
    public function isEclassroomOwnerOnAllSchools ()
    {
        global $CFG;
        $eschools = $CFG->current_app->getEclassroomEschools();
        $eclassrooms = $this->getEclassrooms();
        $owned_school_ids[] = array();

        foreach ($eclassrooms as $eclassroom)
        {
            $purchased_eclassroom_school = Doctrine::getTable('GcrEschool')->findOneByShortName($eclassroom->eschool_id);
            $owned_school_ids[] = $purchased_eclassroom_school->id;
            
        }

        foreach ($eschools as $eschool)
        {
            if(!in_array($eschool->id, $owned_school_ids))
            {
                return false;
            }
        }

        return true;
    }

    public function isFriend ($mhr_user)
    {
        foreach ($this->getFriends() as $friend)
        {
            if ($mhr_user->isSameUser($friend))
            {
                return true;
            }
        }

        return false;
    }

    public function isLoggedIn ()
    {
        return ($this->obj->deleted == 0 && $this->getTimeToLogout() > 0);
    }

    public function isMailDisabled()
    {
        $filters = array();
        $filters[] = new GcrDatabaseQueryFilter('usr', '=', $this->obj->id);
        $filters[] = new GcrDatabaseQueryFilter('field', '=', 'maildisabled');
        $q = new GcrDatabaseQuery($this->app, 'usr_account_preference', 'select * from', $filters);
        $mhr_usr_account_preference = $q->executeQuery(true);
        return ($mhr_usr_account_preference && $mhr_usr_account_preference->value == '1');
    }
    public function getTimeToLogout ()
    {
        $last_access_ts = strtotime($this->obj->lastaccess);
        $access_idle_timeout = $this->app->getAccessIdleTimeout();

        return $last_access_ts - (time() - $access_idle_timeout);
    }

    public function isRemoteUser ()
    {
        return false;
    }

    public function isSiteStaffMember ()
    {
        if ($mhr_user_record = $this->app->selectFromMhrTable('usr', 'id', $this->obj->id, true))
        {
            if($mhr_user_record->staff)
            {
                return true;
            }
        }

        return false;
    }

    public function getRoleManager ()
    {
        return $this->role_manager;
    }

    public function isMember ()
    {
        $mhr_auth_instance = $this->getAuthInstance();
        if ($mhr_auth_instance && $mhr_auth_instance->institution == 'mahara')
        {
            $mhr_institution = $this->app->getMhrInstitution();
            if ($this->getMhrUsrInstitutionRecords($mhr_institution))
            {
                $mhr_auth_instance = $this->app->getAuthInstanceForMhrInstitution($mhr_institution->name);

                if ($this->obj->authinstance != $mhr_auth_instance->id)
                {
                    $this->app->updateMhrTable('usr', array('authinstance' => $mhr_auth_instance->id), array('id' => $this->obj->id));
                }
            }
        }
        return ($mhr_auth_instance && $mhr_auth_instance->institution != 'mahara');
    }

    public function isSiteAdmin ()
    {
        return $this->obj->admin;
    }

    public function isSameUser ($user)
    {
        if ($user->getInstitution()->getShortName() == $this->app->getShortName())
        {
            return ($this->obj->username == $user->getObject()->username);
        }

        return false;
    }

    public function addAccess($eschool)
    {
        $host_id = $eschool->getMnetHostId($this->app);
        if ($host_id)
        {
            $mdl_user = $this->getUserOnEschool($eschool, true);
        
            $eschool->upsertIntoMdlTable('mnet_sso_access_control',
            array('username' => $this->obj->username,
                  'mnet_host_id' => $host_id,
                  'accessctrl' => 'allow'),
            array('username' => $this->obj->username, 'mnet_host_id' => $host_id));
        }
        else
        {
            global $CFG;
	    $CFG->current_app->gcError('Eschool ' . $eschool->getShortName() . 
                    ' cannot add user from unknown host ' . $this->app->getShortName());
        }    
    }
    public function refreshSessionTimeout ($ts = false)
    {
        if (!$ts)
        {
            $ts = time();
        }

        $db_time = GcrInstitutionTable::getDbFormatTimestamp($ts);
        $this->app->updateMhrTable('usr', array('lastaccess' => $db_time), array('id' => $this->obj->id));
    }

    public function setAdminRole ($value = true)
    {
        if ($value)
        {
            $value = 1;
        }
        else
        {
            $value = 0;
        }

        $this->app->updateMhrTable('usr', array('admin' => $value), array('id' => $this->obj->id));
    }
    // This function changes a user's email address in all relevent places on the system.
    // This includes profile records, and user records in both Mahara and all attached Moodles
    // NOTE: No validation is included in this function, so make sure that the $email param
    // is valid and unique before calling this function.
    public function setEmailAddress($email)
    {
        $this->app->beginTransaction();
        try
        {
            $filters = array();
            $filters[] = new GcrDatabaseQueryFilter('owner', '=', $this->obj->id);
            $filters[] = new GcrDatabaseQueryFilter('title', '=', $this->obj->email);
            $filters[] = new GcrDatabaseQueryFilter('artefacttype', '=', 'email');
            $q = new GcrDatabaseQuery($this->app, 'artefact', 'select * from', $filters);
            $mhr_artefact = $q->executeQuery(true);
            if ($mhr_artefact)
            {
                $mhr_artefact_internal_profile_email = $this->app->selectFromMhrTable('artefact_internal_profile_email', 
                        'artefact', $mhr_artefact->id, true);
                if ($mhr_artefact_internal_profile_email)
                {
                    $this->app->updateMhrTable('artefact_internal_profile_email', array('email' => $email),
                            array('artefact' => $mhr_artefact->id));
                }
                $this->app->updateMhrTable('artefact', array('title' => $email), array('id' => $mhr_artefact->id));
            }
            $eschools = $this->app->getMnetEschools();
            foreach($eschools as $eschool)
            {
                $mdl_user = $this->getUserOnEschool($eschool);
                if ($mdl_user)
                {
                    $eschool->updateMdlTable('user', array('email' => $email), 
                            array('id' => $mdl_user->getObject()->id));
                }
            }
            $this->app->updateMhrTable('usr', array('email' => $email), array('id' => $this->obj->id));
        }
        catch (Doctrine_Exception $e)
        {
            $this->app->rollbackTransaction();
            $this->app->gcError($e->getMessage(), 'gcdatabaseerror');
        }
        $this->app->commitTransaction();
    }

    public function setStaffRole ($value = true)
    {
        if ($value)
        {
            $value = 1;
        }
        else
        {
            $value = 0;
        }

        $this->app->updateMhrTable('usr', array('staff' => $value), array('id' => $this->obj->id));
    }

    // Used for debugging only! This var_dumps all role classnames for this user.
    public function dumpRoles ()
    {
        foreach ($this->role_manager->getRoles() as $role)
        {
            var_dump(get_class($role));
        }
    }
}
