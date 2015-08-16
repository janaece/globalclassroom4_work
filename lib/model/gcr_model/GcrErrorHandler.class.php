<?php
class GcrErrorHandler
{
	public static function getError($error_string)
	{
		// Error messages for globalclassroom
		$string['activationnotsupported'] = 'Sorry, this eSchool cannot be activated. Please contact a system administrator.';
		$string['attemptedduplicatewithdrawal'] = 'Sorry, you already have a pending withdrawal, and only one withdrawal may be processed at a time. Withdrawal requests are usually handled within 3-5 business days. If your pending withdrawal has not been processed within that amount of time, please contact customer services.';
		$string['duplicateroamingusererror'] = 'The roaming user you requested already has a roaming account on the eSchool specified.';
		$string['eclassroomnotsupported'] = 'Sorry, this eSchool does not support eClassrooms. Please contact a system administrator.';
		$string['enrolcoursenotfound'] = 'Sorry, the system could not find the course to enroll you in. Please contact a system administrator.';
		$string['eschoolcreationfailed'] = 'We apologize, the eSchool creation process experienced an unexpected error. Please contact eschoolservices@globalclassroom.us for assistance';
		$string['eschooldoesnotexist'] = 'Sorry, the system could not find your eSchool. Please contact a system administrator';
		$string['eschooldoesnotofferclassrooms'] = 'Sorry, this eSchool does not offer digital classrooms. Please contact customer services to obtain a refund.';
		$string['eschoolnotfound'] = 'Invalid eSchool name';
		$string['gcdatabaseerror'] = 'The system has experienced an internal server error. Please contact a system administrator for assistance.';
                $string['gcchainedpaymentcredentials'] = 'Platforms with instant course payments enabled must contact customer service to change payment profile information. Please contact eschoolservices@globalclassroom.us for assistance.';
		$string['gcpageaccessdenied'] = 'Sorry, you do not have permission to access this page.';
		$string['gcwithdrawalbalancetoolow'] = 'Sorry, your current available balance is not high enough to make a withdrawal.';
		$string['mnetconnectiondoesnotexist'] = 'An MNET connection to this eSchool does not exist.';
		$string['paypalbalancetoolow'] = 'Withdrawal transaction failed. Paypal account balance is too low to send this amount.';
		$string['paypalmasspayfailed'] = 'Withdrawal transaction failed. Check paypal.log for more details.';
		$string['purchaseattemptedduplicate'] = 'This transaction has already been processed.';
		$string['purchasetypeinvalid'] = 'Invalid Purchase Request';
		$string['purchasebyguesterror'] = 'Guests are not permitted to make purchases. Please login using a full account.';
		$string['purchasetypeeschoolnotfound'] = 'Sorry, the item you are trying to purchase is located on a different eSchool. Please contact a system administrator.';
		$string['withdrawalamounttoohigh'] = 'This withdrawal cannot be processed. The amount requested would reduce the account balance below the minimum required balance.';
		$string['withdrawaltoosoonerror'] = 'This withdrawal cannot be processed. 24 hours must pass between withdrawals to the same user on the same eSchool.';
		if (isset($string[$error_string]))
		{
			return $string[$error_string];
		}
		return false;
	}
}