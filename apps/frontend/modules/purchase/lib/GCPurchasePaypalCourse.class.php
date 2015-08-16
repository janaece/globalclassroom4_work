<?php
// Created By Ron Stewart
// July 29, 2010
// This class handles course purchases for GlobalClassroom.
class GCPurchasePaypalCourse extends GCPurchasePaypal
{
    function __construct($purchase_id, $billing_data)
    {
        parent::__construct($purchase_id, $billing_data);
        $this->setAmount();
        $this->setGCFee();
        $this->setOwnerFee();
        $this->setCommissionFee();
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
            if ($course = $CFG->current_app->getCourse($this->my_purchase->getPurchaseTypeId()))
            {
                if ($mdl_enrol = $course->getMdlEnrol())
                {
                    if ($mdl_enrol->cost > 0)
                    {
                        $this->amount = $mdl_enrol->cost;
                    }
                    else if ($enrolCost = $CFG->current_app->getConfigVar('enrol_cost'))
                    {
                        $this->amount = $enrolCost;
                    }
                }
            }
        }
    }
    public function setGCFee($fee = null)
    {
        global $CFG;
        if ($fee)
        {
            $this->gc_fee = $fee;
        }
        else
        {
            if ($gc_fee = $CFG->current_app->getConfigVar('gc_course_fee_percent'))
            {
                $this->gc_fee = $gc_fee;
            }
            else
            {
                $this->gc_fee = 0;
            }
        }
    }
    public function setOwnerFee($fee = null)
    {
        global $CFG;
        if ($fee)
        {
            $this->owner_fee = $fee;
        }
        else
        {
            if ($owner_fee = $CFG->current_app->getConfigVar('owner_course_fee_percent'))
            {
                $this->owner_fee = $owner_fee;
            }
            else
            {
                $this->owner_fee = 0;
            }
        }
    }
    public function setCommissionFee($fee = false)
    { 
        if ($fee)
        {
            $this->commission_fee = $fee;
        }
        else
        {
            $institution = $this->my_purchase->getPurchaserInstitution();
            $eschool = $this->my_purchase->getPurchaseTypeApp();
            $this->commission_fee = GcrPurchaseTable::getCommissionFee($institution, $eschool);
        }
    }
    public function emailPurchaseInfo($product_info = null)
    {
        global $CFG;
        $course = $CFG->current_app->getCourse($this->my_purchase->getPurchaseTypeId());
        $mdl_course = $course->getObject();
        $product_info = "Course: $mdl_course->fullname ($mdl_course->shortname)";
        parent::emailPurchaseInfo($product_info);
    }
    public function providePurchasedUtility()
    {
        global $CFG;
        if (!$course = $CFG->current_app->getCourse($this->my_purchase->getPurchaseTypeId()))
        { // Check that course exists
            $CFG->current_app->gcError("Purchasing User Eschool Id: {$this->my_purchase->getUserInstitutionId()}: Purchasing User Id: {$this->my_purchase->getUserId()}: Enrol error: course " .
                    $this->my_purchase->getPurchaseTypeId() . ' not found', 'enrolcoursenotfound');
        }
        $mdl_course = $course->getObject();
        $user = $this->my_purchase->getPurchaserUser();
        $local_user = $user->getUserOnEschool($CFG->current_app);
        $CFG->current_app->enrolUserInCourse($mdl_course, $local_user->getObject());
    }
    public function emailReceipt()
    {
        global $CFG;
        // gather important data
        $eschool = Doctrine_Core::getTable('GcrEschool')->findOneByShortName($this->my_purchase->getPurchaseTypeEschoolId());
        $current_user = $CFG->current_app->getCurrentUser();
        $user = $current_user->getUserOnInstitution();
        $course = $eschool->getCourse($this->my_purchase->getPurchaseTypeId());
        $mdl_course = $course->getObject();

        $params = array('eschool_full_name' => $eschool->getFullName(),
                        'eschool_short_name' => $eschool->getShortName(),
                        'course' => $course->fullname . " (" . $mdl_course->shortname . ")",
                        'amountCharged' => number_format($this->amount, 2),
                        'creditCardString' => $this->getCreditCardString());
        $email = new GcrUserEmailer('purchase_course', $user, "Thank you for purchasing a course from " . $eschool->getFullName() . "!", $params);
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