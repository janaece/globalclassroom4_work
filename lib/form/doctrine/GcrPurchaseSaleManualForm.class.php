<?php
class GcrPurchaseSaleManualForm extends BaseGcrPurchaseForm
{
    public function configure()
    {
        if ($this->getObject()->getId())
        {
            $purchase = $this->getObject();
        }
        $institution = $this->getOption('eschool');
        $users = $institution->getUsers();
        foreach ($users as $user)
        {
            if ($lastname = trim($user->lastname))
            {
                $lastname = $lastname . ', ';
            }
            $userArray[$user->id] =  $lastname . $user->firstname .
                    ' (' . $user->email . ')';
        }
        asort($userArray);
        $userArray = array(0 => 'Select a User') + $userArray;
        
        $items = array();
        foreach (Doctrine::getTable('GcrPurchaseItem')->findAll() as $item)
        {
            $items[$item->getShortName()] = $item->getDescription();
        }

        $this->setWidgets
        (
            array
            (
              'id'                       	=> new sfWidgetFormInputHidden(),
              'purchase_type_id'         	=> new sfWidgetFormSelect(array('choices' => $items), array('style' => 'max-width:50%')),
              'amount_field'             	=> new sfWidgetFormInputText(),
              'profile_id'               	=> new sfWidgetFormInputText(),
              'purchase_type_quantity'	 	=> new sfWidgetFormInputHidden(),
              'purchase_type_description'	=> new sfWidgetFormInputHidden(),
              'purchase_user_field'		=> new sfWidgetFormSelect(array('choices' => $userArray), array('style' => 'max-width:50%')),
              'bill_cycle'               	=> new sfWidgetFormInputHidden(),
              'trans_time'      		=> new sfWidgetFormDate(array('format' => '%month%/%day%/%year%')),
              'user_institution_id' 		=> new sfWidgetFormInputHidden(),
              'purchase_type_eschool_id' 	=> new sfWidgetFormInputHidden(),
              'seller_id'         		=> new sfWidgetFormInputHidden(),
              'user_id'         		=> new sfWidgetFormInputHidden(),
              'amount'          		=> new sfWidgetFormInputHidden(),
              'gc_fee'				=> new sfWidgetFormInputHidden(),
              'owner_fee'			=> new sfWidgetFormInputHidden(),
              'purchase_type'			=> new sfWidgetFormInputHidden(),
            )
        );

        $this->widgetSchema->setLabels
        (
            array
            (
                'purchase_type_id' => 'Purchase Item:',
                'amount_field' => 'Payment Amount:',
                'profile_id' => 'Check Number/Paypal Txn#:',
                'purchase_user_field' => 'Purchasing User:',
                'trans_time' => 'Date of Transaction:',
            )
        );
        if (isset($purchase))
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
              'id'                       	=> new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
              'purchase_type_id'         	=> new sfValidatorString(array('required' => true)),
              'amount_field'               	=> new sfValidatorNumber(array('required' => true, 'min' => 0), array('min' => 'Invalid Amount.')),
              'profile_id'               	=> new sfValidatorString(array('required' => true)),
              'purchase_type_description'	=> new sfValidatorString(array('required' => true)),
              'purchase_type_quantity' 		=> new sfValidatorInteger(array('required' => true, 'min' => 1), array('min' => 'Required.')),
              'purchase_user_field'		=> new sfValidatorInteger(array('required' => true, 'min' => 1), array('min' => 'Required.')),
              'bill_cycle'			=> new sfValidatorString(array('required' => true)),
              'profile_id'			=> new sfValidatorString(array('required' => false)),
              'trans_time'    			=> new sfValidatorString(array('required' => true)),
              'user_institution_id' 		=> new sfValidatorString(array('required' => false)),
              'purchase_type_eschool_id' 	=> new sfValidatorString(array('required' => false)),
              'purchase_type' 			=> new sfValidatorString(array('required' => false)),
              'user_id'        			=> new sfValidatorInteger(array('required' => false)),
              'seller_id'         		=> new sfValidatorInteger(array('required' => false)),
              'amount'          		=> new sfValidatorNumber(array('required' => false)),
              'gc_fee'          		=> new sfValidatorNumber(array('required' => false)),
              'owner_fee'          		=> new sfValidatorNumber(array('required' => false)),
            )
        );
    }
}