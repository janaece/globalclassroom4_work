<?php
class GcrTransaction
{
    protected $purchase;
    protected $paypal_records;
    protected $refunds;
    protected $trans_time;

    public function __construct($purchase, $paypal_records = null, $refunds = null)
    {
        $this->purchase = $purchase;
        $this->paypal_records = $paypal_records;
        $this->refunds = $refunds;
    }
    public function getPurchase()
    {
        return $this->purchase;
    }
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    }
    public function getPaypalRecords()
    {
        return $this->paypal_records;
    }
    public function setPaypalRecords($paypal_records)
    {
        $this->paypal_records = $paypal_records;
    }
    public function getRefunds()
    {
        return $this->refunds;
    }
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
    }
    public function isRecurring()
    {
        return $this->purchase->isRecurring();
    }
}