<?php

/**
 * GcrCommission filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrCommissionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'institution_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eschool_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'commission_rate' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'institution_id'  => new sfValidatorPass(array('required' => false)),
      'eschool_id'      => new sfValidatorPass(array('required' => false)),
      'commission_rate' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_commission_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrCommission';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'institution_id'  => 'Text',
      'eschool_id'      => 'Text',
      'commission_rate' => 'Number',
    );
  }
}
