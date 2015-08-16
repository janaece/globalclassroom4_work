<?php

/**
 * GcrPaypal filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrPaypalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mc_gross'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payer_id'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'receiver_id'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payment_status'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'receipt_id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mc_fee'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tax'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'shipping'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'currency_code'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'parent_txn_id'        => new sfWidgetFormFilterInput(),
      'first_name'           => new sfWidgetFormFilterInput(),
      'last_name'            => new sfWidgetFormFilterInput(),
      'recurring_payment_id' => new sfWidgetFormFilterInput(),
      'payment_date'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gc_fee'               => new sfWidgetFormFilterInput(),
      'commission_fee'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'mc_gross'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'payer_id'             => new sfValidatorPass(array('required' => false)),
      'receiver_id'          => new sfValidatorPass(array('required' => false)),
      'payment_status'       => new sfValidatorPass(array('required' => false)),
      'receipt_id'           => new sfValidatorPass(array('required' => false)),
      'mc_fee'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tax'                  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'currency_code'        => new sfValidatorPass(array('required' => false)),
      'parent_txn_id'        => new sfValidatorPass(array('required' => false)),
      'first_name'           => new sfValidatorPass(array('required' => false)),
      'last_name'            => new sfValidatorPass(array('required' => false)),
      'recurring_payment_id' => new sfValidatorPass(array('required' => false)),
      'payment_date'         => new sfValidatorPass(array('required' => false)),
      'gc_fee'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'commission_fee'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_paypal_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPaypal';
  }

  public function getFields()
  {
    return array(
      'txn_id'               => 'Text',
      'mc_gross'             => 'Number',
      'payer_id'             => 'Text',
      'receiver_id'          => 'Text',
      'payment_status'       => 'Text',
      'receipt_id'           => 'Text',
      'mc_fee'               => 'Number',
      'tax'                  => 'Number',
      'shipping'             => 'Number',
      'currency_code'        => 'Text',
      'parent_txn_id'        => 'Text',
      'first_name'           => 'Text',
      'last_name'            => 'Text',
      'recurring_payment_id' => 'Text',
      'payment_date'         => 'Text',
      'gc_fee'               => 'Number',
      'commission_fee'       => 'Number',
    );
  }
}
