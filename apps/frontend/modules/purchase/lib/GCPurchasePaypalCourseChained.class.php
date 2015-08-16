<?php

/**
 * Description of GCPurchasePaypalCourseChained:
 * 
 * This purchase method uses Paypal's Adaptive payments to
 * make chained payments (multiple receievers with one primary receiever
 * being the only one listed to the payer). This method requires
 * us to redirect users to the Paypal site after creating a pay token. We
 * must wait for an IPN from Paypal to know if the transaction was completed.
 * Therefore, the implementation of GCPurchasePaypalCourseChained::providePurchasedUtility
 * is different than the GCPurchasePaypal branch of the GCPurchase base type.  
 * 
 * @author Ron Stewart
 * Created: Jan 20, 2012
 */
class GCPurchasePaypalCourseChained extends GCPurchasePaypalCourse
{
    protected $receivers;
    protected $pay_key;
    protected $ipn_url;
    
    public function __construct($form, $user)
    {
        $this->pay_key = false;
        $this->setMyPurchase($form, $user);
        $this->setAmount();
        $this->setGCFee();
        $this->setOwnerFee();
        $this->setCommissionFee();
        $this->testing = gcr::paypalSandbox;
        $app = $this->my_purchase->getPurchaseTypeApp();
        if ($this->testing)
        {
            $this->ipn_url = 'https://' . $app->getshortName() . '.globalcampus.us/purchase/ipn';
        }
        else
        {
            $this->ipn_url = $app->getUrl() . '/purchase/ipn';
        }
        $this->my_purchase->setAmount($this->amount);
        $this->my_purchase->setGcFee($this->gc_fee);
        $this->my_purchase->setOwnerFee($this->owner_fee);
        $this->my_purchase->setCommissionFee($this->commission_fee);
        $this->my_purchase->setPurchaseTypeDescription($this->my_purchase->getPurchaseDescription());
        $this->my_purchase->save();
        $this->my_purchase->assignSeller();
        $this->receivers = self::getReceivers($this->my_purchase);
        if (count($this->receivers) < 2)
        {
            global $CFG;
            $CFG->current_app->gcError('No receivers found for Paypal chained payment, purchase ID: ' . 
                    $this->my_purchase->getId(), 'gcdatabaseerror');
        }
        
        gcr::loadSdk('paypal_adaptive_payments');
    }
    public static function getCommissionFeeAmount($purchase)
    {
        // Note: Commission fees are calculated before subtracting any other types of fees
        // on a course purchase. Thereby, commissions are always a straight percentage of the total
        // course enrollment amount.
        return round($purchase->getAmount() * $purchase->getCommissionFee() * .01, 2, PHP_ROUND_HALF_DOWN); 
    }
    public static function getOwnerFeeAmount($purchase)
    {
        if ($purchase->getSellerId() == 0)
        {
            $amount = $purchase->getAmount() - (self::getCommissionFeeAmount($purchase) + 
                    self::getGCFeeAmount($purchase));
        }
        else
        {
            $amount = ($purchase->getAmount() - self::getCommissionFeeAmount($purchase)) * 
                    $purchase->getOwnerFee() * .01;
            $amount = round($amount, 2, PHP_ROUND_HALF_DOWN);
        }
        return $amount;
    }
    public static function getGCFeeAmount($purchase)
    {
        $amount = ($purchase->getAmount() - self::getCommissionFeeAmount($purchase)) * 
                $purchase->getGCFee() * .01;
        return round($amount, 2, PHP_ROUND_HALF_DOWN);
    }
    public static function getSellerAmount($purchase)
    {
        if ($purchase->getSellerId() == 0)
        {
            $amount = 0;
        }
        else
        {
            $amount = $purchase->getAmount() - (self::getCommissionFeeAmount($purchase) + 
                    self::getOwnerFeeAmount($purchase) + self::getGCFeeAmount($purchase));
        }
        return $amount;
    }
    
