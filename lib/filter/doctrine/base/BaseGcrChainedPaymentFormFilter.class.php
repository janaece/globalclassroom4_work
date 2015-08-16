<?php

/**
 * GcrChainedPayment filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrChainedPaymentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_institution_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_institution_id' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_chained_payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrChainedPayment';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'user_id'             => 'Number',
      'user_institution_id' => 'Text',
    );
  }
}
