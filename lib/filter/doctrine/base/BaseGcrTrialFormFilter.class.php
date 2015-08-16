<?php

/**
 * GcrTrial filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrTrialFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'organization_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'start_date'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end_date'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'organization_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_date'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_date'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_trial_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrTrial';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'organization_id' => 'Number',
      'start_date'      => 'Number',
      'end_date'        => 'Number',
    );
  }
}
