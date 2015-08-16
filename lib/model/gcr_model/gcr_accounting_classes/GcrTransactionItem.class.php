<?php
class GcrTransactionItem
{
    protected $purchase;
    protected $paypal;
    protected $amount;
    protected $distribution;
    protected $app;
    protected $seller;
    protected $gc_fee;
    protected $owner_fee;
    protected $commission_fee;
    protected $is_eclassroom_course;
    protected $owner_balance;
    protected $eclassroom_balance;

    public function __construct($purchase, $paypal = null)
    {
        $this->purchase = $purchase;
        $this->paypal = $paypal;
        if ($this->paypal)
        {
            $this->amount = $this->paypal->getMcGross();
            $this->gc_fee = $this->paypal->getGcFee() / 100;
            $this->commission_fee = $this->paypal->getCommissionFee() / 100;
        }
        else
        {
            $this->amount = $this->purchase->getAmount();
            $this->gc_fee = $this->purchase->getGcFee() / 100;
            $this->commission_fee = $this->purchase->getCommissionFee() / 100;
        }
        $this->app = $this->purchase->getPurchaseTypeApp();
        $this->seller = $this->purchase->getSellerUser();
        $this->owner_fee = $this->purchase->getOwnerFee() / 100;
        $this->is_eclassroom_course = $this->purchase->isEclassroomCourse();
        $this->setDistribution();
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function getDistribution()
    {
        return $this->distribution;
    }
    public function getApp()
    {
        return $this->app;
    }
    public function getPurchase()
    {
        return $this->purchase;
    }
    public function getOwnerBalance()
    {
        return $this->owner_balance;
    }
    public function getPaypal()
    {
        return $this->paypal;
    }
    public function getSeller()
    {
        return $this->seller;
    }
    public function getEclassroomBalance()
    {
        return $this->eclassroom_balance;
    }
    public function isEclassroomCourse()
    {
        return $this->is_eclassroom_course;
    }
    public function isInternal()
    {
        return ($this->app && $this->app->isInternal());
    }
    public function isRefund()
    {
        if ($this->paypal)
        {
            if ($this->paypal->getPaymentStatus() == 'Refunded')
            {
                return true;
            }
        }
        return false;
    }
    public function getPurchaserString($return_hyperlink = false)
    {
        global $CFG;
        $purchaser = $this->purchase->getPurchaserUser();
        $mhr_user_obj = $purchaser->getObject();

        if ($return_hyperlink)
        {
            $user_tooltip = $mhr_user_obj->username . ', email: ' . $mhr_user_obj->email;
            $purchaser_text = '<span title="' . $user_tooltip . '"><a href="' . $CFG->current_app->getUrl() .
            '/account/view?eschool=' . $purchaser->getApp()->getShortName() . '&user=' . $mhr_user_obj->id .
            '&startdate=' . $start_date . '&enddate=' . $end_date . '" target="_blank">' . $mhr_user_obj->firstname .
            ' ' . $mhr_user_obj->lastname . '</a></span>';
        }
        else
        {
            $purchaser_text = $mhr_user_obj->firstname . ' ' . $mhr_user_obj->lastname;
        }
        return $purchaser_text;
    }
    public function getTimestamp()
    {
        if ($this->paypal)
        {
            return $this->paypal->getPaymentDate();
        }
        else
        {
            return $this->purchase->getTransTime();
        }
    }
    public function setDistribution()
    {
        $gc_amount = 0;
        $seller_amount = 0;
        $commission_amount = 0;
        if ($this->purchase->isEschool())
        {
            $gc_amount = $this->amount;
        }
        else if ($this->purchase->isPayoff())
        {
            $this->amount *= -1;
            if (!$payoff = Doctrine::getTable('GcrPayoff')->findOneByPurchaseId($this->purchase->getId()))
            {
                global $CFG;
                $CFG->current_app->gcError('Purchase ID ' . $this->purchase->getId() .
                        ' with type payoff has no existing payoff record.', 'gcdatabaserror');
            }
            if (!$payoff->isEschoolPayoff())
            {
                $seller_amount = $this->amount;
            }
        }
        else
        {
            if ($this->purchase->isRemote())
            {
                $commission_amount = $this->amount * $this->commission_fee;
            }
            $amount_net = $this->amount - $commission_amount;
            $amount_owner = $amount_net * $this->owner_fee;
            if ($this->isInternal())
            {
                if ($this->purchase->isCourse())
                {
                    if (!($this->getPurchase()->getSellerId() == 0))
                    {
                        // eClassroom user sells course, owner gc
                        $seller_amount = $amount_net - $amount_owner;
                    }
                }
            }
            else
            {
                
                $gc_amount = $amount_net * $this->gc_fee;

                if ($this->purchase->isCourse())
                {
                    if (!($this->purchase->getSellerId() == 0))
                    {
                        // Owner sells course, owner not gc
                        $seller_amount = $amount_net - ($amount_owner + $amount_net * $this->gc_fee);
                    }
                }
            }
        }
        $this->distribution = new GcrPaymentDistribution($this->amount, $seller_amount, $gc_amount, $commission_amount);
    }
    public function setOwnerBalance($balance)
    {
        $this->owner_balance = $balance;
    }
    public function setEclassroomBalance($balance)
    {
        $this->eclassroom_balance = $balance;
    }
}