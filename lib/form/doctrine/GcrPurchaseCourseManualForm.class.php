<?php
class GcrPurchaseCourseManualForm extends BaseGcrPurchaseForm
{
    public function configure()
    {
        $courseArray = array();
        $userArray = array();
        $purchase = false;
        $app = false;
        $key = false;
        if ($this->getObject()->getId())
        {
            $purchase = $this->getObject();
        }
        $institution = $this->getOption('eschool');
       
        $eschools = $institution->getMnetCourses();
        foreach ($eschools as $short_name => $eschool_courses)
        {
            $eschool = Doctrine::getTable('GcrEschool')->findOneByShortName($short_name);
            foreach ($eschool_courses as $mdl_course)
            {
                if ($mdl_course->id > 1)
                {
                    $course = new GcrMdlCourse($mdl_course, $eschool);
                    $cost = number_format($course->getCost(), 2, '.', '');
                    if ($cost)
                    {
                        $cost = '$' . $cost;
                    }
                    $course_fullname = trim($mdl_course->fullname);
                    if (strlen($course_fullname) > 54)
                    {
                        $course_fullname = substr($course_fullname, 0, 50) . '...';
                    }
                    if ($purchase)
                    {
                        $app = $purchase->getPurchaseTypeApp();
                    }
                    if ($purchase && $app &&
                        $app->getShortName() == $eschool->getShortName() &&
                        $mdl_course->id == $purchase->getPurchaseTypeId())
                    {
                        $key = $eschool->getShortName() . '#' . $mdl_course->id;
                        $value = $eschool->getShortName() . ': ' . $course_fullname .
                                ' (' . $mdl_course->shortname . ') ' . $cost;
                    }
                    else
                    {
                        $courseArray[$eschool->getShortName() . '#' . $mdl_course->id] =
                                $eschool->getShortName() . ': ' . $course_fullname .
                                ' (' . $mdl_course->shortname . ') ' . $cost;
                    }
                }
            }
        }
        asort($courseArray);
        if ($purchase && $key)
        {
            $courseArray = array($key => $value) + $courseArray;
        }

        $users = $institution->selectFromMhrTable('usr');
        foreach ($users as $mhr_user)
        {
            if ($mhr_user->deleted < 1)
            {
                if ($lastname = trim($mhr_user->lastname))
                {
                    $lastname = $lastname . ', ';
                }
                $userArray[$mhr_user->id] =  $lastname . $mhr_user->firstname .
                        ' (' . $mhr_user->email . ')';
            }
        }
        asort($userArray);
        $userArray = array(0 => 'Select a User') + $userArray;

        $this->setWidgets
        (
            array
            (
                'id'                       	=> new sfWidgetFormInputHidden(),
                'purchase_type_id'         	=> new sfWidgetFormSelect(array('choices' => $courseArray), array('style' => 'max-width:50%')),
                'amount_field'             	=> new sfWidgetFormInputText(),
                'profile_id'               	=> new sfWidgetFormInputText(),
                'purchase_type_quantity'	=> new sfWidgetFormInputText(),
                'purchase_user_field'		=> new sfWidgetFormSelect(array('choices' => $userArray), array('style' => 'max-width:50%')),
                'purchase_type_description'	=> new sfWidgetFormTextArea(),
                'bill_cycle'                    => new sfWidgetFormInputHidden(),
                'trans_time'      		=> new sfWidgetFormDate(array('format' => '%month%/%day%/%year%')),
                'user_institution_id' 		=> new sfWidgetFormInputHidden(),
                'purchase_type_eschool_id' 	=> new sfWidgetFormInputHidden(),
                'seller_id'         		=> new sfWidgetFormInputHidden(),
                'user_id'         		=> new sfWidgetFormInputHidden(),
                'amount'          		=> new sfWidgetFormInputHidden(),
                'gc_fee'			=> new sfWidgetFormInputHidden(),
                'owner_fee'			=> new sfWidgetFormInputHidden(),
                'commission_fee'		=> new sfWidgetFormInputHidden(),
                'purchase_type'			=> new sfWidgetFormInputHidden(),
            )
        );

        $this->widgetSchema->setLabels
        (
            array
            (
                'purchase_type_id' => 'Course:',
                'amount_field' => 'Payment Amount:',
                'profile_id' => 'Check Number/Paypal TID:',
                'purchase_type_quantity' => 'Quantity (# of enrollments):',
                'purchase_user_field' => 'Purchasing User:',
                'purchase_type_description' => 'Description:',
                'trans_time' => 'Date of Transaction:',
            )
        );
        if ($purchase)
        {
            $this->widgetSchema->setDefaults
            (
                array
                (
                    'purchase_user_field' => $purchase->getUserId(),
                    'amount_field' => number_format($purchase->getAmount(), 2, '.', ''),
                )
            );
        }

        $this->setValidators
        (
            array
            (
              'id'                              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
              'purchase_type_id'         	=> new sfValidatorInteger(array('required' => true, 'min' => 1), array('min' => 'Required.')),
              'amount_field'               	=> new sfValidatorNumber(array('required' => true, 'min' => 0), array('min' => 'Invalid Amount.')),
              'profile_id'               	=> new sfValidatorString(array('required' => true)),
              'purchase_type_description'	=> new sfValidatorString(array('required' => true)),
              'purchase_type_quantity' 		=> new sfValidatorInteger(array('required' => true, 'min' => 1), array('min' => 'Required.')),
              'purchase_user_field'		=> new sfValidatorInteger(array('required' => true, 'min' => 1), array('min' => 'Required.')),
              'bill_cycle'                      => new sfValidatorString(array('required' => true)),
              'profile_id'			=> new sfValidatorString(array('required' => false)),
              'trans_time'    			=> new sfValidatorString(array('required' => true)),
              'user_institution_id' 		=> new sfValidatorString(array('required' => false)),
              'purchase_type_eschool_id' 	=> new sfValidatorString(array('required' => false)),
              'purchase_type'                   => new sfValidatorString(array('required' => false)),
              'user_id'        			=> new sfValidatorInteger(array('required' => false)),
              'seller_id'         		=> new sfValidatorInteger(array('required' => false)),
              'amount'          		=> new sfValidatorNumber(array('required' => false)),
              'gc_fee'          		=> new sfValidatorNumber(array('required' => false)),
              'owner_fee'          		=> new sfValidatorNumber(array('required' => false)),
              'commission_fee'          	=> new sfValidatorNumber(array('required' => false)),
            )
        );
    }
}