<?php

/**
 * GcrTrialApplication form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GcrTrialApplicationForm extends BaseGcrTrialApplicationForm
{
  public function configure()
  {
      require_once(gcr::moodleDir . 'lang/en/countries.php');
      array_unshift($string, 'Select Country');

      $this->setWidgets
      (
          array
          (
              'id'              => new sfWidgetFormInputHidden(),
              'first_name'      => new sfWidgetFormInput(),
              'last_name'	=> new sfWidgetFormInput(),
              'address'         => new sfWidgetFormInputHidden(),
              'contact'         => new sfWidgetFormInputHidden(),
              'street1'		=> new sfWidgetFormInput(),
              'street2'		=> new sfWidgetFormInput(),
              'city'		=> new sfWidgetFormInput(),
              'state'		=> new sfWidgetFormInput(),
              'zipcode'		=> new sfWidgetFormInput(),
              'country'		=> new sfWidgetFormSelect(array('choices' => $string)),
              'phone1'		=> new sfWidgetFormInput(),
              'phone2'		=> new sfWidgetFormInput(),
              'email'		=> new sfWidgetFormInput(),
              'verify_hash' 	=> new sfWidgetFormInputHidden(),
            )
        );
        $this->widgetSchema->setLabels
        (
            array
            (
                'street1'=> 'Street Address:',
                'street2' => 'Address 2nd Line:',
                'city'     => 'City:',
                'country'  => 'Country:',
                'first_name'  => 'First Name:',
                'last_name'  => 'Last Name:',
                'phone1'  => 'Phone:',
                'phone2'  => 'Phone Alt:',
                'email'   => 'Email:',
                'state'    => 'State/Province:',
                'zipcode' => 'Zipcode:',
            )
        );
        $this->setValidators
        (
            array
            (
              'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
              'contact'     => new sfValidatorInteger(array('required' => false)),
              'address'     => new sfValidatorInteger(array('required' => false)),
              'verify_hash' => new sfValidatorString(array('max_length' => 64)),
              'street1'     => new sfValidatorString(array('max_length' => 100)),
              'street2'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
              'city'        => new sfValidatorString(array('max_length' => 100)),
              'zipcode'     => new sfValidatorString(array('max_length' => 50)),
              'state'       => new sfValidatorString(array('max_length' => 50)),
              'country'     => new sfValidatorString(array('required' => false)),
              'first_name'  => new sfValidatorString(array('max_length' => 50)),
              'last_name'   => new sfValidatorString(array('max_length' => 50)),
              'phone1'      => new sfValidatorString(array('max_length' => 50)),
              'phone2'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
              'email'       => new sfValidatorEmail(array('max_length' => 100)),
            )
        );
    }
}
