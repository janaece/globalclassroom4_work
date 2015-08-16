<?php

/**
 * eschool form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Ron Stewart
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class GcrEschoolForm extends BaseGcrEschoolForm
{
    public function configure()
    {
        global $CFG;
        $person1 = $CFG->current_app->getPersonObject();
        $person2 = $CFG->current_app->getPerson2Object();
        $address = $CFG->current_app->getAddressObject();

        // Get a list of eschool types. Only show public templates if user is not an admin.
        $typeList = GcrEschoolTypeTable::getEschoolTypes(!$CFG->current_app->hasPrivilege('GCUser'));
        foreach ($typeList as $type)
        {
            $existingTypes[$type->id] = $type->name;
        }
        require_once(gcr::moodleDir . 'lang/en/countries.php');
        array_unshift($string, 'Select Country');

        $this->setWidgets
        (
            array
            (
                'id'                => new sfWidgetFormInputHidden(),
                'full_name'         => new sfWidgetFormInput(),
                'external_url'      => new sfWidgetFormInputHidden(),
                'suspended'         => new sfWidgetFormInputHidden(),
                'logo'              => new sfWidgetFormInputHidden(),
                'can_sell'          => new sfWidgetFormInputHidden(),
                'contact1'          => new sfWidgetFormInputHidden(),
                'contact2'          => new sfWidgetFormInputHidden(),
                'address'           => new sfWidgetFormInputHidden(),
                'eschool_type'      => new sfWidgetFormSelect(array('choices' => $existingTypes)),
                'visible'           => new sfWidgetFormInputHidden(),
                'short_name'        => new sfWidgetFormInput(),
                'admin_password'    => new sfWidgetFormInputHidden(),
                'eschool_creator'   => new sfWidgetFormInputHidden(),
                'password_salt'     => new sfWidgetFormInputHidden(),
                'creation_date'     => new sfWidgetFormInputHidden(),
                'organization_id'   => new sfWidgetFormInputHidden(),
                'street1'           => new sfWidgetFormInput(),
                'street2'           => new sfWidgetFormInput(),
                'city'              => new sfWidgetFormInput(),
                'state'             => new sfWidgetFormInput(),
                'zipcode'           => new sfWidgetFormInput(),
                'country'           => new sfWidgetFormSelect(array('choices' => $string)),
                'first_name'        => new sfWidgetFormInput(),
                'last_name'         => new sfWidgetFormInput(),
                'phone1'            => new sfWidgetFormInput(),
                'phone2'            => new sfWidgetFormInput(),
                'email'             => new sfWidgetFormInput(),
                'first_name_2'      => new sfWidgetFormInput(),
                'last_name_2'       => new sfWidgetFormInput(),
                'phone1_2'          => new sfWidgetFormInput(),
                'phone2_2'          => new sfWidgetFormInput(),
                'email_2'           => new sfWidgetFormInput(),
            )
        );

        $this->widgetSchema->setDefaults
        (
            array
            (
                'street1'       => $address->getStreet1(),
                'street2'       => $address->getStreet2(),
                'city'          => $address->getCity(),
                'state'         => $address->getState(),
                'country'       => $address->getCountry(),
                'zipcode'       => $address->getZipcode(),
                'first_name'    => $person1->getFirstName(),
                'last_name'     => $person1->getLastName(),
                'phone1'        => $person1->getPhone1(),
                'phone2'        => $person1->getPhone2(),
                'email'         => $person1->getEmail(),
                'first_name_2'  => $person2->getFirstName(),
                'last_name_2'   => $person2->getLastName(),
                'phone1_2'      => $person2->getPhone1(),
                'phone2_2'      => $person2->getPhone2(),
                'email_2'       => $person2->getEmail(),
            )
        );

        $this->widgetSchema->setLabels
        (
            array
            (
                'eschool_type'   => 'Type:',
                'full_name' => 'Full Name:',
                'short_name'    => 'URL:',
                'street1' => 'Street Address:',
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
                'state'    => 'State/Province:',
                'zipcode' => 'Zipcode:',
            )
        );

        $this->setValidators
    (
        array
        (
                'id'        	  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
                'full_name'       => new sfValidatorString(array('max_length' => 60)),
                'external_url'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
                'suspended'       => new sfValidatorBoolean(),
                'logo'            => new sfValidatorString(array('max_length' => 300, 'required' => false)),
                'can_sell'        => new sfValidatorBoolean(),
                'contact1'        => new sfValidatorInteger(),
                'contact2'        => new sfValidatorInteger(array('required' => false)),
                'address'         => new sfValidatorInteger(array('required' => false)),
                'eschool_type'    => new sfValidatorInteger(array('min' => 0), array('min' => 'Eschool Type Is Invalid')),
                'visible'         => new sfValidatorBoolean(),
                'short_name'      => new sfValidatorString(array('max_length' => 32)),
                'admin_password'  => new sfValidatorString(array('max_length' => 64)),
                'eschool_creator' => new sfValidatorInteger(),
                'organization_id' => new sfValidatorInteger(),
                'password_salt'   => new sfValidatorString(array('max_length' => 64, 'min_length' => 64)),
                'creation_date'	=> new sfValidatorInteger(),
                'street1' => new sfValidatorString(array('max_length' => 100)),
                'street2' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
                'city'    => new sfValidatorString(array('max_length' => 100)),
                'zipcode' => new sfValidatorString(array('max_length' => 50)),
                'state'   => new sfValidatorString(array('max_length' => 50)),
                'country' => new sfValidatorString(array('required' => false)),
                'first_name' => new sfValidatorString(array('max_length' => 50)),
                'last_name'  => new sfValidatorString(array('max_length' => 50)),
                'phone1'     => new sfValidatorString(array('max_length' => 50)),
                'phone2'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
                'email'      => new sfValidatorEmail(array('max_length' => 100)),
                'first_name_2' => new sfValidatorString(array('max_length' => 50)),
                'last_name_2'  => new sfValidatorString(array('max_length' => 50)),
                'phone1_2'     => new sfValidatorString(array('max_length' => 50)),
                'phone2_2'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
                'email_2'      => new sfValidatorEmail(array('max_length' => 100)),
            )
        );
    }
}