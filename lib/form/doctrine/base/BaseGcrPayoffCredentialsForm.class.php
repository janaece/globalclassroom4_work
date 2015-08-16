<?php

/**
 * GcrPayoffCredentials form base class.
 *
 * @method GcrPayoffCredentials getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrPayoffCredentialsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'user_business_name' => new sfWidgetFormTextarea(),
      'user_first_name'    => new sfWidgetFormTextarea(),
      'user_last_name'     => new sfWidgetFormTextarea(),
      'user_paypal_email'  => new sfWidgetFormTextarea(),
      'user_tin'           => new sfWidgetFormTextarea(),
      'user_eschool_id'    => new sfWidgetFormTextarea(),
      'user_id'            => new sfWidgetFormInputText(),
      'verify_status'      => new sfWidgetFormTextarea(),
      'verify_hash'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_business_name' => new sfValidatorString(array('required' => false)),
      'user_first_name'    => new sfValidatorString(array('required' => false)),
      'user_last_name'     => new sfValidatorString(array('required' => false)),
      'user_paypal_email'  => new sfValidatorString(array('required' => false)),
      'user_tin'           => new sfValidatorString(array('required' => false)),
      'user_eschool_id'    => new sfValidatorString(array('required' => false)),
      'user_id'            => new sfValidatorInteger(array('required' => false)),
      'verify_status'      => new sfValidatorString(array('required' => false)),
      'verify_hash'        => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_payoff_credentials[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPayoffCredentials';
  }

}
