<?php
class BillingDataCreditcard extends BillingData
{
	protected $cc_number;
  	protected $cc_type;
  	protected $cc_ccv2;
  	protected $cc_exp_month;
  	protected $cc_exp_year;
  	
  	function __construct($firstName, $lastName, $ccNumber, $ccType, $ccCcv2, 
		$ccExpMonth, $ccExpYear, $address, $city, $state, $country, $zip)
  	{
  		parent::__construct($firstName, $lastName, $address, $city, $state, $country, $zip);
  		$this->cc_number = $ccNumber;
		$this->cc_type = $ccType;
		$this->cc_ccv2 = $ccCcv2;
		$this->cc_exp_month = $ccExpMonth;
		$this->cc_exp_year = $ccExpYear;
  	}
  	
	public function getCCNumber()
  	{
  		return $this->cc_number;
  	}
  	public function setCCNumber($cc_number)
  	{
  		$this->cc_number = $cc_number;
  	}
	public function getCCType()
  	{
  		return $this->cc_type;
  	}
  	public function setCCType($cc_type)
  	{
  		$this->cc_type = $cc_type;
  	}
	public function getCCCcv2()
  	{
  		return $this->cc_ccv2;
  	}
  	public function setCCCcv2($cc_ccv2)
  	{
  		$this->cc_ccv2 = $cc_ccv2;
  	}
	public function getCCExpMonth()
  	{
  		return $this->cc_exp_month;
  	}
  	public function setCCExpMonth($cc_exp_month)
  	{
  		$this->cc_exp_month = $cc_exp_month;
  	}
	public function getCCExpYear()
  	{
  		return $this->cc_exp_year;
  	}
  	public function setCCExpYear($cc_exp_year)
  	{
  		$this->cc_exp_year = $cc_exp_year;
  	}
}