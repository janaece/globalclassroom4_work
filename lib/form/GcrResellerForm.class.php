<?php

class ResellerForm extends BaseForm
{	
	public function configure()
	{
		$type_options = array(
			'Other' => 'Other',
			'Training' => 'Training',
			'Course Development' => 'Course Development',
			'Educational Service Provider' => 'Educational Service Provider',
			'IT Service Provider' => 'IT Service Provider',
			'Educational Hardware/Software' => 'Educational Hardware/Software',
			'Educational Consultant' => 'Educational Consultant'
		);
		
		$this->disableLocalCSRFProtection();
		
		$this->setWidgets(array(
			'first-name'		=> new sfWidgetFormInputText(),
			'last-name'			=> new sfWidgetFormInputText(),
			'email'				=> new sfWidgetFormInputText(),
			'phone'				=> new sfWidgetFormInputText(),
			'legal-name'		=> new sfWidgetFormInputText(),
			'position'			=> new sfWidgetFormInputText(),
			'length'			=> new sfWidgetFormInputText(),
			'type'				=> new sfWidgetFormSelect(array('choices' => $type_options)),
			'address1'			=> new sfWidgetFormInputText(),
			'address2'			=> new sfWidgetFormInputText(),
			'city'				=> new sfWidgetFormInputText(),
			'state'				=> new sfWidgetFormInputText(),
			'zipcode'			=> new sfWidgetFormInputText(),
			'country'			=> new sfWidgetFormInputText(),
			'url'				=> new sfWidgetFormInputText(),
		));
		$this->widgetSchema->setLabels(array(
			'first-name'		=> 'First Name:',
			'last-name'			=> 'Last Name:',
			'email'				=> 'Contact Email:',
			'phone'				=> 'Phone Number:',
			'legal-name'		=> 'Registered Legal Name:',
			'position'			=> 'Your Current Position:',
			'length'			=> 'How long has your company been in business?',
			'type'				=> 'Type of Education Business:',
			'address1'			=> 'Address 1:',
			'address2'			=> 'Address 2:',
			'city'				=> 'City:',
			'state'				=> 'State/Province:',
			'zipcode'			=> 'Postal Code:',
			'country'			=> 'Country:',
			'url'				=> 'Website URL:',
		));
		$this->setDefaults(array(
			'type'				=> 'Other',
		));
		$this->setValidators(array(
			'first-name'		=> new sfValidatorString(array('required' => true), array('required' => '*First name is required')),
			'last-name'			=> new sfValidatorString(array('required' => true), array('required' => '*Last name is required')),
			'email'				=> new sfValidatorEmail(array(), array('invalid' => '*The email address is invalid.', 'required' => '*Email is required')),
			'phone'				=> new sfValidatorString(array('min_length' => 10), array('required' => '*Phone number is required')),
			'legal-name'		=> new sfValidatorString(array('required' => false)),
			'position'			=> new sfValidatorString(array('required' => false)),
			'length'			=> new sfValidatorString(array('required' => false)),
			'type'				=> new sfValidatorChoice(array('choices' => $type_options)),
			'address1'			=> new sfValidatorString(array('required' => true), array('required' => '*Address is required')),
			'address2'			=> new sfValidatorString(array('required' => false)),
			'city'				=> new sfValidatorString(array('required' => true), array('required' => '*City is required')),
			'state'				=> new sfValidatorString(array('required' => true), array('required' => '*State/Province is required')),
			'zipcode'			=> new sfValidatorString(array('required' => true), array('required' => '*Postal code is required')),
			'country'			=> new sfValidatorString(array('required' => true), array('required' => '*Country is required')),
			'url'				=> new sfValidatorString(array('required' => false)),
		));
	}
}