<?php

/**
 * homeadmin actions.
 *
 *
 * @package    globalclassroom
 * @subpackage homeadmin
 * @author     Ron Stewart
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class homeadminActions extends sfActions
{
    public function executeApprovePayoff(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        if (!$payoff_id = $request->getParameter('id'))
        {
            $CFG->current_app->gcError('Payoff ID parameter missing.', 'gcdatabaseerror');
        }
        if (!$payoff = Doctrine::getTable('GcrPayoff')->find($payoff_id))
        {
            $CFG->current_app->gcError('Payoff with ID ' . $payoff_id . ' does not exist.', 'gcdatabaseerror');
        }
        if (!$credentials = $payoff->getCredentials())
        {
            $CFG->current_app->gcError('Payoff with ID ' . $payoff_id . ' has no payoff credentials.', 'gcdatabaseerror');
        }
        $user_institution = $payoff->getUserInstitution();
        $institution = $payoff->getInstitution();
        $user = $payoff->getUser();
        $account_manager = $user->getAccountManager(); 
        $max_withdrawal = $account_manager->getMaxWithdrawalAmount();
        if ($max_withdrawal < $payoff->getAmount())
        {
            $CFG->current_app->gcError('Attempt to withdraw amount greater than max allowance for ' . $institution->getShortName() .
                    ' user ID ' . $user->getObject()->id, 'withdrawalamounttoohigh');
        }
        if (!$payoff->isPending())
        {
            $CFG->current_app->gcError('Payoff with ID ' . $payoff_id . ' is already processed.', 'purchaseattemptedduplicate');
        }
        $payoff->doPaypalMassPayment();
        $this->redirect($CFG->current_app->getUrl() . '/homeadmin/payoffs?purchase=' .
                $payoff->getPurchaseId());
    }
    public function executeSetCourseSeller(sfWebRequest $request)
    {
        $this->authorizeUser();
        $purchase_id = $request->getParameter('id');
        $this->validateSetCourseSeller($purchase_id);
        $this->eclassroom_users = $this->eschool->getEclassroomUsers();
    }
    public function executeDoSetCourseSeller(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        $form = $request->getPostParameters();
        $purchase_id = $form['purchase_id'];
        $this->validateSetCourseSeller($purchase_id);
        $seller_id = $form['course_seller_user_id'];
        $old_seller_id = $this->purchase->getSellerId();
        if ($seller_id != $old_seller_id)
        {
            if ($seller_id != 0)
            {
                $mhr_user = $this->institution->getUserById($seller_id);
                if ($mhr_user->hasEclassroom($this->eschool))
                {
                    $this->purchase->setSellerInstitutionId($this->institution->getShortName());
                }
                else
                {
                    $CFG->current_app->gcError('Seller Id: ' . $seller_id . 
                            ' does not have an eclassroom on ' . $this->eschool->getShortName(), 'gcdatabaseerror');
                }
            }
            $this->purchase->setSellerId($seller_id);
            $this->purchase->save();   
        }
        $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . 
                $this->institution->getShortName());
    }
    protected function validateSetCourseSeller($purchase_id)
    {
        global $CFG;
        if (!$purchase_id)
        {
            $CFG->current_app->gcError('Purchase ID parameter missing.', 'gcdatabaseerror');
        }
        $this->purchase = Doctrine::getTable('GcrPurchase')->find($purchase_id);
        if (!$this->purchase)
        {
            $CFG->current_app->gcError('Purchase with ID: ' . $purchase_id . 
                    ' not found.', 'gcdatabaseerror');
        }
        else if (!$this->purchase->isCourse())
        {
            $CFG->current_app->gcError('Purchase with ID: ' . $purchase_id . 
                    ' cannot set seller on non-course.', 'gcdatabaseerror');
        }
        $this->eschool = $this->purchase->getPurchaseTypeApp();
        if (!$this->eschool)
        {
            $CFG->current_app->gcError('Purchase with ID: ' . $purchase_id . 
                    ' purchase type app does not exist.', 'gcdatabaseerror');
        }
        $this->institution = $this->eschool->getInstitution();
    }
    public function executeCreateTrial(sfWebRequest $request)
    {
        $this->authorizeUser();
        $form = $request->getPostParameters();
        if ($start_date = $form['newstartdatepicker'])
        {
            $institution = GcrInstitutionTable::getInstitution($form['eschool']);
            $trial = new GcrTrial();
            $trial->setStartDate(strtotime($start_date));
            $trial->setOrganizationId($institution->getId());
            $trial->save();
        }
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/trials');
    }
    public function executeDeleteTrial(sfWebRequest $request)
    {
        $this->authorizeUser();
        if ($trial_id = $request->getParameter('del_trial_id'))
        {
            $trial = Doctrine::getTable('GcrTrial')->find($trial_id);
            $trial->delete();
        }
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/trials');
    }
    public function executeEditTrial(sfWebRequest $request)
    {
        $this->authorizeUser();
        $form = $request->getPostParameters();
        if ($trial_id = $form['trial_id'])
        {
            $trial = Doctrine::getTable('GcrTrial')->find($trial_id);
            $trial->setStartDate(strtotime($form['startdatepicker']));
            if ($form['enddatepicker'] == '')
            {
                $end_date = 0;
            }
            else
            {
                $end_date = strtotime($form['enddatepicker']);
            }
            $trial->setEndDate($end_date);
            $trial->save();
        }
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/trials');
    }
    public function executeSetOwner(sfWebRequest $request)
    {
        $this->authorizeUser();
        $this->institution = GcrInstitutionTable::getInstitution($request->getParameter('id'));
        $this->owner = $this->institution->getOwnerUser();
        $this->users = $this->institution->selectFromMhrTable('usr', 'deleted', 0);
        $this->getResponse()->setTitle('Set Platform Owner');
    }
    public function executeDoSetOwner(sfWebRequest $request)
    {
        $this->authorizeUser();
        $form = $request->getPostParameters();
        $institution = GcrInstitutionTable::getInstitution($form['institution_id']);
        $new_owner = $institution->getUserById($form['user_id']);
        $new_owner->getRoleManager()->setAsOwner();
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool');
    }
    public function executeEschool (sfWebRequest $request)
    {
        $this->authorizeUser();
        $this->gc_home_admin = $this->authorizeHomeAdmin();
        $this->data_array = array();
        $this->totals = array();
        $this->totals['users'] = 0;
        $this->totals['courses'] = 0;
        $this->totals['balances'] = 0;
        $this->totals['institutions'] = 0;
        $this->totals['eschools'] = 0;

        foreach (Doctrine::getTable('GcrInstitution')->findAll() as $institution)
        {
            if (!$institution->isCreated())
            {
                global $CFG;
                $CFG->current_app->gcError('WARNING: Institution ' . $institution->getShortName() . 
                        ' has no existing schema');
                continue;
            }
            $expiration_ts = null;
            $date = null;
            $status = null;
            $balance = 0;
            $owner = $institution->getOwnerUser();
            if ($institution->getIsInternal())
            {
                $status = 'Internal';
            }
            else if ($trial = $institution->getActiveTrial())
            {
                $expiration_ts = $trial->getExpirationTime();
                if ($expiration_ts < time())
                {
                    $status = 'Expired Trial';
                }
                else
                {
                    $status = 'Trial';
                }
            }
            else if ($expiration_ts = $institution->getNextActivationPaymentDate())
            {
                if ($expiration_ts < time())
                {
                    $status = 'Overdue';
                }
                else
                {
                    $status = 'Paid';
                }
            }
            else
            {
                $status = 'Free';
            }
            if (!$balance = $institution->getAccountManager()->getAccountBalance())
            {
                $balance = 0;
            }
            $eschools = $institution->getEschools();
            $eschool_count = count($eschools);
            $user_count = $institution->getUserCount();
            $course_count = $institution->getCourseCount();
            $enrol_count = 0;
            $enrol_count_last_month = 0;
            $thirty_days_ago = time() - (24 * 60 * 60 * 30);
            
            foreach ($eschools as $eschool) 
            {
                foreach ($eschool->getMdlEnrolments() as $mdl_enrolment) 
                {
                    if ($mdl_enrolment->timecreated >= $thirty_days_ago) 
                    {
                        $enrol_count_last_month++;
                    }
                    $enrol_count++;
                }
                
            }

            if ($expiration_ts)
            {
                $date = date('m/d/Y', $expiration_ts);
            }
            else
            {
                $date = date('M j, Y g:i A', 2100000000);
            }
            if ($created_ts = $institution->getCreationDate())
            {
                $created = date('m/d/Y', $created_ts);
            }
            $full_name = $institution->getFullName();

            if (strlen($full_name) > 54)
            {
                $full_name = substr($full_name, 0, 50) . '...';
            }

            $this->data_array[] = array('eschool' => $institution,
                                        'moodles' => $eschools,
                                        'balance' => $balance,
                                        'name' => $full_name,
                                        'created' => $created,
                                        'num_users' => $user_count,
                                        'num_courses' => $course_count,
                                        'num_enrol' => $enrol_count,
                                        'num_enrol_month' => $enrol_count_last_month,
                                        'owner' => $owner,
                                        'status' => $status,
                                        'date' => $date);

            $this->totals['users'] += $user_count;
            $this->totals['courses'] += $course_count;
            $this->totals['balances'] += $balance;
            $this->totals['eschools']++;
        }
        $this->getResponse()->setTitle('My eSchools Dashboard');
    }
    protected function authorizeHomeAdmin()
    {
        return ($this->current_user->getRoleManager()->hasPrivilege('GCHomeAdmin') && 
                !session_is_loggedinas());
    }
    public function executeAdminAccess(sfWebRequest $request)
    {
        $this->authorizeUser();
        if ($this->authorizeHomeAdmin())
        {
            if ($short_name = $request->getParameter('eschoolList'))
            {
                $app = GcrInstitutionTable::getApp($short_name);
                $this->redirect($app->setupAdminAutoLogin());
            }
        }
        global $CFG;
        $this->redirect($CFG->current_app->getUrl() . '/homeadmin/eschool');
    }
    public function executeCommission(sfWebRequest $request)
    {
        $this->authorizeUser();
        $this->data_array = array();
        $this->commissions = Doctrine::getTable('GcrCommission')->findAll();
        $this->getResponse()->setTitle('GlobalClassroom Commissions Management');
    }
    public function executeCreateCommission(sfWebRequest $request)
    {
        $this->authorizeUser();
        $form = $request->getPostParameters();
        
        if ($form['commission_rate'] > 0 && $form['commission_rate'] <= 100)
        {
            $institution = GcrInstitutionTable::getInstitution($form['institution']);
            $eschool = GcrEschoolTable::getEschool($form['eschool']);
            $existing_commission = GcrCommissionTable::getCommission($institution, $eschool);
            if ($existing_commission)
            {
                $existing_commission->setCommissionRate($form['commission_rate']);
                $existing_commission->save();
            }
            else
            {
                GcrCommissionTable::createCommission($institution, $eschool, $form['commission_rate']);
            }
        }
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/commission');
    }
    public function executeDeleteCommission(sfWebRequest $request)
    {
        $this->authorizeUser();
        $commission_id = $request->getParameter('del_commission_id');
        if ($commission_id)
        {
            $commission = Doctrine::getTable('GcrCommission')->find($commission_id);
            $commission->delete();
        }
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/commission');
    }
    public function executeEditCommission(sfWebRequest $request)
    {
        $this->authorizeUser();
        $form = $request->getPostParameters();
        $commission_id = $form['commission_id'];
        if (isset($commission_id) && $form['edit_commission_rate'] > 0 && $form['edit_commission_rate'] <= 100)
        {
            $commission = Doctrine::getTable('GcrCommission')->find($commission_id);
            $commission->setCommissionRate($form['edit_commission_rate']);
            $commission->save();
        }
        $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/commission');
    }
    public function executeConfigEschool(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        if (!$short_name = $request->getParameter('eschoolList'))
        {
            $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool');
        }
        $this->message = '';
        if (isset($_SESSION['eschoolSettingsSaved']))
        {
            $this->message = $_SESSION['eschoolSettingsSaved'];
            unset($_SESSION['eschoolSettingsSaved']);
        }
        $this->eschoolSettingsForm = new GcrEschoolSettingsForm();

        $this->eschool = GcrEschoolTable::getEschool($short_name);
        if ($this->eschool->isTemplate() && (!$CFG->current_app->hasPrivilege('GCHomeAdmin')))
        {
            $CFG->current_app->gcError('Unauthorized attempt to access template settings', 'gcpageaccessdenied');
        }

        $this->eschoolSettingsForm->setDefaults(array
        (
            'eschool_short_name'        => $this->eschool->getShortName(),
            'is_public'                 => $this->eschool->getIsPublic(),
            'is_visible'                => $this->eschool->getVisible(),
            'gc_auto_creates_users'     => $this->eschool->getConfigVar('gc_auto_creates_users'),
            'course_gc_fee'             => number_format($this->eschool->getConfigVar('gc_course_fee_percent'), 1, '.', ''),
            'course_owner_fee'          => number_format($this->eschool->getConfigVar('owner_course_fee_percent'), 1, '.', ''),
            'classroom_trial_length'    => $this->eschool->getConfigVar('gc_classroom_trial_length'),
            'classroom_cost_month'      => number_format($this->eschool->getConfigVar('gc_classroom_cost_month'), 2, '.', ''),
            'classroom_cost_year'       => number_format($this->eschool->getConfigVar('gc_classroom_cost_year'), 2, '.', ''),
            'classroom_gc_fee'          => number_format($this->eschool->getConfigVar('gc_classroom_fee_percent'), 1, '.', ''),
        ));
        $this->getResponse()->setTitle($this->eschool->getFullName() . ' Settings');
    }
    public function executeConfig(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        if (!$short_name = $request->getParameter('eschoolList'))
        {
            $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool');
        }
        $this->message = '';
        if (isset($_SESSION['eschoolSettingsSaved']))
        {
            $this->message = $_SESSION['eschoolSettingsSaved'];
            unset($_SESSION['eschoolSettingsSaved']);
        }
        $this->settingsForm = new GcrSettingsForm();

        $this->institution = GcrInstitutionTable::getInstitution($short_name);
        if ($this->institution->isTemplate() && (!$CFG->current_app->hasPrivilege('GCHomeAdmin')))
        {
            $CFG->current_app->gcError('Unauthorized attempt to access template settings', 'gcpageaccessdenied');
        }

        $this->settingsForm->setDefaults
        (
            array
            (
                'short_name'                        => $this->institution->getShortName(),
                'force_membership'                  => $this->institution->getConfigVar('gc_force_membership'),
                'membership_cost_month'             => $this->institution->getConfigVar('gc_membership_cost_month'),
                'membership_cost_year'              => $this->institution->getConfigVar('gc_membership_cost_year'),
                'membership_fee_percent'            => $this->institution->getConfigVar('gc_membership_fee_percent'),
                'membership_trial_length'           => $this->institution->getConfigVar('gc_membership_trial_length'),
                'eclassroom_min_balance'            => $this->institution->getConfigVar('gc_eclassroom_min_balance'),
                'eschool_min_balance'               => $this->institution->getConfigVar('gc_eschool_min_balance'),
                'eclassroom_create_institution'     => $this->institution->getConfigVar('gc_eclassroom_create_institution'),
                'membership_includes_eclassroom'    => $this->institution->getConfigVar('gc_membership_includes_eclassroom'),
                'is_internal'                       => $this->institution->getIsInternal(),
            )
        );
        $this->getResponse()->setTitle($this->institution->getFullName() . ' Settings');
    }
    public function executeDeletePayoff(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        if (!$payoff_id = $request->getParameter('id'))
        {
            $CFG->current_app->gcError('Payoff ID parameter missing.', 'gcdatabaseerror');
        }
        if (!$payoff = Doctrine::getTable('GcrPayoff')->find($payoff_id))
        {
            $CFG->current_app->gcError('Payoff with ID ' . $payoff_id . ' does not exist.', 'gcdatabaseerror');
        }
        if ($purchase = $payoff->getPurchase())
        {
            $purchase->cleanDelete();
        }
        $payoff->delete();
        $this->redirect($CFG->current_app->getUrl() . '/homeadmin/payoffs');
    }
    public function executeDoInstitutionSettings(sfWebRequest $request)
    {
        $this->authorizeUser();
        if (!$short_name = $request->getParameter('short_name'))
        {
            $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool');
        }
        $this->institution = GcrInstitutionTable::getInstitution($short_name);
        if ($this->institution->isTemplate() && (!$CFG->current_app->hasPrivilege('GCHomeAdmin')))
        {
            $CFG->current_app->gcError('Unauthorized attempt to access template settings', 'gcpageaccessdenied');
        }
        $this->settingsForm = new GcrSettingsForm();
        $form = $request->getPostParameters();
        $this->settingsForm->bind($form);
        if ($this->settingsForm->isValid())
        {
            $config_settings = array('gc_force_membership' => $form['force_membership'],
                    'gc_eclassroom_min_balance' => $form['eclassroom_min_balance'],
                    'gc_eschool_min_balance' => $form['eschool_min_balance'],
                    'gc_membership_cost_month'  => $form['membership_cost_month'],
                    'gc_membership_cost_year'  => $form['membership_cost_year'],
                    'gc_membership_trial_length'  => $form['membership_trial_length'],
                    'gc_membership_fee_percent'  => $form['membership_fee_percent'],
                    'gc_eclassroom_create_institution' => $form['eclassroom_create_institution'],
                    'gc_membership_includes_eclassroom' => $form['membership_includes_eclassroom']);

            foreach ($config_settings as $config_setting => $form_value)
            {
                $this->institution->setConfigVar($config_setting, $form_value);
            }
            $is_internal = ($form['is_internal'] == 'on');
            if ($this->institution->getIsInternal() != $is_internal)
            {
                $this->institution->setIsInternal($is_internal);
                $this->institution->save();
            }
            $_SESSION['eschoolSettingsSaved'] = 'Settings Saved';
            $this->redirect('homeadmin/config?eschoolList=' . $this->institution->getShortName());
        }
        else
        {
            $this->setTemplate('config');
        }
    }
    public function executeDoEschoolSettings (sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        if (!$eschool_short_name = $request->getParameter('eschool_short_name'))
        {
            $this->redirect(GcrEschoolTable::getHome()->getUrl() . '/homeadmin/eschool');
        }

        $this->eschool = GcrEschoolTable::getEschool($eschool_short_name);
        if ($this->eschool->isTemplate() && (!$CFG->current_app->hasPrivilege('GCHomeAdmin')))
        {
            $CFG->current_app->gcError('Unauthorized attempt to access template settings', 'gcpageaccessdenied');
        }
        $this->eschoolSettingsForm = new GcrEschoolSettingsForm();
        $form = $request->getPostParameters();
        $this->eschoolSettingsForm->bind($form);
        if ($this->eschoolSettingsForm->isValid())
        {
            $config_settings = array(
                                    'gc_course_fee_percent' => $form['course_gc_fee'],
                                    'owner_course_fee_percent' => $form['course_owner_fee'],
                                    'gc_classroom_trial_length' => $form['classroom_trial_length'],
                                    'gc_classroom_cost_month' => $form['classroom_cost_month'],
                                    'gc_classroom_cost_year' => $form['classroom_cost_year'],
                                    'gc_classroom_fee_percent' => $form['classroom_gc_fee'],
                                    'gc_auto_creates_users' => $form['gc_auto_creates_users']);

            foreach ($config_settings as $config_setting => $form_value)
            {
                if ($form_value == '')
                {
                    $this->eschool->deleteFromMdlTable('config', 'name', $config_setting);
                }
                else
                {
                    $this->eschool->setConfigVar($config_setting, $form_value);
                }
            }
            $is_public = ($form['is_public'] == 'on');
            $visible = ($form['is_visible'] == 'on');
            $this->eschool->setIsPublic($is_public);
            $this->eschool->setVisible($visible);
            $this->eschool->save();
            $_SESSION['eschoolSettingsSaved'] = 'Settings Saved';
            $this->redirect('homeadmin/configEschool?eschoolList=' . $this->eschool->getShortName());
        }
        else
        {
            $this->setTemplate('eschoolConfig');
        }
    }

    public function executePayoffs (sfWebRequest $request)
    {
        $this->authorizeUser();
        if ($purchase_id = $request->getParameter('purchase'))
        {
            if ($purchase = Doctrine::getTable('GcrPurchase')->find($purchase_id))
            {
                $purchase->updateRelatedAccounting();
            }
        }
        $this->payoffs = Doctrine::getTable('GcrPayoff')->findAll();
        $this->getResponse()->setTitle('GlobalClassroom Payoff Management');
    }
    public function executeTrials (sfWebRequest $request)
    {
        $this->authorizeUser();
        $this->data_array = array();
        $trials = Doctrine::getTable('GcrTrial')->findAll();
        foreach($trials as $trial)
        {
            $institution = $trial->getInstitution();
            $last_activity = $institution->getLastActivity();
            $this->data_array[] = array('name' => $institution->getFullName()  . ' (' . $institution->getShortName() . ')',
                                        'num_courses' => $institution->getCourseCount(),
                                        'num_users' => $institution->getUserCount(),
                                        'owner' => $institution->getOwnerUser(),
                                        'last_activity' => $last_activity,
                                        'trial' => $trial);
        }
        $this->getResponse()->setTitle('GlobalClassroom Trial Management');
    }
    public function authorizeManualPurchaseForm(sfWebRequest $request, $type)
    {
        $this->authorizeUser();
  	global $CFG;
  	$purchase = false;
  	if (!$short_name = $request->getParameter('eschool'))
  	{
            $this->redirect($CFG->current_app->getUrl());
  	}
        $this->eschool = GcrInstitutionTable::getInstitution($short_name);
  	$this->return_url = $CFG->current_app->getUrl() . '/account/view?eschool=' .
                $this->eschool->getShortName();
  	if ($purchase_id = $request->getParameter('purchase'))
  	{
            if (!$purchase = Doctrine::getTable('GcrPurchase')->find($purchase_id))
            {
                $CFG->current_app->gcError('Purchase ID ' . $purchase_id . ' does not exist',
                        'gcdatabaseerror');
            }
            else if ($purchase->getPurchaseType() != $type . '_manual')
            {
                $CFG->current_app->gcError('Purchase ID ' . $purchase_id . ' is not of type ' .
                        $type . '_manual', 'gcdatabaseerror');
            }
  	}
        if ($user_id = $request->getParameter('user'))
        {
            $this->default_user_id = $user_id;
        }
  	return $purchase;
    }
    public function executeManualCourse(sfWebRequest $request)
    {
        $purchase = $this->authorizeManualPurchaseForm($request, 'course');
        $this->course_form = new GcrPurchaseCourseManualForm($purchase, array('eschool' => $this->eschool));
        $this->course_form->setDefault('user_institution_id', $this->eschool->getShortName());
        if (!$purchase)
        {
            $this->course_form->setDefault('trans_time', time());
        }
        if (isset($this->default_user_id))
        {
            $this->course_form->setDefault('purchase_user_field', $this->default_user_id);
        }
    }
    public function executeManualClassroom(sfWebRequest $request)
    {
        $purchase = $this->authorizeManualPurchaseForm($request, 'classroom');
        $this->classroom_form = new GcrPurchaseClassroomManualForm($purchase, 
                array('eschool' => $this->eschool));
  	if (!$purchase)
  	{
            $this->classroom_form->setDefault('trans_time', time());
  	}
        if (isset($this->default_user_id))
        {
            $this->classroom_form->setDefault('purchase_user_field', $this->default_user_id);
        }
    }
    public function executeManualMembership(sfWebRequest $request)
    {
  	$purchase = $this->authorizeManualPurchaseForm($request, 'membership');
  	$this->membership_form = new GcrPurchaseMembershipManualForm($purchase,
                array('eschool' => $this->eschool));
  	if (!$purchase)
  	{
            $this->membership_form->setDefault('trans_time', time());
  	}
        if (isset($this->default_user_id))
        {
            $this->membership_form->setDefault('purchase_user_field', $this->default_user_id);
        }
    }
    public function executeManualEschool(sfWebRequest $request)
    {
  	$purchase = $this->authorizeManualPurchaseForm($request, 'eschool');
  	$this->eschool_form = new GcrPurchaseEschoolManualForm($purchase);
  	if (!$purchase)
  	{
            $this->eschool_form->setDefault('trans_time', time());
  	}
    }
    public function executeManualSale(sfWebRequest $request)
    {
        $purchase = $this->authorizeManualPurchaseForm($request, 'sale');
  	$this->sale_form = new GcrPurchaseSaleManualForm($purchase, array('eschool' => $this->eschool));
  	$this->sale_form->setDefault('user_institution_id', $this->eschool->getShortName());
        if (!$purchase)
  	{
            $this->sale_form->setDefault('trans_time', time());
  	}
        if (isset($this->default_user_id))
        {
            $this->sale_form->setDefault('purchase_user_field', $this->default_user_id);
        }
    }
    public function executeDoManualSale(sfWebRequest $request)
    {
  	global $CFG;
  	$this->authorizeUser();
  	$form = $request->getPostParameters();
        $form['purchase_type_eschool_id'] = gcr::gchomeSchemaMahara;
        $institution = GcrInstitutionTable::getInstitution($form['user_institution_id']);
        
  	if ($form['id'] != '')
  	{
            // Edit of existing purchase
            $purchase = $this->getManualPurchase($form['id'], 'sale');
           
            if (!$mhr_user = $institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
            {
                $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
            }
            $manual_purchase_form = new GcrPurchaseSaleManualForm($purchase, array('eschool' => $institution));
            $form['user_id'] = $mhr_user->id;
            $form['amount'] = $form['amount_field'];
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                $purchase = $manual_purchase_form->save();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName());
            }
  	}
  	else
  	{
            // New purchase
            
            $manual_purchase_form = new GcrPurchaseSaleManualForm(array(),
                    array('eschool' => $institution));
            $purchase_item = Doctrine::getTable('GcrPurchaseItem')->find($form['purchase_type_id']);
            $form['purchase_type_description'] = $purchase_item->getDescription();
            $form['purchase_type'] = 'sale_manual';
            $form['user_id'] = $form['purchase_user_field'];
            $form['user_institution_id'] = $institution->getShortName();
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = 0;
            $form['owner_fee'] = 0;
            $form['purchase_type_quantity'] = 1;
            $form['bill_cycle'] = 'single';
            $form['seller_id'] = 0;
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            
            if ($manual_purchase_form->isValid())
            {
                if (!$institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
                {
                    $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
                }
                $purchase = $manual_purchase_form->save();
                $purchase->assignSeller();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' .
                        $institution->getShortName());
            }
            //var_dump($manual_purchase_form->getErrorSchema()->__toString());die;
  	}
  	$this->sale_form = $manual_purchase_form;
  	$this->setTemplate('manualSale');
    }
    public function executeDoManualEschool(sfWebRequest $request)
    {
        $this->authorizeUser();
  	global $CFG;
  	$form = $request->getPostParameters();

  	if ($form['id'] != '')
  	{
            // Edit of existing purchase
            $purchase = $this->getManualPurchase($form['id'], 'eschool');
            $this->institution = $this->eschool->getInstitution();
            $manual_purchase_form = new GcrPurchaseEschoolManualForm($purchase, 
                    array('eschool' => $this->institution));
            $form['amount'] = $form['amount_field'];
            $form['bill_cycle'] = GcrPurchaseTable::convertDatetoTimestamp($form['bill_cycle']);
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);

            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                $purchase = $manual_purchase_form->save();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' .
                        $this->institution->getShortName());
            }
  	}
  	else
  	{
            // New purchase
            if ($short_name = $form['user_institution_id'])
            {
                $this->institution = GcrInstitutionTable::getInstitution($short_name);
            }
            else
            {
                $CFG->current_app->gcError('Schema Parameter Missing', 'gcdatabaseerror');
            }

            $manual_purchase_form = new GcrPurchaseEschoolManualForm();

            $form['purchase_type'] = 'eschool_manual';
            $form['purchase_type_description'] = 'Manual eSchool Transaction';
            $form['purchase_type_quantity'] = 1;
            $form['purchase_type_id'] = $form['user_institution_id'];
            $form['purchase_type_eschool_id'] = $form['user_institution_id'];
            $form['user_id'] = $this->institution->getCreatorId();
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = 0;
            $form['owner_fee'] = 0;
            $form['seller_id'] = 0;
            $form['bill_cycle'] = GcrPurchaseTable::convertDateToTimestamp($form['bill_cycle']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                $purchase = $manual_purchase_form->save();
                $purchase->assignSeller();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . 
                        $this->institution->getShortName());
            }
  	}
  	$this->eschool_form = $manual_purchase_form;
  	$this->setTemplate('manualActivation');
    }
    public function executeDoManualCourse(sfWebRequest $request)
    {
  	global $CFG;
  	$this->authorizeUser();
  	$form = $request->getPostParameters();
        $course_data = explode('#', $form['purchase_type_id']);
        $form['purchase_type_eschool_id'] = $course_data[0];
        $form['purchase_type_id'] = $course_data[1];
        $institution = GcrInstitutionTable::getInstitution($form['user_institution_id']);
        
  	if ($form['id'] != '')
  	{
            // Edit of existing purchase
            $purchase = $this->getManualPurchase($form['id'], 'course');
           
            if (!$course = $this->eschool->getCourse($form['purchase_type_id']))
            {
                $CFG->current_app->gcError('Invalid course ID ' . $form['purchase_type_id'], 'gcdatabaseerror');
            }
            
            if (!$mhr_user = $institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
            {
                $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
            }
            $manual_purchase_form = new GcrPurchaseCourseManualForm($purchase, array('eschool' => $institution));
            $form['user_id'] = $mhr_user->id;
            $form['purchase_type_id'] = $course->getObject()->id;
            $form['amount'] = $form['amount_field'];
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                $purchase = $manual_purchase_form->save();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName());
            }
  	}
  	else
  	{
            // New purchase
            
            $this->eschool = GcrEschoolTable::getEschool($form['purchase_type_eschool_id']);
           
            $manual_purchase_form = new GcrPurchaseCourseManualForm(array(),
                    array('eschool' => $institution));

            $form['purchase_type'] = 'course_manual';
            $form['user_id'] = $form['purchase_user_field'];
            $form['user_institution_id'] = $institution->getShortName();
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = $this->eschool->getGcFeeCourse();
            $form['owner_fee'] = $this->eschool->getOwnerFeeCourse();
            $form['commission_fee'] = GcrPurchaseTable::getCommissionFee($institution, $this->eschool);
            $form['bill_cycle'] = 'single';
            $form['seller_id'] = 0;
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                if (!$course = $this->eschool->getCourse($form['purchase_type_id']))
                {
                    $CFG->current_app->gcError('Invalid course ID ' .
                            $form['purchase_type_id'], 'gcdatabaseerror');
                }
                else if (!$institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
                {
                    $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
                }
                $purchase = $manual_purchase_form->save();
                $purchase->assignSeller();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' .
                        $this->eschool->getInstitution()->getShortName());
            }
  	}
  	$this->course_form = $manual_purchase_form;
  	$this->setTemplate('manualCourse');
    }
    public function executeDoManualClassroom(sfWebRequest $request)
    {
        $this->authorizeUser();
  	global $CFG;
  	$form = $request->getPostParameters();

  	if ($form['id'] != '')
  	{
            // Edit of existing purchase
            $purchase = $this->getManualPurchase($form['id'], 'classroom');
            $institution = $this->eschool->getInstitution();
            if (!$mhr_user = $institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
            {
                $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
            }
            $manual_purchase_form = new GcrPurchaseClassroomManualForm($purchase, array('eschool' => $institution));
            $form['user_id'] = $mhr_user->id;
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = $this->eschool->getGcFeeClassroom();
            $form['bill_cycle'] = GcrPurchaseTable::convertDatetoTimestamp($form['bill_cycle']);
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                $purchase = $manual_purchase_form->save();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' .
                        $institution->getShortName());
            }
  	}
  	else
  	{
            // New purchase
            if ($short_name = $form['purchase_type_eschool_field'])
            {
                $this->eschool = GcrEschoolTable::getEschool($short_name);
            }
            else
            {
                $CFG->current_app->gcError('eSchool Parameter Missing', 'gcdatabaseerror');
            }
            $institution = $this->eschool->getInstitution();
            $manual_purchase_form = new GcrPurchaseClassroomManualForm(array(), array('eschool' => $institution));

            $form['purchase_type'] = 'classroom_manual';
            $form['purchase_type_description'] = 'Manual eClassroom Transaction';
            $form['purchase_type_quantity'] = 1;
            $form['purchase_type_id'] = $this->eschool->getShortName();
            $form['user_id'] = $form['purchase_user_field'];
            $form['purchase_type_eschool_id'] = $form['purchase_type_eschool_field'];
            $form['user_institution_id'] = $institution->getShortName();
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = $this->eschool->getGcFeeClassroom();
            $form['owner_fee'] = 0;
            $form['seller_id'] = 0;
            $form['bill_cycle'] = GcrPurchaseTable::convertDatetoTimestamp($form['bill_cycle']);
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                if (!$mhr_user = $institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
                {
                    $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
                }
                $mhr_user = new GcrMhrUser($mhr_user, $institution);
                $purchase = $manual_purchase_form->save();
                $purchase->assignSeller();
                if (!$mhr_user->hasEclassroom($this->eschool))
                {
                    $institution->createEclassroom($mhr_user, $this->eschool);
                }
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName());
            }
  	}
  	$this->classroom_form = $manual_purchase_form;
  	$this->setTemplate('manualClassroom');
    }
    protected function getManualPurchase($id, $type)
    {
        global $CFG;
        if (!$purchase = Doctrine::getTable('GcrPurchase')->find($id))
        {
            $CFG->current_app->gcError('Purchase ID: ' . $id . ' does not exist', 'gcdatabaseerror');
        }
        else if ($purchase->getPurchaseType() != $type . '_manual')
        {
            $CFG->current_app->gcError('Purchase ID ' . $purchase->getId() . ' is not of type ' . 
                    $type . '_manual', 'gcdatabaseerror');
        }
        else if (!$this->eschool = $purchase->getPurchaseTypeApp())
        {
            $CFG->current_app->gcError('Purchase ID ' . $purchase->getId() .
                    ' eSchool ID ' . $purchase->getPurchaseTypeEschoolId() . ' invalid.', 'gcdatabaseerror');
        }
        return $purchase;
    }
    public function executeDoManualMembership(sfWebRequest $request)
    {
        $this->authorizeUser();
  	global $CFG;
  	$form = $request->getPostParameters();

  	if ($form['id'] != '')
  	{
            // Edit of existing purchase
            $purchase = $this->getManualPurchase($form['id'], 'membership');

            $institution = $this->eschool->getInstitution();
            if (!$mhr_user = $institution->selectFromMhrTable('usr', 'id', $form['purchase_user_field'], true))
            {
                $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
            }
            $manual_purchase_form = new GcrPurchaseMembershipManualForm($purchase, array('eschool' => $institution));
            $form['user_id'] = $mhr_user->id;
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = $this->eschool->getGcFeeMembership();
            $form['bill_cycle'] = GcrPurchaseTable::convertDatetoTimestamp($form['bill_cycle']);
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                $purchase = $manual_purchase_form->save();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' .
                        $institution->getShortName());
            }
  	}
  	else
  	{
            // New purchase
            if ($short_name = $form['user_institution_id'])
            {
                $this->eschool = GcrInstitutionTable::getInstitution($short_name);
            }
            else
            {
                $CFG->current_app->gcError('eSchool Parameter Missing', 'gcdatabaseerror');
            }
            $institution = $this->eschool->getInstitution();
            $manual_purchase_form = new GcrPurchaseMembershipManualForm(array(), array('eschool' => $institution));

            $form['purchase_type'] = 'membership_manual';
            $form['purchase_type_description'] = 'Manual Membership Transaction';
            $form['purchase_type_quantity'] = 1;
            $form['purchase_type_id'] = $institution->getShortName();
            $form['user_id'] = $form['purchase_user_field'];
            $form['purchase_type_eschool_id'] = $institution->getShortName();
            $form['user_institution_id'] = $institution->getShortName();
            $form['amount'] = $form['amount_field'];
            $form['gc_fee'] = $institution->getGcFeeMembership();
            $form['owner_fee'] = 0;
            $form['seller_id'] = 0;
            $form['bill_cycle'] = GcrPurchaseTable::convertDatetoTimestamp($form['bill_cycle']);
            $form['trans_time'] = GcrPurchaseTable::convertDatetoTimestamp($form['trans_time']);
            $manual_purchase_form->bind($form);
            if ($manual_purchase_form->isValid())
            {
                if (!$institution->getUserById($form['purchase_user_field']))
                {
                    $CFG->current_app->gcError('Invalid user ID ' . $form['purchase_user_field'], 'gcdatabaseerror');
                }
                $purchase = $manual_purchase_form->save();
                $purchase->assignSeller();
                $purchase->updateRelatedAccounting();
                $this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . $institution->getShortName());
            }
  	}
  	$this->memerbship_form = $manual_purchase_form;
  	$this->setTemplate('manualMembership');
    }
    public function executeDeleteManualPurchase(sfWebRequest $request)
    {
  	$this->authorizeUser();
  	global $CFG;
        if (!$id = $request->getParameter('id'))
  	{
            $CFG->current_app->gcError('No Purchase ID in $_GET', 'gcdatabaseerror');
  	}
  	else if (!$purchase = Doctrine::getTable('GcrPurchase')->find($id))
  	{
            $CFG->current_app->gcError('Purchase with ID ' . $id . ' does not exist', 'gcdatabaseerror');
  	}
  	else if (!$purchase->isManual())
  	{
            $CFG->current_app->gcError('Purchase with ID ' . $id . ' is not manual', 'gcdatabaseerror');
  	}
        $short_name = '';
  	$app = $purchase->getPurchaseTypeApp();
        if ($app)
        {
            $short_name = $app->getInstitution()->getShortName();
        }
  	$purchase->cleanDelete();
  	$this->redirect($CFG->current_app->getUrl() . '/account/view?eschool=' . $short_name);
    }
    public function executeAdminOptions(sfWebRequest $request)
    {
        $this->authorizeUser();
        global $CFG;
        $form = $request->getPostParameters();
        switch($form['admin_option'])
        {
            case 'manual_course':
                $action = '/homeadmin/manualCourse';
                break;
            case 'manual_classroom':
                $action = '/homeadmin/manualClassroom';
                break;
            case 'manual_eschool':
                $action = '/homeadmin/manualEschool';
                break;
            case 'manual_membership':
                $action = '/homeadmin/manualMembership';
                break;
            case 'manual_sale':
                $action = '/homeadmin/manualSale';
                break;
            case 'manual_payoff':
                $action = '/account/manualPayoff';
                break;
            case 'set_chained_payments':
                $action = '/homeadmin/setChainedPayments';
                break;
            case 'set_payment_info':
                $action = '/account/paymentInfo';
                break;   
        }
        $this->redirect($CFG->current_app->getUrl() . $action . '?eschool=' . 
                $form['admin_options_institution_id'] . '&user=' . $form['admin_options_user_id']);
    }
    public function executeSetChainedPayments(sfWebRequest $request)
    {
        $this->message = '';
        if (isset($_SESSION['chainedPaymentsSettingsSaved']))
        {
            $this->message = $_SESSION['chainedPaymentsSettingsSaved'];
            unset($_SESSION['chainedPaymentsSettingsSaved']);
        }
        $this->authorizeManualPurchaseForm($request);  
        $this->setChainedPaymentsVars();
    }
    public function executeDoSetChainedPayments(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeUser();
        $flag = false;
        $form = $request->getPostParameters();
        $institution = GcrInstitutionTable::getInstitution($form['chained_payments_institution_id']);
        $user = $institution->getUserById($form['chained_payments_user_id']);
        if ($form['use_chained_payments'] == 'on')
        {
            $flag = true;
        }
        $user->getAccountManager()->setChainedPaymentsAllowed($flag);
        $_SESSION['chainedPaymentsSettingsSaved'] = 'Saved';
        $this->redirect($CFG->current_app->getUrl() . '/homeadmin/setChainedPayments?eschool=' . 
                $form['chained_payments_institution_id'] . '&user=' . $form['chained_payments_user_id']);
    }
    public function executeUserSearch(sfWebRequest $request)
    {
        $this->authorizeUser();
        $this->institutions = GcrInstitutionTable::getInstitutions();
    }
    public function executeGetUserData(sfWebRequest $request)
    {
        $this->authorizeUser();
        $params = $request->getGetParameters();
        
	$sOrder = intval($params['iSortCol_0']);
     
        if ($params['bSortable_' . $sOrder] != "true")
        {
            $sOrder = 0;  
        }
            
	$search_string = false;
	if (isset($params['sSearch']) && strlen($params['sSearch']) > 2)
	{
            $search_string = $params['sSearch'];
            $s = '%' . strtolower($search_string) . '%';
            $sql_params = array($s, $s, $s, $s);
	}
	
	$aaData = array();
        $mhr_users = array();
        $totalDisplayRecords = 0;
        if ($search_string)
        {
            foreach (GcrInstitutionTable::getInstitutions() as $institution)
            {
                $institution_name = '<a href="' . $institution->getAppUrl() . 
                        '" target="_blank">' . $institution->getFullName() . '</a>';  
                
                $sql = 'select * from ' . $institution->getShortName() . '.mhr_usr ' . 
                        'where deleted < 1 and id > 1 and (lower(firstname) like ? or lower(lastname) like ? or lower(email) like ? or lower(username) like ?)';

                $mhr_users = $institution->gcQuery($sql, $sql_params);

                if ($mhr_users && count($mhr_users) > 0)
                {
                    foreach($mhr_users as $mhr_user)
                    {
                        $fullname = trim($mhr_user->lastname) . ', ' . trim($mhr_user->firstname);
                        $fullname_url = '<a href="' . $institution->getAppUrl() . 
                                'user/view?id=' . $mhr_user->id . '" target="_blank">' .
                                trim($mhr_user->lastname) . ', ' . trim($mhr_user->firstname) . '</a>';
                        $username = trim($mhr_user->username);
                        $username_url = '<a href="/account/view?eschool=' . $institution->getShortName() .
                            '&user=' . $mhr_user->id . '" target="_blank">' . $username . '</a>';

                        $email = trim($mhr_user->email);
                        $user_data = array($fullname_url, $username_url, $email, $institution_name, $mhr_user->lastaccess);
                        switch ($sOrder)
                        {
                            case 1: 
                                $key = $username;
                                break;
                            case 2:
                                $key = $email;
                                break;
                            case 3:
                                $key = $institution_name;
                                break;
                            case 4:
                                $key = $mhr_user->lastaccess;
                                break;
                            default:
                                $key = $fullname;
                        }
                        $aaData[strtolower($key)] = $user_data;
                    }
                }
            }
            $totalDisplayRecords = count($aaData);
            ksort($aaData);
            $aaData = array_values($aaData);
            if ($params['sSortDir_0'] == 'desc')
            {
                $aaData = array_reverse($aaData);
            }
            if (isset($params['iDisplayStart'] ) && $params['iDisplayLength'] != '-1')
            {
                $aaData = array_slice($aaData, $params['iDisplayStart'], $params['iDisplayLength']);   
            }
            
        }
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        
        $output = array(
		"sEcho" => intval($params['sEcho']),
		"iTotalRecords" => count($aaData),
		"iTotalDisplayRecords" => $totalDisplayRecords,
		"aaData" => $aaData
	);
        
        return $this->renderText(json_encode($output));
    }
    
    protected function setChainedPaymentsVars()
    {
        global $CFG;
        $this->institution = $this->eschool;
        $this->userArray = array();
        $this->uses_chained_payments_array = array();
        
        if ($this->institution->isInternal())
        {
            // we only allow chained payments on internal eclassrooms
            $users = $this->institution->getEclassroomUsers();
            foreach ($users as $user)
            {
                $this->setUserArrayItem($user);
            }
        }
        else
        {
            // we only allow chained payments to external owners
            $owner = $this->institution->getOwnerUser();
            $this->setUserArrayItem($owner);
            $this->userArray[$owner->getObject()->id] = 'Owner: ' . $this->userArray[$owner->getObject()->id];
        }
        asort($this->userArray);
    }
    
    protected function setUserArrayItem($user)
    {
        $mhr_user = $user->getObject();
        $uses_chained_payments = $user->getAccountManager()->getChainedPaymentsAllowed();
        $checked = '';
        if ($uses_chained_payments)
        {
            $checked = 'true';
        }
        $this->uses_chained_payments_array[$mhr_user->id] = $checked;
        if ($lastname = trim($mhr_user->lastname))
        {
            $lastname = $lastname . ', ';
        }
        $this->userArray[$mhr_user->id] =  $lastname . $mhr_user->firstname .
                ' (' . $mhr_user->email . ')';
    }
    protected function authorizeUser()
    {
        global $CFG;
        if (!($CFG->current_app->isHome() && $CFG->current_app->isMoodle()))
        {
            $CFG->current_app->gcError('Attempt to access homeadmin from outside home.', 'gcpageaccessdenied');
        }
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();
        if (!$this->current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError('Attempt to access homeadmin from non-admin account.', 'gcpageaccessdenied');
        }
    }

    protected function processForm($data, sfForm $form)
    {
        $form->bind($data);
        if ($form->isValid())
        {
            $form->save();
            return true;
        }
        return false;
    }
}
