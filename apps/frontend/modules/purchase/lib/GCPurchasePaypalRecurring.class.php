<?php
// Created By Ron Stewart
// July 29, 2010
// This class handles recurring purchase profiles for GlobalClassroom.
class GCPurchasePaypalRecurring extends GCPurchasePaypal
{
	protected $start_date; // starting date for recurring payments (yyyy-mm-ddTh:m:s)
  	protected $auto_bill_outstanding_amount; // boolean for whether to charge all deliquient payments on recurring payment
  	protected $billing_frequency; // number of recurring payments per period (int)
  	const DAY = 86400;
  	
	function __construct($purchase_id, $billing_data)
	{
		parent::__construct($purchase_id, $billing_data);
		
		$this->setAutoBillOutstandingAmount();
		$this->setBillingFrequency();
		$this->setStartDate();
	}
  	
  	public function getAutoBillOutstandingAmount()
  	{
  		return $this->auto_bill_outstanding_amount;
  	}
  	public function setAutoBillOutstandingAmount($auto_bill = true)
  	{
  		$this->auto_bill_outstanding_amount = $auto_bill;
  	}
	public function getBillingFrequency()
  	{
  		return $this->billing_frequency;
  	}
  	public function setBillingFrequency($billing_frequency = 1)
  	{
  		$this->billing_frequency = $billing_frequency;
  	}
  	public function getStartDate()
  	{
  		return $this->start_date;
  	}
  	public function setStartDate($start_date = null)
  	{
  		if (!$start_date)
  		{
  			$start_date = time() + self::DAY;  //add 25 hours because the profile may take up to 24 hours to activate.
  		}	
		$start_date = date('Y-n-j', $start_date);
		$start_date = $start_date . 'T0:0:0';
  		$this->start_date = $start_date;
  	}
  	public function attemptPaypalTransaction()
	{
		$this->cc->setStartDate($this->start_date);
		$this->cc->setBillingPeriod($this->my_purchase->getBillCycle());
		$this->cc->setBillingFrequency($this->billing_frequency);
		$this->cc->setAutoBillOutstandingAmount($this->auto_bill_outstanding_amount);
		$this->cc->setProfileReference($this->my_purchase->getId());
		return parent::attemptPaypalTransaction();
	}
  	public function providePurchasedUtility(){}
}