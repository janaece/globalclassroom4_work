<?php

/**
 * account actions.
 *
 * @package    globalclassroom
 * @subpackage account
 * @author     Ron Stewart
 */
class accountActions extends sfActions
{
    private function setupTimePeriod(sfWebRequest $request)
    {
        global $CFG;
        if ($start_date = $request->getParameter('startdate'))
        {
            $ts = strtotime($start_date);
            if ($ts < gcr::startDateForApplication)
            {
                $ts = gcr::startDateForApplication;
            }
            $this->start_ts = $ts;
            $this->start_date = $start_date;
        }
        else
        {
            $days = 90;
            if ($CFG->current_app->hasPrivilege('GCStaff'))
            {
                $days = 15; // For GC staff, use 15 days default to speed up administration
            }
            $this->start_ts = time() - 60 * 60 * 24 * $days; // 90 days ago
        }
        if ($end_date = $request->getParameter('enddate'))
        {
            $ts = strtotime($end_date);
            if ($ts < $this->start_date)
            {
                $ts = time();
            }
            $this->end_ts = $ts;
            $this->end_date = $end_date;
        }
        else
        {
            $this->end_ts = time();
        }
    }

    public function executeWithdrawal(sfWebRequest $request)
    {
        $this->authorizeAccountAccess();
        global $CFG;
        $this->institution = $this->app->getInstitution();
        $account_manager = $this->user->getAccountManager();
        if (!$CFG->current_app->hasPrivilege('GCUser') && !$this->current_user->isSameUser($this->user))
        {
            $CFG->current_app->gcError('Non-privileged attempted access to ' .
                    $short_name . ' withdrawal with user ID ' . $this->user->getObject()->id, 'gcpageaccessdenied');
        }
        else if (!$account_manager->canRequestWithdrawal())
        {
            $CFG->current_app->gcError('User not premitted to request withdrawal: ' .
                    $short_name . ' withdrawal with user ID ' . $this->user->getObject()->id, 'gcpageaccessdenied');
        }
        $this->setPayoffCredentials();
        $this->setWithdrawalLimits();

        $this->payoffForm = new GcrPayoffForm(array(), array('max_withdrawal' => $this->max_withdrawal));
        $this->payoffForm->setDefaults
        (
            array
            (
                'amount'            => number_format($this->max_withdrawal, 2, '.', ''),
                'user_id'           => $this->user->getObject()->id,
                'eschool_id'        => $this->institution->getShortName(),
                'user_eschool_id'   => $this->user->getApp()->getShortName()
            )
        );
        $this->getResponse()->setTitle($this->institution->getFullName() . ' Account Withdrawal');
    }
    public function executeEditManualPayoff(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();
        if (!$this->current_user->getRoleManager()->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError('Non-privileged attempted access to manual payoff',
                    'gcpageaccessdenied');
        }
        $this->payoff_id = $request->getParameter('id');
        if (!$this->payoff_id)
        {
            $CFG->current_app->gcError('required parameter id missing, edit manual payoff error', 'gcdatabaserror');
        }
        if (!$payoff = Doctrine::getTable('GcrPayoff')->find($this->payoff_id))
        {
            $CFG->current_app->gcError('Payoff ID ' . $this->payoff_id .
                    ' does not exist.', 'gcdatabaseerror');
        }
        if (!$payoff->isManual())
        {
            $CFG->current_app->gcError('Payoff ID ' . $this->payoff_id .
                    ' is not a manual payoff.', 'gcdatabaseerror');
        }
        if (!$purchase = $payoff->getPurchase())
        {
            $CFG->current_app->gcError('Payoff ID ' . $payoff->getId() .
                    ' purchase ID ' . $payoff->getPurchaseId() . ' does not exist.', 'gcdatabaseerror');
        }
        if (!$this->credentials = $payoff->getCredentials())
        {
            $CFG->current_app-gcError('Payoff ID ' . $payoff->getId() .
                    ' has no credentials', 'gcdatabaserror');
        }
        $this->institution = GcrInstitutionTable::getInstitution($payoff->getEschoolId());
        $user_institution = GcrInstitutionTable::getInstitution($payoff->getUserEschoolId());
        $this->user = $user_institution->getUserById($payoff->getUserId());
        if (!$this->user)
        {
            $CFG->current_app->gcError('User with ID ' . $payoff->getUserId() .
                    ' does  not exist on eSchool ' . $payoff->getEschoolId(), 'gcdatabaseerror');
        }
        $this->setWithdrawalLimits();

        $this->payoff_form = new GcrPayoffManualForm();
        if ($payoff->isManualCheckPayment())
        {
            if (!$address = $payoff->getAddressObject())
            {
                $CFG->current_app->gcError('Payoff ID ' . $payoff->getId() .
                        ' has non-existant address value');
            }
            $this->payoff_form->setDefaults
            (
                array
                (
                    'street1'           => $address->getStreet1(),
                    'street2'           => $address->getStreet2(),
                    'city'              => $address->getCity(),
                    'state'             => $address->getState(),
                    'country'           => $address->getCountry(),
                    'zipcode'           => $address->getZipcode(),
                    'amount'            => number_format($payoff->getAmount(), 2, '.', ''),
                    'user_id'           => $payoff->getUserId(),
                    'eschool_id'        => $payoff->getEschoolId(),
                    'user_eschool_id'   => $payoff->getUserEschoolId(),
                    'transtime'         => $payoff->getTransTime(),
                    'reference_id'      => $purchase->getProfileId(),
                    'description'       => $purchase->getPurchaseTypeDescription(),
                    'type'              => 'check'
                )
            );
        }
        else
        {
            $this->payoff_form->setDefaults
            (
                array
                (
                    'amount'            => number_format($payoff->getAmount(), 2, '.', ''),
                    'user_id'           => $payoff->getUserId(),
                    'eschool_id'        => $payoff->getEschoolId(),
                    'user_eschool_id'   => $payoff->getUserEschoolId(),
                    'transtime'         => $payoff->getTransTime(),
                    'reference_id'      => $purchase->getProfileId(),
                    'description'       => $purchase->getPurchaseTypeDescription(),
                    'type'              => 'paypal'
                )
            );
        }
        $this->setTemplate('manualPayoff');
        $this->getResponse()->setTitle('Edit Manual Account Withdrawal');
    }
    protected function setPayoffCredentials()
    {
        $this->credentials = $this->user->getAccountManager()->getPayoffCredentials();
        if ((!$this->credentials) || $this->credentials->getVerifyStatus() != 'verified')
        {
            global $CFG;
            $this->redirect($CFG->current_app->getUrl() . '/account/paymentInfo?eschool=' .
                    $this->user->getApp()->getShortName() . '&user=' . $this->user->getObject()->id);
        }
    }
    public function executeManualPayoff(sfWebRequest $request)
    {
        $this->authorizeAccountAccess();
        if (!$this->gc_admin)
        {
            $CFG->current_app->gcError('Non-privileged attempted access to manual payoff',
                    'gcpageaccessdenied');
        }
        $this->institution = $this->app;
        $this->setPayoffCredentials();
        $this->setWithdrawalLimits();
        $this->payoff_form = new GcrPayoffManualForm();
        $this->payoff_form->setDefaults
        (
            array
            (
                'amount'		=> number_format($this->max_withdrawal, 2, '.', ''),
                'user_id'		=> $this->user->getObject()->id,
                'eschool_id'            => $this->institution->getShortName(),
                'user_eschool_id'       => $this->user->getApp()->getShortName(),
                'transtime'		=> time(),
            )
        );
    }
    public function executeDoManualPayoff(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();

        if (!$this->current_user->getRoleManager()->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError('Non-privileged attempted access to ' . $form['user_eschool_id'] .
                    ' createWithdrawal with user ID ' . $form['user_id'], 'gcpageaccessdenied');
        }

        $form = $request->getPostParameters();

        if ($form['payoff_id'] != '')
        {
            if (!$this->payoff = Doctrine::getTable('GcrPayoff')->find($form['payoff_id']))
            {
                $CFG->current_app->gcError('Payoff ID: ' . $form['id'] .
                        ' does not exist', 'gcdatabaseerror');
            }
            if (!$this->payoff->isManual())
            {
                $CFG->current_app->gcError('Payoff ID ' . $this->payoff->getId() .
                        ' is not of type manual', 'gcdatabaseerror');
            }
            $this->institution = GcrInstitutionTable::getInstitution($this->payoff->getEschoolId());
            $user_institution = GcrInstitutionTable::getInstitution($this->payoff->getUserEschoolId());
            
            if (!$this->user = $user_institution->getUserById($this->payoff->getUserId()))
            {
                $CFG->current_app->gcError('Local User with ID ' . $this->payoff->getUserId() .
                        ' on eschool ' . $user_institution->getShortName() .
                        ' does not exist', 'gcdatabaseerror');
            }
            if (!$this->credentials = $this->payoff->getCredentials())
            {
                $CFG->current_app-gcError('Payoff ID ' . $this->payoff->getId() .
                        ' has no credentials', 'gcdatabaserror');
            }
            if (!$purchase = $this->payoff->getPurchase())
            {
                $CFG->current_app->gcError('Payoff ID ' . $this->payoff->getId() .
                        ' purchase ID ' . $this->payoff->getPurchaseId() .
                        ' does not exist.', 'gcdatabaseerror');
            }
        }
        else
        {
            $this->institution = GcrInstitutionTable::getInstitution($form['eschool_id']);
            $user_institution = GcrInstitutionTable::getInstitution($form['user_eschool_id']);
            
            if (!$this->user = $user_institution->getUserById($form['user_id']))
            {
                $CFG->current_app->gcError('Local User with ID ' . $form['user_id'] .
                        ' on eschool ' . $user_institution->getShortName() .
                        ' does not exist', 'gcdatabaseerror');
            }
            if (!$this->credentials = $this->user->getAccountManager()->getPayoffCredentials())
            {
                $CFG->current_app->gcError('No payoff credentials found for ' . $form['user_eschool_id'] .
                        ' createWithdrawal with user ID ' . $form['user_id'], 'gcpageaccessdenied');
            }
            $this->payoff = new GcrPayoff();
            $this->payoff->setUserId($this->user->getObject()->id);
            $this->payoff->setUserEschoolId($form['user_eschool_id']);
            $this->payoff->setEschoolId($form['eschool_id']);
            $this->payoff->setPayoffStatus('completed');
            $this->payoff->setCredentialsId($this->credentials->getId());
        }
        unset($form['payoff_id']);
        $manual_payoff_form = new GcrPayoffManualForm();
        $manual_payoff_form->bind($form);
        if ($manual_payoff_form->isValid())
        {
            $this->payoff->setAmount($form['amount']);
            $form['transtime'] = GcrPurchaseTable::convertDatetoTimestamp($form['transtime']);
            $this->payoff->setTransTime($form['transtime']);
            $this->payoff->setPayoffType($this->user->getAccountManager()->getPayoffType() . '_manual');
            
            $url = GcrEschoolTable::getHome()->getUrl() . '/account/view?eschool=' .
                        $this->institution->getShortName() . '&user=' . $this->user->getObject()->id;
            if ($form['type'] == 'check')
            {
                if ($this->payoff->isManualCheckPayment())
                {
                    if (!$address = $this->payoff->getAddressObject())
                    {
                        $CFG->current_app->gcError('Payoff ID ' .
                                $this->payoff->getId() . ' has non-existant address value');
                    }
                }
                else
                {
                    $address = new GcrAddress();
                }
                $address->setStreet1($form['street1']);
                $address->setStreet2($form['street2']);
                $address->setCity($form['city']);
                $address->setZipcode($form['zipcode']);
                $address->setState($form['state']);
                $address->setCountry($form['country']);
                $address->save();
                $this->payoff->setAddress($address->getId());
            }
            if ($form['description'] == '')
            {
                $description = 'Manual Account Withdrawal';
            }
            else
            {
                $description = $form['description'];
            }
            if ($purchase)
            {
                $purchase->setPurchaseTypeDescription($description);
                $purchase->setAmount($this->payoff->getAmount());
                $purchase->setTransTime($form['transtime']);
                $purchase->setProfileId($form['reference_id']);
                $purchase->save();
            }
            else
            {
                $purchase = $this->payoff->createPurchaseRecord($description, 
                        $form['transtime'], $form['reference_id']);
            }
            $this->payoff->save();
            $purchase->updateRelatedAccounting();

            $this->redirect($url);
        }
        $this->payoff_id = $this->payoff->getId();
        $this->payoff_form = $manual_payoff_form;
        $this->setTemplate('manualPayoff');
    }
    private function setWithdrawalLimits()
    {
        $account_manager = $this->user->getAccountManager();
        $error = false;
        if (!$this->account_balance = $account_manager->getAccountBalance())
        {
            $error = 'No account balance stored for user ';
        }
        if (!$this->max_withdrawal = $account_manager->getMaxWithdrawalAmount())
        {
            $error = 'Maximum withdrawal amount not set for user ';   
        }
        if ($error)
        {
            global $CFG;
            $CFG->current_app->gcError($error . $this->user->getObject()->id . ' on ' . 
                    $this->user->getApp()->getShortName() . ' withdrawal error', 'gcpageaccessdenied');
        }
        $this->min_balance = $account_manager->getMinAccountBalance();
    }
    public function executePaymentInfo(sfWebRequest $request)
    {
        global $CFG;
        $this->authorizeAccountAccess();
        $this->institution = $this->app;
        $user_id = $this->user->getObject()->id;
        if ($id = $this->request->getParameter('id'))
        {
            // Only GC Users are allowed to edit credentials by ID. 
            if (!$this->current_user->getRoleManager()->hasPrivilege('GCUser'))
            {
                $CFG->current_app->gcError('Non-privileged attempted access to edit ' .
                        $short_name . ' paymentInfo with user ID ' . $user_id, 'gcpageaccessdenied');
            }
            if (!$credentials = Doctrine::getTable('GcrPayoffCredentials')->find($id))
            {
                $CFG->current_app->gcError('Credentials with ID ' . $id .
                        ' does not exist.', 'gcdatabaseerror');
            }
            $this->payoff_credentials_form = new GcrPayoffCredentialsForm($credentials);
        }
        else
        {
            // We always create a new credentials record rather than editing the old one
            // in order to maintain historical data about withdrawals.
            $this->payoff_credentials_form = new GcrPayoffCredentialsForm();
            $credentials = $this->user->getAccountManager()->getPayoffCredentials();
            if ($credentials)
            {
                $this->payoff_credentials_form->setDefaults
                (
                    array
                    (
                        'user_id' => $user_id,
                        'user_eschool_id' => $this->institution->getShortName(),
                        'user_first_name' => $credentials->getUserFirstName(),
                        'user_last_name' => $credentials->getUserLastName(),
                        'user_business_name' => $credentials->getUserBusinessName(),
                        'user_tin' => $credentials->getUserTin(),
                        'user_paypal_email' => $credentials->getUserPaypalEmail()
                    )
                );
            }
            else
            {
                $mhr_user_obj = $this->user->getObject();
                $this->payoff_credentials_form->setDefaults
                (
                    array
                    (
                        'user_id' => $user_id,
                        'user_eschool_id' => $this->institution->getShortName(),
                        'user_first_name' => $mhr_user_obj->firstname,
                        'user_last_name' => $mhr_user_obj->lastname,
                        'user_paypal_email' => $mhr_user_obj->email
                    )
                );
            }
        }
        $this->return_url = $CFG->current_app->getUrl() . '/account/view?eschool=' . 
                $this->institution->getShortName() . '&user=' . $this->user->getObject()->id;
        
        $this->getResponse()->setTitle('Update Payment Information Profile');
    }
    public function executeCreateWithdrawal(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();

        $form = $request->getPostParameters();
        $this->institution = GcrInstitutionTable::getInstitution($form['eschool_id']);
        $user_institution = GcrInstitutionTable::getInstitution($form['user_eschool_id']);
        $this->user = $user_institution->getUserById($form['user_id']);
        if (!$this->user)
        {
            $CFG->current_app->gcError('Local User with ID ' . $form['user_id'] .
                    ' on eschool ' . $user_institution->getShortName() . ' does not exist', 'gcdatabaseerror');
        }
        $account_manager = $this->user->getAccountManager();
        if (!$this->current_user->getRoleManager()->hasPrivilege('GCUser') && 
                !$this->current_user->isSameUser($this->user))
        {
            $CFG->current_app->gcError('Non-privileged attempted access to ' . $form['user_eschool_id'] .
                    ' createWithdrawal with user ID ' . $form['user_id'], 'gcpageaccessdenied');
        }
        $withdrawal = $account_manager->getPendingWithdrawal();
        if ($withdrawal)
        {
            $CFG->current_app->gcError('Attempted duplicate withdrawal for ' . $form['user_eschool_id'] .
                    ' createWithdrawal with user ID ' . $form['user_id'], 'attemptedduplicatewithdrawal');
        }
        if (!$account_manager->canRequestWithdrawal())
        {
            $CFG->current_app->gcError('Attempt to request a withdraw before interval ' . $this->user->getApp()->getShortName() .
                    ' user ID ' . $this->user->getObject()->id, 'gcdatabaseerror');
        }
        if (!$credentials = $account_manager->getPayoffCredentials())
        {
            $CFG->current_app->gcError('No payoff credentials found for ' . $form['user_eschool_id'] .
                    ' createWithdrawal with user ID ' . $form['user_id'], 'gcpageaccessdenied');
        }
        $url = $CFG->current_app->getUrl() . '/account/view?eschool=' .
                    $this->institution->getShortName() . '&user=' . $this->user->getObject()->id;
        
        $this->setWithdrawalLimits();
        if ($form['amount'] > $this->max_withdrawal)
        {
            $CFG->current_app->gcError('Amount of ' . $form['amount'] . ' higher than max-withdrawal of ' .
                    $this->max_withdrawal . ' for ' . $form['user_eschool_id'] .
                    ' createWithdrawal with user ID ' . $form['user_id'], 'withdrawalamounttoohigh');
        }
        $payoff = new GcrPayoff();
        $payoff->setTransTime(time());
        $payoff->setUserId($form['user_id']);
        $payoff->setUserEschoolId($form['user_eschool_id']);
        $payoff->setEschoolId($form['eschool_id']);
        $payoff->setAmount($form['amount']);
        $payoff->setPayoffType($account_manager->getPayoffType());
        $payoff->setCredentialsId($credentials->getId());
        $payoff->save();

        $this->redirect($url);
    }
    public function executeCreatePaymentInfo(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();
        $role_manager = $this->current_user->getRoleManager();
        
        $form = $request->getPostParameters();
        if ($form['id'] != '')
        {
            if (!$role_manager->hasPrivilege('GCUser'))
            {
                $CFG->current_app->gcError('Non-privileged attempted access to edit ' .
                        $form['user_eschool_id'] . ' createPaymentInfo with user ID ' .
                        $form['user_id'], 'gcpageaccessdenied');
            }
            if (!$credentials = Doctrine::getTable('GcrPayoffCredentials')->find($form['id']))
            {
                $CFG->current_app->gcError('Credentials with ID ' . $form['id'] .
                        ' does not exist.', 'gcdatabaseerror');
            }
            $this->payoff_credentials_form = new GcrPayoffCredentialsForm($credentials);
        }
        else
        {
            $this->payoff_credentials_form = new GcrPayoffCredentialsForm();
        }
        $institution = GcrInstitutionTable::getInstitution($form['user_eschool_id']);
        if (!$user = $institution->getUserById($form['user_id']))
        {
            $CFG->current_app->gcError('Local User with ID ' . $form['user_id'] .
                    ' on eschool ' . $institution->getShortName() . ' does not exist', 'gcdatabaseerror');
        }
        $account_manager = $user->getAccountManager();
        
        // If this isn't a gc admin, we need to check that they aren't trying to change someone else's credentials
        if (!$role_manager->hasPrivilege('GCUser'))
        {
            if (!$this->current_user->isSameUser($user))
            {
                $CFG->current_app->gcError('Non-privileged attempted access to ' .
                        $form['user_eschool_id'] . ' createPaymentInfo with user ID ' .
                        $form['user_id'], 'gcpageaccessdenied');
            }
            if ($account_manager->usesChainedPayments())
            {
                $CFG->current_app->gcError('User cannot change payoff credentials with chained payments enabled.', 
                        'gcchainedpaymentcredentials');
            }
            $form['verify_status'] = 'unverified';
        }
        else // otherwise, we can skip the validation process
        {
            $form['verify_status'] = 'verified';
        }
        $form['verify_hash'] = GcrEschoolTable::generateRandomString();
        $this->payoff_credentials_form->bind($form);
        if ($this->payoff_credentials_form->isValid())
        {
            $old_credentials = $account_manager->getPayoffCredentials();
            $payoff_credentials = $this->payoff_credentials_form->save();
            if ($old_credentials && $form['id'] == '')
            {
                $old_credentials->setVerifyStatus('expired');
                $old_credentials->save();
            }
            if ($form['verify_status'] == 'unverified')
            {
                $subject = 'Verification of Global Classroom Payment Information';
                $params = array('institution' => $institution, 'credentials' => $payoff_credentials);
                $email = new GcrUserEmailer('verify_payoff_credentials', $user, $subject, $params);
                $email->sendHtmlEmail();
            }
            $this->redirect($CFG->current_app->getUrl() . '/account/newPaymentInfo?id=' .
                    $payoff_credentials->getId());
        }
        $this->setTemplate('paymentInfo');
    }
    public function executeNewPaymentInfo(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();
        if (!$id = $request->getParameter('id'))
        {
            $CFG->current_app->gcError('Missing Payoff Credentials ID parameter', 'gcdatabaseerror');
        }
        if (!$this->credentials = Doctrine::getTable('GcrPayoffCredentials')->find($id))
        {
            $CFG->current_app->gcError('PayoffCredentials ID ' . $id . ' does not exist', 'gcdatabaseerror');
        }
        $this->institution = GcrInstitutionTable::getInstitution($this->credentials->getUserEschoolId());
        if (!$this->user = $this->institution->getUserById($this->credentials->getUserId()))
        {
            $CFG->current_app->gcError('User ID ' . $this->credentials->getUserId() .
                    ' Does not exist for PayoffCredentials ID ' . $id, 'gcdatabaseerror');
        }
        if (!$this->current_user->isSameUser($this->user) && 
                !$this->current_user->getRoleManager()->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError('Non-privileged attempted access to ' .
                    $this->institution->getShortName() . ' newPaymentInfo with user ID ' .
                    $this_user->getObject()->id, 'gcpageaccessdenied');
        }
        $verified = false;
        if ($CFG->current_app->hasPrivilege('GCUser'))
        {
            $verified = true;
        }
        if ($this->credentials->getVerifyStatus() != 'verified')
        {
            if ($verify_hash = $request->getParameter('verify'))
            {
                if ($verify_hash == $this->credentials->getVerifyHash())
                {
                    $verified = true;
                }
                else
                {
                    $CFG->current_app->gcError('Verify hash ' . $verify_hash .
                            ' does not match for PayoffCredentials with ID ' . $id, 'gcdatabaseerror');
                }
            }
        }
        if ($verified)
        {
            $this->credentials->setVerifyStatus('verified');
            $this->credentials->save();
        }
        $this->getResponse()->setTitle('Payment Information Profile Verification');
    }
    public function executeView(sfWebRequest $request)
    {
        global $CFG;
        $this->owner = $CFG->current_app->hasPrivilege('EschoolAdmin');
        $this->authorizeAccountAccess();
        if ($this->user->getRoleManager()->hasPrivilege('Owner'))
        {
            $this->getResponse()->setTitle(trim($this->app->getFullName()) . ': Platform Transaction History');
            $this->setTemplate('eschool');
        }
        else
        {
            $this->getResponse()->setTitle($this->user->getObject()->firstname . ' ' .
                $this->user->getObject()->lastname . ' Account Transaction History');
            $this->setTemplate('user');
        }
        $this->setupTimePeriod($request);
        $this->account_table = $this->user->getAccountManager()->getAccountTable($this->start_ts,
                $this->end_ts, $this->gc_admin, $this->owner);
    }
    // This function sets properties for most calls to accounting pages:
    // 
    // gc_admin - flag for homeadmin access with GCUser privilege level
    // owner - flag for owner level access (this is used to choose the table display perspective
    // app - the institution which owns the platform associated with the account
    protected function authorizeAccountAccess()
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $this->current_user = $CFG->current_app->getCurrentUser();
        $this->gc_admin = false;
        if (!isset($this->owner))
        {
            $this->owner = $CFG->current_app->hasPrivilege('Owner');
        }
        
