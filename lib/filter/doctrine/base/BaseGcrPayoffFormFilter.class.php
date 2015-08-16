<?php

/**
 * GcrPayoff filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrPayoffFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'trans_time'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'purchase_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eschool_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'credentials_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_eschool_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payoff_status'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payoff_type'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'recipient_name'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'trans_time'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'purchase_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'eschool_id'      => new sfValidatorPass(array('required' => false)),
      'user_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'credentials_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_eschool_id' => new sfValidatorPass(array('required' => false)),
      'payoff_status'   => new sfValidatorPass(array('required' => false)),
      'payoff_type'     => new sfValidatorPass(array('required' => false)),
      'amount'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'recipient_name'  => new sfValidatorPass(array('required' => false)),
      'address'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_payoff_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPayoff';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'trans_time'      => 'Number',
      'purchase_id'     => 'Number',
      'eschool_id'      => 'Text',
      'user_id'         => 'Number',
      'credentials_id'  => 'Number',
      'user_eschool_id' => 'Text',
      'payoff_status'   => 'Text',
      'payoff_type'     => 'Text',
      'amount'          => 'Number',
      'recipient_name'  => 'Text',
      'address'         => 'Number',
    );
  }
}
