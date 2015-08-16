<?php

/**
 * eschool edit form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Ron Stewart
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class GcrEschoolEditForm extends BaseGcrEschoolForm
{
    public function configure()
    {
  	// access related database tables to load current data for eschool
  	$addressObject = $this->getObject()->getAddressObject();
  	$contact1Object = $this->getObject()->getPersonObject();
  	$contact2Object = $this->getObject()->getPerson2Object();
  	
  	$this->setWidgets
        (
            array
            (
              'id'              => new sfWidgetFormInputHidden(),
              'full_name'       => new sfWidgetFormInput(),
              'external_url'    => new sfWidgetFormInput(),
              'contact1'        => new sfWidgetFormInputHidden(),
              'contact2'        => new sfWidgetFormInputHidden(),
              'address'		=> new sfWidgetFormInputHidden(),
              'visible'         => new sfWidgetFormInputCheckbox(),
              'street1' 	=> new sfWidgetFormInput(array(), array('value' => $addressObject->street1)),
              'street2' 	=> new sfWidgetFormInput(array(), array('value' => $addressObject->street2)),
              'city'    	=> new sfWidgetFormInput(array(), array('value' => $addressObject->city)),
              'zipcode' 	=> new sfWidgetFormInput(array(), array('value' => $addressObject->zipcode)),
              'state'   	=> new sfWidgetFormInput(array(), array('value' => $addressObject->state)),
              'country' 	=> new sfWidgetFormInput(array(), array('value' => $addressObject->country)),
              'first_name' 	=> new sfWidgetFormInput(array(), array('value' => $contact1Object->first_name)),
              'last_name'  	=> new sfWidgetFormInput(array(), array('value' => $contact1Object->last_name)),
              'phone1'     	=> new sfWidgetFormInput(array(), array('value' => $contact1Object->phone1)),
              'phone2'     	=> new sfWidgetFormInput(array(), array('value' => $contact1Object->phone2)),
              'email'      	=> new sfWidgetFormInput(array(), array('value' => $contact1Object->email)),
              'first_name_2' 	=> new sfWidgetFormInput(array(), array('value' => $contact2Object->first_name)),
              'last_name_2'  	=> new sfWidgetFormInput(array(), array('value' => $contact2Object->last_name)),
              'phone1_2'     	=> new sfWidgetFormInput(array(), array('value' => $contact2Object->phone1)),
              'phone2_2'     	=> new sfWidgetFormInput(array(), array('value' => $contact2Object->phone2)),
              'email_2'      	=> new sfWidgetFormInput(array(), array('value' => $contact2Object->email)),
            )
        );
    
        $this->widgetSchema->setLabels
	(
            array
            (
                 'eschool_type'   => 'Type of eSchool:',
                 'full_name' => 'Full Name of eSchool:',
                 'external_url' => 'Requested New URL:',
                 'short_name'    => 'eSchool URL:',
                 'street1'=> 'Street Address:',
                 'street2' => 'Address 2nd Line:',
                 'city'     => 'City:',
                 'country'  => 'Country:',
                 'first_name'  => 'First Name:',
                 'last_name'  => 'Last Name:',
                 'phone1'  => 'Phone:',
                 'phone2'  => 'Phone Alt:',
                 'email'   => 'Email:',
                 'first_name_2'  => 'First Name:',
                 'last_name_2'  => 'Last Name:',
                 'phone1_2'  => 'Phone:',
                 'phone2_2'  => 'Phone Alt:',
                 'email_2'   => 'Email:',
                 'state'    => 'State:',
                 'zipcode' => 'Zipcode:',
            )
	);

    $this->setValidators(array(
      'id'        	=> new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'full_name'       => new sfValidatorString(array('max_length' => 60)),
      'external_url'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'suspended'       => new sfValidatorBoolean(),
      'contact1'        => new sfValidatorInteger(),
      'contact2'        => new sfValidatorInteger(array('required' => false)),
      'address'         => new sfValidatorInteger(array('required' => false)),
      'visible'         => new sfValidatorBoolean(),
      'street1' 	=> new sfValidatorString(array('max_length' => 100)),
      'street2'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'city'    	=> new sfValidatorString(array('max_length' => 100)),
      'zipcode' 	=> new sfValidatorString(array('max_length' => 50)),
      'state'  		=> new sfValidatorString(array('max_length' => 50)),
      'country'		=> new sfValidatorString(array('max_length' => 50)),
      'first_name' 	=> new sfValidatorString(array('max_length' => 50)),
      'last_name'  	=> new sfValidatorString(array('max_length' => 50)),
      'phone1'    	=> new sfValidatorString(array('max_length' => 50)),
      'phone2'    	=> new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'email'     	=> new sfValidatorEmail(array('max_length' => 100)),
      'first_name_2'	=> new sfValidatorString(array('max_length' => 50)),
      'last_name_2'  	=> new sfValidatorString(array('max_length' => 50)),
      'phone1_2'     	=> new sfValidatorString(array('max_length' => 50)),
      'phone2_2'     	=> new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'email_2'     	=> new sfValidatorEmail(array('max_length' => 100)),
    )); 
  	
  }
}
