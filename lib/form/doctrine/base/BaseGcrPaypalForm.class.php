<?php

/**
 * GcrPaypal form base class.
 *
 * @method GcrPaypal getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrPaypalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'txn_id'               => new sfWidgetFormInputHidden(),
      'mc_gross'             => new sfWidgetFormInputText(),
      'payer_id'             => new sfWidgetFormTextarea(),
      'receiver_id'          => new sfWidgetFormTextarea(),
      'payment_status'       => new sfWidgetFormTextarea(),
      'receipt_id'           => new sfWidgetFormTextarea(),
      'mc_fee'               => new sfWidgetFormInputText(),
      'tax'                  => new sfWidgetFormInputText(),
      'shipping'             => new sfWidgetFormInputText(),
      'currency_code'        => new sfWidgetFormTextarea(),
      'parent_txn_id'        => new sfWidgetFormTextarea(),
      'first_name'           => new sfWidgetFormTextarea(),
      'last_name'            => new sfWidgetFormTextarea(),
      'recurring_payment_id' => new sfWidgetFormTextarea(),
      'payment_date'         => new sfWidgetFormTextarea(),
      'gc_fee'               => new sfWidgetFormInputText(),
      'commission_fee'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'txn_id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('txn_id')), 'empty_value' => $this->getObject()->get('txn_id'), 'required' => false)),
      'mc_gross'             => new sfValidatorNumber(array('required' => false)),
      'payer_id'             => new sfValidatorString(array('required' => false)),
      'receiver_id'          => new sfValidatorString(array('required' => false)),
      'payment_status'       => new sfValidatorString(array('required' => false)),
      'receipt_id'           => new sfValidatorString(array('required' => false)),
      'mc_fee'               => new sfValidatorNumber(array('required' => false)),
      'tax'                  => new sfValidatorNumber(array('required' => false)),
      'shipping'             => new sfValidatorNumber(array('required' => false)),
      'currency_code'        => new sfValidatorString(array('required' => false)),
      'parent_txn_id'        => new sfValidatorString(array('required' => false)),
      'first_name'           => new sfValidatorString(array('required' => false)),
      'last_name'            => new sfValidatorString(array('required' => false)),
      'recurring_payment_id' => new sfValidatorString(array('required' => false)),
      'payment_date'         => new sfValidatorString(array('required' => false)),
      'gc_fee'               => new sfValidatorNumber(array('required' => false)),
      'commission_fee'       => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_paypal[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPaypal';
  }

}
