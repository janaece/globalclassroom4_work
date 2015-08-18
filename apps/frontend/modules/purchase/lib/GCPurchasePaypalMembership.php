<?php
// Created By Ron Stewart
// May 9, 2011
// This class handles membership purchases for GlobalClassroom.
class GCPurchasePaypalMembership extends GCPurchasePaypalRecurring
{
    function __construct($purchase_id, $billing_data)
    {
        parent::__construct($purchase_id, $billing_data);
        $this->setAmount();
        $this->setStartDate();
        $this->setGCFee();
    }

    public function setAmount($amount = null)
    {
        if ($amount && $amount <= 0)
        {
            $this->amount = $amount;
        }
        else
        {
            $eschool = $this->my_purchase->getPurchaseTypeApp();
            if ($cost = $eschool->getConfigVar('gc_membership_cost_' . strtolower($this->my_purchase->getBillCycle())))
            {
                $this->amount = $cost;
            }
        }
		//echo "mem amt =".$this->amount."=";
    }
    public function setGCFee($fee = null)
    {
        if ($fee)
        {
            $this->gc_fee = $fee;
        }
        else
        {
            $eschool = $this->my_purchase->getPurchaseTypeApp();
            if ($gc_fee = $eschool->getConfigVar('gc_membership_fee_percent'))
            {
                $this->gc_fee = $gc_fee;
            }
            else
            {
                $this->gc_fee = 0;
            }
        }
    }
    public function setStartDate($start_date = null)
    {
        if (!$start_date)
        {
            $eschool = $this->my_purchase->getPurchaseTypeApp();
            if (!$start_date = $eschool->getConfigVar('gc_membership_trial_length'))
            {
                $start_date = time() + gcr::membershipTrialLengthInDays * self::DAY;
            }
            else
            {
                $start_date = time() + $start_date * self::DAY;
            }
        }
        // convert to date format that conforms to PayPal's API
        $start_date = date('Y-n-j', $start_date);
        $start_date = $start_date . 'T0:0:0';
        $this->start_date = $start_date;
    }
    public function emailPurchaseInfo()
    {
        $product_info = 'Membership, billed ' . $this->my_purchase->getBillCycle() . 'ly';
        parent::emailPurchaseInfo($product_info);
    }
    public function emailReceipt()
    {
        $user = $this->my_purchase->getPurchaserUser();
        $institution = $user->getApp();
        $trial_length = $institution->getMembershipTrialLength();
        $trial_end_date = date('F j, Y', $trial_length * self::DAY + time());

        $params = array('institution_full_name' => $institution->getFullName(),
                        'institution_short_name' => $institution->getShortName(),
                        'trial_end_date' => $trial_end_date,
                        'amountCharged' => number_format($this->amount, 2),
                        'trial_length' => $trial_length,
                        'cycle_text' => strtolower($this->my_purchase->getBillCycle()) . 'ly');
        $email = new GcrUserEmailer('purchase_membership', $user,
                 "Thank you for purchasing a membership from " . $institution->getFullName() . "!", $params);
        $email->sendHtmlEmail();
    }
    public function providePurchasedUtility()
    {
        global $CFG;
        if (!$institution = $this->my_purchase->getPurchaseTypeApp())
        {
            $CFG->current_app->gcError("Membership purchase error: " . 'institution ' .
                    $this->my_purchase->getPurchaseTypeId() . ' not found', 'gcdatabaseerror');
        }
        if (!$mhr_user = $this->my_purchase->getPurchaserUser())
        {
            $CFG->current_app->gcError("Membership purchase error: " . 'Purchaser User ' .
                    $this->my_purchase->getUserId() . ' not found', 'gcdatabaseerror');
        }
        $mhr_user_obj = $mhr_user->getObject();
        // add as auth type institution user on home to grant access to system
        $mhr_user->addMhrInstitutionMembership(false, true);
    }
}