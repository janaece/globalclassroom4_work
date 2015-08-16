<?php

/**
 * GcrUserMonthlyData filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrUserMonthlyDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'month_value'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'year_value'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_institution_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_balance'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gross'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gc_fee'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'owner_fee'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'month_value'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'year_value'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_institution_id' => new sfValidatorPass(array('required' => false)),
      'user_balance'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gross'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gc_fee'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'owner_fee'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_user_monthly_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrUserMonthlyData';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'month_value'         => 'Number',
      'year_value'          => 'Number',
      'user_id'             => 'Number',
      'user_institution_id' => 'Text',
      'user_balance'        => 'Number',
      'gross'               => 'Number',
      'gc_fee'              => 'Number',
      'owner_fee'           => 'Number',
    );
  }
}