    // This receiver is included if:
    // 1. Purchasing user is a remote user
    // 2. A commission record exists for the relationship between the disparate platforms
    // 3. The purchasing user's platform is configured to use chained payments
    public static function getCommissionReceiver($purchase)
    {
        $receiver = false;
        if ($purchase->isRemote() && $purchase->getCommissionFee() > 0)
        {
            $purchaser = $purchase->getPurchaserUser();
            $purchaser_institution = $purchaser->getApp();
            $purchaser_institution_account_manager = $purchaser_institution->getAccountManager();
            if ($purchaser_institution_account_manager->usesChainedPayments())
            {
                $amount = self::getCommissionFeeAmount($purchase);
                if ($amount > 0)
                {
                    $receiver = new StdClass();
                    $payoff_credentials = $purchaser_institution_account_manager->getPayoffCredentials();
                    $receiver->user = $purchaser_institution_account_manager->getUser();
                    $receiver->email = $payoff_credentials->getUserPaypalEmail();
                    $receiver->amount = $amount;
                }
            }
        }
        return $receiver;
    }
    
    // This receiver is included if:
    // 1. The course is an eclassroom course
    // 2. The eclassroom user is configured to use chained payments
    // 3. The amount remaining for the seller after subtracting all fees > 0
    public static function getSellerReceiver($purchase)
    {
        $receiver = false;
        $seller = $purchase->getSellerUser();
        if ($seller && $seller->getApp()->isInternal())
        {
            $seller_account_manager = $seller->getAccountManager();
            if ($seller_account_manager->usesChainedPayments())
            {
                $amount = self::getSellerAmount($purchase);
                if ($amount > 0)
                {
                    $receiver = new StdClass();
                    $payoff_credentials = $seller_account_manager->getPayoffCredentials();
                    $receiver->user = $seller;
                    $receiver->email = $payoff_credentials->getUserPaypalEmail();
                    $receiver->amount = $amount;
                }
            }
        }
        return $receiver;
    }
    // This receiver is included if:
    // 1. The platform which owns the course catalog is configured to use chained payments
    // 2. The amount remaining for the owner after subtracting all fees > 0
    public static function getOwnerReceiver($purchase)
    {
        $receiver = false;
        $institution = $purchase->getPurchaseTypeApp()->getInstitution();
        $institution_account_manager = $institution->getAccountManager();
        if ($institution_account_manager->usesChainedPayments())
        {
            $amount = self::getOwnerFeeAmount($purchase);
            if ($amount > 0)
            {
                $receiver = new StdClass();
                $payoff_credentials = $institution_account_manager->getPayoffCredentials();
                $receiver->user = $institution_account_manager->getUser();
                $receiver->email = $payoff_credentials->getUserPaypalEmail();
                $receiver->amount = $amount;
            }
        }
        return $receiver;
    }
    
