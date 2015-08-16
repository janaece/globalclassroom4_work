<?php
class GcrPurchaseEschoolManualForm extends BaseGcrPurchaseForm
{
    public function configure()
    {
        if ($this->getObject()->getId())
        {
            $purchase = $this->getObject();
        }

        $this->setWidgets
        (
            array
            (
              'id'                       	=> new sfWidgetFormInputHidden(),
              'purchase_type_id'         	=> new sfWidgetFormInputHidden(),
              'amount_field'             	=> new sfWidgetFormInputText(),
              'profile_id'               	=> new sfWidgetFormInputText(),
              'purchase_type_quantity'	 	=> new sfWidgetFormInputHidden(),
              'purchase_type_description'	=> new sfWidgetFormInputHidden(),
              'bill_cycle'               	=> new sfWidgetFormDate(array('format' => '%month%/%day%/%year%')),
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
                'amount_field' => 'Payment Amount:',
                'profile_id' => 'Check Number:',
                'purchase_user_field' => 'Purchasing User:',
                'bill_cycle' => 'Date Paid Until:',
                'trans_time' => 'Date of Transaction:',
            )
        );
        if (isset($purchase))
        {
            $this->widgetSchema->setDefaults
            (
                array
                (
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