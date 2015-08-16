<?php


class GcrPaypalTable extends Doctrine_Table
{
    const TXN_PENDING = 'Pending';
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GcrPaypal');
    }
    public static function getRefunds($txn_id, $start_ts = 0, $end_ts = null)
    {
    	if (!$end_ts)
    	{
            $end_ts = time();
    	}
        $refunds = Doctrine::getTable('GcrPaypal')->createQuery('p')
            ->where('p.parent_txn_id = ?', $txn_id)
            ->andWhere('p.payment_status = ?', 'Refunded')
            ->andWhere('p.payment_date >= ?', $start_ts)
            ->andWhere('p.payment_date <= ?', $end_ts)
            ->orderBy('p.payment_date')
            ->execute();
    	return (count($refunds) > 0) ? $refunds : false;
    }
    public static function initializePaypalCaller($paypal, $testing)
    {
    	if ($testing)
        {
            $paypal_username = gcr::API_USERNAME_SB;
            $paypal_password = gcr::API_PASSWORD_SB;
            $paypal_signature = gcr::API_SIGNATURE_SB;
        }
        else
        {
            $paypal_username = gcr::API_USERNAME;
            $paypal_password = gcr::API_PASSWORD;
            $paypal_signature = gcr::API_SIGNATURE;
        }

        // Your PayPal ID or an email address associated with your PayPal account. Email addresses must be confirmed.
        $paypal->setUserName($paypal_username);
        $paypal->setPassword($paypal_password);

        // API signature
        // How to get a signature ? https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics
        $paypal->setSignature($paypal_signature);
        // Usefull in development environment
        $paypal->setTestMode($testing);
    }
    public static function getRecurringProfile($profileID)
    {
    	$paypal = new prestaPaypal(gcr::rootDir . 'plugins/prestaPaypalPlugin/sdk/lib');
    	GCPurchasePaypal::initializePaypalCaller($paypal, gcr::paypalSandbox);
    	$paypal->setProfileID($profileID);
    	return $paypal->getRecurringProfile();
    }
    public static function getCreditCardString($profile)
    {
    	if ($profile->CreditCard->CreditCardType == 'Amex')
    	{
            $cc_string = 'xxxx-xxxxxx-x';
    	}
    	else
    	{
            $cc_string = 'xxxx-xxxx-xxxx-';
    	}
    	$month = $profile->CreditCard->ExpMonth;
    	if (preg_match('/^[1-9]$/', $month))
    	{
            $month = '0' . $month;
    	}
    	return $profile->CreditCard->CreditCardType . " " . $cc_string . $profile->CreditCard->CreditCardNumber . ", expiring on: " . 
    		$month . "/" . $profile->CreditCard->ExpYear;
    }
}