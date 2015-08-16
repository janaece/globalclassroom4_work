<?php

/**
 * GcrEschoolMonthlyData filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrEschoolMonthlyDataFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'eschool_id'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'month_value'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'year_value'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eschool_balance'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eclassroom_balance' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gross'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gc_fee'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'seller_fee'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'num_users'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'num_courses'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'eschool_id'         => new sfValidatorPass(array('required' => false)),
      'month_value'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'year_value'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'eschool_balance'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'eclassroom_balance' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gross'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gc_fee'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'seller_fee'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'num_users'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'num_courses'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_eschool_monthly_data_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEschoolMonthlyData';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'eschool_id'         => 'Text',
      'month_value'        => 'Number',
      'year_value'         => 'Number',
      'eschool_balance'    => 'Number',
      'eclassroom_balance' => 'Number',
      'gross'              => 'Number',
      'gc_fee'             => 'Number',
      'seller_fee'         => 'Number',
      'num_users'          => 'Number',
      'num_courses'        => 'Number',
    );
  }
}
