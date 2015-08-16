<?php

/**
 * GcrPayoff form base class.
 *
 * @method GcrPayoff getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrPayoffForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'trans_time'      => new sfWidgetFormInputText(),
      'purchase_id'     => new sfWidgetFormInputText(),
      'eschool_id'      => new sfWidgetFormTextarea(),
      'user_id'         => new sfWidgetFormInputText(),
      'credentials_id'  => new sfWidgetFormInputText(),
      'user_eschool_id' => new sfWidgetFormTextarea(),
      'payoff_status'   => new sfWidgetFormTextarea(),
      'payoff_type'     => new sfWidgetFormTextarea(),
      'amount'          => new sfWidgetFormInputText(),
      'recipient_name'  => new sfWidgetFormTextarea(),
      'address'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'trans_time'      => new sfValidatorInteger(array('required' => false)),
      'purchase_id'     => new sfValidatorInteger(array('required' => false)),
      'eschool_id'      => new sfValidatorString(array('required' => false)),
      'user_id'         => new sfValidatorInteger(array('required' => false)),
      'credentials_id'  => new sfValidatorInteger(array('required' => false)),
      'user_eschool_id' => new sfValidatorString(array('required' => false)),
      'payoff_status'   => new sfValidatorString(array('required' => false)),
      'payoff_type'     => new sfValidatorString(array('required' => false)),
      'amount'          => new sfValidatorNumber(array('required' => false)),
      'recipient_name'  => new sfValidatorString(array('required' => false)),
      'address'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_payoff[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPayoff';
  }

}