        // Check for homeadmin access to accounting where the schema can be set as
        // a parameter
        if ($CFG->current_app->isHome() && $CFG->current_app->isMoodle())
        {
            if (!$CFG->current_app->hasPrivilege('GCUser'))
            {
                $CFG->current_app->gcError('Non-privileged attempted access to startadmin accounting page', 'gcpageaccessdenied');
            }    
            $this->gc_admin = true; 
            $short_name = $this->request->getParameter('eschool');
            if ($short_name)
            {
                $this->app = GcrInstitutionTable::getInstitution($short_name, true);
            }
        }
        else
        {
            $CFG->current_app->requireMahara();
        }
        
        // Only allow access to current_app for normal access to accounting (not startadmin)
        if (!isset($this->app) || (!$this->app))
        {
            $this->app = $CFG->current_app->getInstitution();
        }
        
        // Check for owner privilege level. If so, we allow a user parameter to see
        // other users' accounts on the platform
        if ($this->owner)
        {
            $user_id = $this->request->getParameter('user');
            if ($user_id)
            {
                $this->user = $this->app->getUserById($user_id);
            }
            else
            {
                // We set the owner as $this->user for gcUsers who
                // are administering the platform.
                $this->user = $this->app->getOwnerUser();
            }
        }
        
        // Default to showing current user's account.
        if (!$this->user)
        {
            $this->user = $this->current_user;
        }
    }
}
?>