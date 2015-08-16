<?php
// Class IpnHandler
// Created by Ron Stewart
// Feb 23, 2011
//
// This class handles incoming IPN notifications from Paypal which tell us when activity has occured on
// our business account. Possible IPNs include: One-time purchase, recurring payment was charged, recurring
// payment profile was created, or a refund was given for a previous transaction.
// NOTE: This function posts errors to debug/paypal.log rather than debug/error.log and hence does not use
// the function gcError().
// 
// Updated Jan 26, 2012:
// Paypal Adaptive Payments have been added as a new IPN message type. In these cases, the 
// $CFG->current_app will be the same as the GcrPurchase::getPurchaseTypeApp().

class IpnHandler
{
    protected $paypal_data;
    protected $req;
    protected $paypal_record;
    protected $purchase;

    public function getPaypalData($key)
    {
        return $this->paypal_data[$key];
    }

    public function getReq()
    {
        return $this->req;
    }

    public function getRecurringPaymentId()
    {
        if (isset($this->paypal_data['recurring_payment_id']) && $this->paypal_data['recurring_payment_id'] != '')
        {
            return $this->paypal_data['recurring_payment_id'];
        }
        return false;
    }

    // This code was taken from https://cms.paypal.com/cms_content/US/en_US/files/developer/IPN_PHP_41.txt
    // as a code example for IPN handling with PHP.
    // PHP 4.1
    public function processIpn($data_array = array())
    {
        // Ron Stewart: Jan 30, 2012
        // The raw post data processing below is required in order to process some of
        // Paypal's IPN message types. In cases like Adaptive payments, with
        // more than one payment receiver, the key-value pairs include keys with
        // subscripts to denote which receiver is being referenced 
        // i.e. "transaction[0].id"
        // PHP currently does not accept subscripts as part of a $_POST parameter key name, and
        // ends up disregarding such key-value pairs. 
        // Therefore, I needed to do it manually in the code below.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $_YOUR_POST = array();
        
        foreach ($raw_post_array as $keyval) 
        {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
            {
                $_YOUR_POST[urldecode($keyval[0])] = urldecode($keyval[1]);
            }    
        }
        if (count($_YOUR_POST) < 3) 
        {
            $_YOUR_POST = $data_array;
            $original_post_used = TRUE;    
        }
        else
        {
            $original_post_used = FALSE;
        }
        // Build final $_req postback request
        // Paypal's IPN Sample
        // read the post from PayPal system and add 'cmd'
        if ($original_post_used) 
        {
            $_req = 'cmd=_notify-validate';
            foreach ($_YOUR_POST as $key => $value) 
            {
                $key = urlencode(stripslashes($key));
                $value = urlencode(stripslashes($value));
                $_req .= "&$key=$value";    
            }    
        }
        else
        {
            $_req = $raw_post_data . '&cmd=_notify-validate';
        }
        // $_req is ready for postback to Paypal here...
        $data_array = $_YOUR_POST;
        $this->req = $_req;
        
        if (gcr::paypalSandbox)
        {
            $paypal_url = 'ssl://www.sandbox.paypal.com';
            $gc_receiver_email = gcr::API_EMAIL_SB;
        }
        else
        {
            $paypal_url = 'ssl://www.paypal.com';
            $gc_receiver_email = gcr::API_EMAIL;
        }

        foreach ($data_array as $key => $value)
        {
            $this->paypal_data[$key] = $value;
        }
        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($this->req) . "\r\n\r\n";
        $fp = fsockopen ($paypal_url, 443, $errno, $errstr, 30);

        if (!$fp)
        {
            $this->logMessage('Could not verify transaction, http error');
        }
        else
        {
            fputs ($fp, $header . $this->req);
            while (!feof($fp))
            {
                $res = fgets ($fp, 1024);
                if (strcmp ($res, "VERIFIED") == 0)
                {               
                    $this->processRequest();
                }
                else if (strcmp ($res, "INVALID") == 0)
                {
                    $this->logMessage('Transaction Invalid.');
                }
            }
            fclose ($fp);
        }
    }

