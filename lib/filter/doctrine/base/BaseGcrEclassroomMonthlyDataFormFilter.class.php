<?php

/**
 * GcrEclassroomMonthlyData filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrEclassroomMonthlyDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'month_value'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'year_value'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eclassroom_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eclassroom_balance' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gross'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gc_fee'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'owner_fee'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'month_value'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'year_value'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'eclassroom_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'eclassroom_balance' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gross'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gc_fee'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'owner_fee'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_eclassroom_monthly_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEclassroomMonthlyData';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'month_value'        => 'Number',
      'year_value'         => 'Number',
      'eclassroom_id'      => 'Number',
      'eclassroom_balance' => 'Number',
      'gross'              => 'Number',
      'gc_fee'             => 'Number',
      'owner_fee'          => 'Number',
    );
  }
}