    // This receiver is always included. This is GlobalClassroom's
    // Paypal account, which must be the first link in the chain, even if
    // the amount paid to Globalclassroom is 0.
    public static function getPrimaryReceiver($purchase)
    {
        $receiver = new StdClass();
        $receiver->amount = $purchase->getAmount();
        if (gcr::paypalSandbox)
        {
            $receiver->email = gcr::API_EMAIL_SB;
        }
        else
        {
            $receiver->email = gcr::API_EMAIL;
        }
        $receiver->user = false;
        return $receiver;
    }
    public static function getReceivers($purchase)
    {
        $primary_receiver = self::getPrimaryReceiver($purchase);
        $commission_receiver = self::getCommissionReceiver($purchase);
        $owner_receiver = self::getOwnerReceiver($purchase);
        $seller_receiver = self::getSellerReceiver($purchase);
        $receivers = array($primary_receiver);
        
        // set secondary receiver
        if ($owner_receiver)
        {
            $receivers[] = $owner_receiver;
        }
        else if ($seller_receiver)
        {
            $receivers[] = $seller_receiver;
        }
        else if ($commission_receiver)
        {
            $receivers[] = $commission_receiver;
        }
        
        // set tertiary receiver, if needed. This must be a commission because we don't allow chained payments
        // to eclassroom users on external platforms.
        if (($owner_receiver || $seller_receiver) && $commission_receiver)
        {
            $receivers[] = $commission_receiver;
        }
        return $receivers;
    }
    public function setMyPurchase($form, $user)
    {
        global $CFG;
        $this->my_purchase = new GcrPurchase();
        $this->my_purchase->setTransTime(time());
        $this->my_purchase->setUserId($user->getObject()->id);
        $this->my_purchase->setPurchaseType($form['type']);
        $this->my_purchase->setPurchaseTypeId($form['type_id']);
        $this->my_purchase->setPurchaseTypeEschoolId($CFG->current_app->getShortName());
        $this->my_purchase->setUserInstitutionId($user->getApp()->getShortName());
        $this->my_purchase->setBillCycle('single');
        $this->my_purchase->setProfileId(GcrPaypalTable::TXN_PENDING);
        $this->my_purchase->save();
    }
    public function attemptPaypalTransaction()
    {
        global $CFG;
        $purchaser_user = $this->my_purchase->getPurchaserUser();
        $user_institution = $purchaser_user->getApp();
        $return_url = $CFG->current_app->getUrl() . '/purchase/receipt?purchaseId=' . $this->my_purchase->getId();
	$cancel_url = $user_institution->getUrl();
	$request_array = array( 'actionType' => 'CREATE',
                                'trackingId' => $this->my_purchase->getId(),
                                'cancelUrl'  => $cancel_url,
                                'returnUrl' => $return_url,
                                'currencyCode' => 'USD',
                                'requestEnvelope.errorLanguage' => 'en_US',
                                'memo' => $this->my_purchase->getPurchaseDescription(),
                                'feesPayer' => 'EACHRECEIVER',
                                'ipnNotificationUrl' => $this->ipn_url);
        for ($i = 0; $i < count($this->receivers); $i++)
        {
            $request_array["receiverList.receiver[$i].email"] = $this->receivers[$i]->email;
            $request_array["receiverList.receiver[$i].amount"] = $this->receivers[$i]->amount;
            $request_array["receiverList.receiver[$i].primary"] = ($i == 0) ? 'true' : 'false';
        }

	$nvp_str = http_build_query($request_array, '', '&');

	/* Make the call to PayPal to get the Pay token
	 If the API call succeded, then redirect the buyer to PayPal
	 to begin to authorize payment.  If an error occured, show the
	 resulting errors
	 */
        
        $result_array = hash_call('AdaptivePayments/Pay', $nvp_str);
       
	$ack = strtoupper($result_array['responseEnvelope.ack']);

	if ($ack != "SUCCESS" && $ack != "SUCCESSWITHWARNING")
        {
            $error_string = 'Course Purchase Paypal Chained, PAY failed. Response: ';
            foreach($result_array as $key => $value)
            {
                $error_string .= $key . '=' . $value . ';';
            }
            $CFG->current_app->gcError($error_string);
            $this->my_purchase->delete();
            return false;
	}
	else
	{
            $banner_url = $user_institution->getAppUrl() . 'local/printBanner.php';
            $this->pay_key = $result_array['payKey'];
            $request_array = array( 'payKey' => $this->pay_key,
                                    'requestEnvelope.errorLanguage' => 'en_US',
                                    'displayOptions.emailHeaderImageUrl' => $banner_url,
                                    'displayOptions.emailMarketingImageUrl' => $banner_url,
                                    'displayOptions.headerImageUrl' => $banner_url,
                                    'displayOptions.businessName' => $user_institution->getFullName());
			
					
            $nvp_str = http_build_query($request_array, '', '&');
            $result_array = hash_call('AdaptivePayments/SetPaymentOptions', $nvp_str); 

            $ack = strtoupper($result_array['responseEnvelope.ack']);

            if ($ack != "SUCCESS" && $ack != "SUCCESSWITHWARNING")
            {
        
		$error_string = 'Course Purchase Paypal Chained, SetPaymentOptions failed. Response: ';
                foreach($result_array as $key => $value)
                {
                    $error_string .= $key . '=' . $value . ';';
                }
                $CFG->current_app->gcError($error_string);
            }
            return PAYPAL_REDIRECT_URL . '_ap-payment&paykey=' . $this->pay_key;
	}
    }
}

?>