    protected function logMessage($error_string)
    {
        $backtrace = debug_backtrace();
        error_log("\n" . date('d/m/Y H:i:s', time()) . ': FUNCTION: ' . $backtrace[1]['function'] .
                ' MESSAGE: ' . $error_string . ': DATA: ' . $this->req, 3, gcr::rootDir . 'debug/paypal.log');
    }
    protected function processRequest()
    {
        if (isset($this->paypal_data['txn_id']))
        {
            $existing_record = GcrPaypalTable::getInstance()->find($this->paypal_data['txn_id']);
        }
        else
        {
            $existing_record = false;
        }
        if (isset($this->paypal_data['action_type']) && 
                ($this->paypal_data['action_type'] == 'CREATE') &&
                $this->paypal_data['status'] == 'COMPLETED')
        {
            $this->processChainedPayment();
        }
        else if ($existing_record)
        {
            $this->logMessage('Duplicate IPN (paypal txn_id already exists)');
        }
        else if ($this->paypal_data['payment_status'] == 'Completed')
        {
            if ($this->getRecurringPaymentId())
            {
                $this->processRecurringPayment();
            }
            else
            {
                $this->processSinglePayment();
            }
        }
        else if ($this->paypal_data['payment_status'] == 'Refunded')
        {
            $this->processRefund();
        }
        else if ($this->paypal_data['txn_type'] == 'recurring_payment_profile_created')
        {
            $this->logMessage('Recurring Payment Profile Created');
        }
        else
        {
            $this->logMessage('Unhandled Transaction Type');
        }
    }
    protected function processChainedPayment()
    {
        // NOTE: Adaptive payments IPNs are sent to: 
        // https://<GcrPurchase::getPurchaseTypeApp()>.globalclassroom.us/purchase/ipn 
        // instead of: 
        // https://startadmin.globalclassroom.us/purchase/ipn
        // This is so that we can use the Moodle library to enrol users.
        $this->purchase = Doctrine::getTable('GcrPurchase')->find($this->paypal_data['tracking_id']);
        if ($this->purchase)
        {
            if ($this->purchase->getProfileId() == GcrPaypalTable::TXN_PENDING)
            {
                $this->processNewChainedPayment();
            }
            else
            {
                $this->processChainedPaymentRefund();
            }
        }
        else
        {
            $this->logMessage('No corresponding purchase found for chained payment.');
        }      
    }
    protected function processNewChainedPayment()
    {
        // Update the GcrPurchase record by linking it to the paypal txn_id.
        $this->purchase->setProfileId($this->paypal_data['transaction[0].id']);
        $this->purchase->save();
        $receivers = GCPurchasePaypalCourseChained::getReceivers($this->purchase);
        for ($i = 1; $i < count($receivers); $i++)
        {
            if (strtoupper($this->paypal_data["transaction[$i].status"]) != 'COMPLETED')
            {
                continue;
            }
            // Create new GcrPayoff record to account for the automatic transfer to the eschool owner
            // for chained payments
            $payoff = new GcrPayoff();
            $payoff->setTransTime(time());
            $institution = $receivers[$i]->user->getApp();
            $account_manager = $receivers[$i]->user->getAccountManager();
            if ($account_manager->isOwner())
            {
                $payoff_type = 'eschool';
            }
            else
            {
                $payoff_type = 'classroom';
            }
            $payoff->setUserId($receivers[$i]->user->getObject()->id);
            $payoff->setUserEschoolId($institution->getShortName());
            $payoff->setEschoolId($institution->getShortName());
            $amount = $this->parseAmountFromCurrencyString($this->paypal_data["transaction[$i].amount"]);
            $payoff->setAmount($amount);
            $payoff->setPayoffStatus('completed');
            $payoff->setPayoffType($payoff_type);
            $payoff->setCredentialsId($account_manager->getPayoffCredentials()->getId());
            $payoff->save();

            // Create a corresponding purchase record (this record is what our accounting uses) 
            // for the new GcrPayoff 
            $purchase = $payoff->createPurchaseRecord('Automatic Payment for Transaction #' . $this->purchase->getId(), 
                    time(), $this->paypal_data["transaction[$i].id"]);
            $purchase->updateRelatedAccounting();
        }
        
        // With Apaptive payments we don't know if the transaction went through
        // until this IPN is receieved. Therefore, we have to wait until now to
        // provide whatever was purchased.
        $this->providePurchasedUtility();
        
    }
    // This function is called if we needed to wait until receiving IPN confirmation
    // before providing whatever the customer paid for.
    protected function providePurchasedUtility()
    {
        if ($this->purchase->getPurchaseType() == 'course')
        {
            global $CFG;
            $course = $CFG->current_app->getCourse($this->purchase->getPurchaseTypeId());
            if ($course)
            {
                $mdl_course = $course->getObject();
                $local_user = $this->purchase->getPurchaserUser()->getUserOnEschool($CFG->current_app);
                if ($local_user)
                {
                    $CFG->current_app->enrolUserInCourse($mdl_course, $local_user->getObject());
                }
                else
                {
                    $this->logMessage('Failed to enrol user in course. Purchase ID#' .
                        $this->purchase->getId() . ' no corresponding user found on eschool ' . $CFG->current_app->getShortName());
                }
            }
            else
            {
                $this->logMessage('Failed to enrol user in course. Course ID#' .
                        $this->purchase->getPurchaseTypeId() . ' not found on eschool ' . $CFG->current_app->getShortName());
            }
            $this->purchase->emailInfoToGC(array('product_info' => "Course: $mdl_course->fullname ($mdl_course->shortname)"));
        }
    }
    protected function processChainedPaymentRefund()
    {
        $refund_was_processed = false;
        for ($i = 1; $i < 3; $i++)
        {
            $status = '';
            if (isset($this->paypal_data["transaction[$i].status"]))
            {
                $status = $this->paypal_data["transaction[$i].status"];
            }
            if ($status == 'Refunded')
            {
                $amount = $this->parseAmountFromCurrencyString($this->paypal_data["transaction[$i].refund_amount"]);
                $purchase = Doctrine::getTable('GcrPurchase')->findOneByProfileId($this->paypal_data["transaction[$i].id"]);

                if ($purchase && $amount && 
                        ($purchase->getPurchaseType() == 'payoff') && 
                        ($purchase->getAmount() == $amount))
                {
                    $payoff = Doctrine::getTable('GcrPayoff')->find($purchase->getPurchaseTypeId());
                    $purchase->delete();
                    $payoff->delete();
                    $refund_was_processed = true;
                }
            }
        }
        if (!$refund_was_processed)
        {
            $this->logMessage('Chained Payment Refund Failed.');
        }
    }
    protected function parseAmountFromCurrencyString($string)
    {
        $currency = strpos($string, 'USD');
        if ($currency !== false)
        {
            $amount = substr($string, $currency + 4);
            return $amount;
        }
        return false;
    }
    protected function processSinglePayment()
    {
        $this->purchase = Doctrine::getTable('GcrPurchase')->findOneByProfileId($this->paypal_data['txn_id']);
        $this->hydratePaypalRecord();
        $this->commitPaypalRecord();
    }
    protected function processRefund()
    {
        if ($paypal = Doctrine::getTable('GcrPaypal')->find($this->paypal_data['parent_txn_id']))
        {
            if (!$purchase_table_profile_id = $this->getRecurringPaymentId())
            {
                $purchase_table_profile_id = $this->paypal_data['parent_txn_id'];
            }
            if ($this->purchase = Doctrine::getTable('GcrPurchase')->findOneByProfileId($purchase_table_profile_id))
            {
                $this->hydratePaypalRecord();
                $this->commitPaypalRecord();
            }
            else
            {
                $this->logMessage('No corresponding purchase found for refund.');
            }
        }
        else
        {
            $this->logMessage('No parent payment found for this refund');
        }
    }
    protected function processRecurringPayment()
    {
        if ($this->purchase = Doctrine::getTable('GcrPurchase')->findOneByProfileId($this->paypal_data['recurring_payment_id']))
        {
            $this->hydratePaypalRecord();
            $this->paypal_record->setGcFee($this->purchase->getGcFee());
            $this->commitPaypalRecord();

            if ($profile = PaypalTable::getRecurringProfile($this->paypal_data['recurring_payment_id']))
            {
                $this->sendRecurringReceiptEmail($profile);
            }
            else
            {
                $this->logMessage('Cannot get recurring profile data from Paypal.');
            }
        }
        else
        {
            $this->logMessage('No corresponding purchase found.');
        }
    }
    protected function sendRecurringReceiptEmail($profile)
    {
        // Text alterations based upon billing cycle of annual or monthly
        $cycle = $this->purchase->getBillCycle();
        if ($cycle == 'Year')
        {
            $cycle_text1 = 'an annual';
            $cycle_text2 = 'your subscription will be renewed in one year at the published subscription rate at the time';
        }
        else
        {
            $cycle_text1 = 'a monthly';
            $cycle_text2 = 'your credit card will be charged each month';
        }
        // Text alterations based upon purchase type
        $type = $this->purchase->getPurchaseType();
        if ($type == 'eschool')
        {
            $type_text1 = 'eSchool';
            $type_text2 = 'for eSchool';
        }
        else if ($type == 'classroom')
        {
            $type_text1 = 'eClassroom';
            $type_text2 = 'for an eClassroom on';
        }
        else if ($type == 'membership')
        {
            $type_text1 = 'Membership';
            $type_text2 = 'for a membership on';
        }
        else
        {
            $this->logMessage('Invalid Type for Recurring Payment (sendRecurringInvoiceEmail)');
            return;
        }
        $mhr_user = $this->purchase->getPurchaserUser();
        // put data in array to pass to gcEmail object
        $params = array('type_text1' => $type_text1,
                        'type_text2' => $type_text2,
                        'cycle_text1' => $cycle_text1,
                        'cycle_text2' => $cycle_text2,
                        'eschool_short_name' => $mhr_user->getApp()->getShortName(),
                        'eschool_full_name' => $mhr_user->getApp()->getFullName(),
                        'creditCardString' => GcrPaypalTable::getCreditCardString($profile),
                        'amountCharged' => number_format($this->paypal_data['mc_gross'], 2));
        $email = new GcrUserEmailer('purchase_renewal', $mhr_user, "Your $type_text1 Subscription Has Been Renewed", $params);
        $email->sendHtmlEmail();
    }
    protected function hydratePaypalRecord($paypal_data = false)
    {
        if (!$paypal_data)
        {
            $paypal_data = $this->paypal_data;
        }
        $this->paypal_record = new GcrPaypal();
        $this->paypal_record->setTxnId($paypal_data['txn_id']);
        $this->paypal_record->setMcGross($paypal_data['mc_gross']);
        $this->paypal_record->setPayerId($paypal_data['payer_id']);
        $this->paypal_record->setFirstName($paypal_data['first_name']);
        $this->paypal_record->setLastName($paypal_data['last_name']);
        $this->paypal_record->setPaymentStatus($paypal_data['payment_status']);
        $this->paypal_record->setCurrencyCode($paypal_data['mc_currency']);
        $this->paypal_record->setPaymentDate(strtotime($paypal_data['payment_date']));
        
        if (isset($paypal_data['receiver_id']))
        {
            $this->paypal_record->setReceiverId($paypal_data['receiver_id']);
        }
        if (isset($paypal_data['receipt_id']))
        {
            $this->paypal_record->setReceiptId($paypal_data['receipt_id']);
        }
        if (isset($paypal_data['mc_fee']))
        {
            $this->paypal_record->setMcFee($paypal_data['mc_fee']);
        }
        if (isset($paypal_data['tax']))
        {
            $this->paypal_record->setTax($paypal_data['tax']);
        }
        if (isset($paypal_data['shipping']))
        {
            $this->paypal_record->setShipping($paypal_data['shipping']);
        }
        if (isset($paypal_data['parent_txn_id']))
        {
            $this->paypal_record->setParentTxnId($paypal_data['parent_txn_id']);
        }
        if (isset($paypal_data['recurring_payment_id']))
        {
            $this->paypal_record->setRecurringPaymentId($paypal_data['recurring_payment_id']);
        }
    }
    protected function commitPaypalRecord()
    {
        $this->paypal_record->save();
        if (isset($this->purchase) && $this->purchase)
        {
            $this->purchase->updateRelatedAccounting();
        }
        else
        {
            $this->logMessage('Paypal record saved without corresponding purchase object');
        }
    }
}