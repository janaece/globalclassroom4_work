<?php
// Created By Ron Stewart
// Feb 15, 2011
// This class handles miscellaneous purchases for GlobalClassroom.
class GCPurchasePaypalSale extends GCPurchasePaypal
{
    function __construct($purchase_id, $billing_data)
    {
        parent::__construct($purchase_id, $billing_data);
        $this->setAmount();
    }
    public function setAmount($amount = null)
    {
        if ($amount && $amount <= 0)
        {
            $this->amount = $amount;
        }
        else
        {
            if ($purchase_item = Doctrine::getTable('GcrPurchaseItem')->find($this->my_purchase->getPurchaseTypeId()))
            {
                $this->amount = $purchase_item->getAmount();
            }
        }
    }
    public function emailPurchaseInfo($product_info = null)
    {
        $purchase_item = Doctrine::getTable('GcrPurchaseItem')->find($this->my_purchase->getPurchaseTypeId());
        $product_info = "Item: $purchase_item->description";
        parent::emailPurchaseInfo($product_info);
    }
    public function emailReceipt()
    {
        global $CFG;
        // gather important data
        $eschool = Doctrine_Core::getTable('GcrInstitution')->findOneByShortName($this->my_purchase->getUserInstitutionId());
        $current_user = $CFG->current_app->getCurrentUser();
        $user = $current_user->getUserOnInstitution();
        $purchase_item = Doctrine::getTable('GcrPurchaseItem')->find($this->my_purchase->getPurchaseTypeId());

        $params = array('sale_description' => $purchase_item->getDescription(),
                        'amountCharged' => number_format($this->amount, 2),
                        'creditCardString' => $this->getCreditCardString());
        $email = new GcrUserEmailer('purchase_sale', $user, "Thank you for your purchase!", $params);
        $email->sendHtmlEmail();
    }
    private function getCreditCardString()
    {
        $last_four_digits = substr($this->billing_data->getCCNumber(), -4);
        if ($this->billing_data->getCCType() == 'Amex')
        {
            $cc_string = 'xxxx-xxxxxx-x';
        }
        else
        {
            $cc_string = 'xxxx-xxxx-xxxx-';
        }

        return $this->billing_data->getCCType() . " " . $cc_string . $last_four_digits;
    }
}