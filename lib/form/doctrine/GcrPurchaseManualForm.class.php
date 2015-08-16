<?php
class GcrPurchaseManualForm extends BaseGcrPurchaseForm
{
	public function configure()
	{
		$this->setWidgets(array(
	      'id'                       => new sfWidgetFormInputHidden(),
	      'purchase_type'            => new sfWidgetFormInputHidden(),
	      'purchase_type_id'         => new sfWidgetFormInputHidden(),
	      'purchase_type_eschool_id' => new sfWidgetFormInputHidden(),
	      'trans_time'               => new sfWidgetFormInputHidden(),
	      'user_eschool_id'          => new sfWidgetFormInputHidden(),
	      'user_id'                  => new sfWidgetFormInputHidden(),
	      'amount'                   => new sfWidgetFormInputText(array(), array('style' => 'width:50%')),
	      'bill_cycle'               => new sfWidgetFormDate(array('format' => '%month%/%day%/%year%')),
	      'profile_id'               => new sfWidgetFormInputText(),
	      'gc_fee'                   => new sfWidgetFormInputHidden(),
		  'owner_fee'                => new sfWidgetFormInputHidden(),
		  'purchase_type_description'=> new sfWidgetFormInputHidden(),
	    ));
	    
	    $this->widgetSchema->setDefaults
		(
			array
			(
				'gc_fee'     	=> 0,
			    'owner_fee'		=> 0,
				'trans_time' => time(), 
			)
		);
		
		$this->widgetSchema->setLabels
		(
			array
			(
				'bill_cycle' => 'Payment Period End Date:',
				'amount' => 'Payment Amount:',
				'profile_id' => 'Check Number:',
			)
		);
		
	     $this->setValidators(array(
	      'id'                       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
	      'purchase_type'            => new sfValidatorString(array('required' => true)),
	      'purchase_type_id'         => new sfValidatorString(array('required' => true)),
	      'purchase_type_eschool_id' => new sfValidatorString(array('required' => true)),
	      'trans_time'               => new sfValidatorInteger(array('required' => false)),
	      'user_eschool_id'          => new sfValidatorString(array('required' => true)),
	      'user_id'                  => new sfValidatorInteger(array('required' => true)),
	      'amount'                   => new sfValidatorNumber(array('required' => true)),
	      'bill_cycle'               => new sfValidatorInteger(array('required' => true)),
	      'profile_id'               => new sfValidatorString(array('required' => true)),
	      'gc_fee'                   => new sfValidatorNumber(array('required' => false)),
	      'owner_fee'                => new sfValidatorNumber(array('required' => false)),
	      'purchase_type_description'=> new sfValidatorString(array('required' => true)),
	    ));
	}
}