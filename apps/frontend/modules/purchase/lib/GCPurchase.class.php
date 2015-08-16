<?php
// Created By Ron Stewart
// July 29, 2010
// This class handles purchases for GlobalClassroom. It includes a Purchase object built by Smyfony.
class GCPurchase
{
    protected $my_purchase; // Purchase object from Purchase table in db
    protected $amount;
    protected $gc_fee;
    protected $owner_fee;
    protected $commission_fee;

    function __construct($purchase_id)
    {
        $this->my_purchase = Doctrine::getTable('GcrPurchase')->find($purchase_id);
        $this->setAmount(); 
        $this->gc_fee = 0;
        $this->owner_fee = 0;
        $this->commission_fee = 0;
    }
    public function getPurchase()
    {
        return $this->my_purchase;
    }
    public function setPurchase($purchase_id)
    {
        $my_purchase = Doctrine::getTable('GcrPurchase')->find($purchase_id);
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function getGCFee()
    {
        return $this->gc_fee;
    }
    public function getOwnerFee()
    {
        return $this->owner_fee;
    }
    public function getCommissionFee()
    {
        return $this->commission_fee;
    }
    public function setAmount($amount = null)
    {
        if ($amount && $amount <= 0)
        {
            $this->amount = $amount;
        }
        else
        {
            $this->amount = -1;
        }
    }
    protected function providePurchasedUtility(){}
}