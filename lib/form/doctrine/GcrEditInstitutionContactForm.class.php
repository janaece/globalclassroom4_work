<?php

/**
 * eschool form.
 *
 * @package    globalclassroom3
 * @subpackage form
 * @author     Steve Nelson
 */

class GcrEditInstitutionContactForm extends BaseGcrInstitutionForm
{
    public function configure()
    {
        $app = $this->getObject();
        $addressObject = $app->getAddressObject();
        $contact1Object = $app->getPersonObject();

        $this->setWidgets
        (
            array
            (
                'app_id'		=> new sfWidgetFormInputHidden(),
                'address_id'            => new sfWidgetFormInputHidden(),
                'person_id'		=> new sfWidgetFormInputHidden(),
                'first_name'		=> new sfWidgetFormInput(),
                'last_name'		=> new sfWidgetFormInput(),
                'street1'		=> new sfWidgetFormInput(),
                'street2'		=> new sfWidgetFormInput(),
                'city'			=> new sfWidgetFormInput(),
                'state'			=> new sfWidgetFormInput(),
                'zipcode'		=> new sfWidgetFormInput(),
                'phone'			=> new sfWidgetFormInput(),
                'email'			=> new sfWidgetFormInput(),
            )
        );

        $this->widgetSchema->setDefaults
        (
            array
            (
                'app_id'		=> $app->getId(),
                'address_id'		=> $addressObject->id,
                'person_id'		=> $contact1Object->id,
                'first_name'		=> $contact1Object->first_name,
                'last_name' 		=> $contact1Object->last_name,
                'street1'		=> $addressObject->street1,
                'street2'		=> $addressObject->street2,
                'city'     		=> $addressObject->city,
                'state'    		=> $addressObject->state,
                'zipcode' 		=> $addressObject->zipcode,
                'phone'  		=> $contact1Object->phone1,
                'email'  		=> $contact1Object->email,
            )
        );

        $this->widgetSchema->setLabels
        (
            array
            (
                'first_name' 	=> 'First Name:',
                'last_name' 	=> 'Last Name:',
                'street1'	=> 'Street Address:',
                'street2'	=> 'Address 2nd Line:',
                'city'		=> 'City:',
                'state'		=> 'State/Providence:',
                'zipcode'	=> 'Zipcode:',
                'phone'		=> 'Phone:',
                'email'		=> 'Email:',
            )
        );

        $this->setValidators
        (
            array
            (
                'app_id'	=> new sfValidatorString(array('max_length' => 100)),
                'address_id'    => new sfValidatorDoctrineChoice(array('model' => 'GcrAddress', 'column' => 'id')),
                'person_id'	=> new sfValidatorDoctrineChoice(array('model' => 'GcrPerson', 'column' => 'id')),
                'first_name'	=> new sfValidatorString(array('max_length' => 50)),
                'last_name'	=> new sfValidatorString(array('max_length' => 50)),
                'street1'	=> new sfValidatorString(array('max_length' => 100)),
                'street2'	=> new sfValidatorString(array('max_length' => 100, 'required' => false)),
                'city'		=> new sfValidatorString(array('max_length' => 50)),
                'state'		=> new sfValidatorString(array('max_length' => 50)),
                'zipcode'	=> new sfValidatorString(array('max_length' => 50)),
                'phone'		=> new sfValidatorString(array('max_length' => 50)),
                'email'		=> new sfValidatorEmail(array('max_length' => 100)),
            )
        );

    }
}