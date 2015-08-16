<?php
// Created By Ron Stewart
// July 29, 2010
// This class handles eschool purchases for GlobalClassroom.
class GCPurchasePaypalEschool extends GCPurchasePaypalRecurring
{
    function __construct($purchase_id, $billing_data)
    {
        parent::__construct($purchase_id, $billing_data);

        $this->setAmount();
    }
    public function setAmount($amount = null)
    {
        global $CFG;
        if ($amount && $amount <= 0)
        {
            $this->amount = $amount;
        }
        else
        {
            if ($cost = $CFG->current_app->getConfigVar('gc_eschool_cost_' . strtolower($this->my_purchase->getBillCycle())))
            {
                $this->amount = $cost;
            }
        }
    }
    public function emailPurchaseInfo()
    {
        $product_info = 'Activation of eSchool, billed ' . $this->my_purchase->getBillCycle() . 'ly';
        parent::emailPurchaseInfo($product_info);
    }
    public function emailReceipt()
    {
        $user = $this->my_purchase->getPurchaserUser();
        $email = new GcrUserEmailer('purchase_eschool', $user, "Thank you for purchasing your eSchool!");
        $email->sendHtmlEmail();
    }
    public function providePurchasedUtility()
    {
        if (!$institution = $this->my_purchase->getPurchaseTypeApp())
        {
            global $CFG;
            $CFG->current_app->gcError("Eschool Activation purchase error: " .
                    'eSchool ' . $this->my_purchase->getPurchaseTypeId() . ' not found', 'eschooldoesnotexist');
        }
        $institution->activate();
    }
}