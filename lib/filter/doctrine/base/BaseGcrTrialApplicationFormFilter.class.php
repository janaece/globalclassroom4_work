<?php

/**
 * GcrTrialApplication filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrTrialApplicationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'contact'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'verify_hash' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'contact'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'address'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'verify_hash' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_trial_application_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrTrialApplication';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'contact'     => 'Number',
      'address'     => 'Number',
      'verify_hash' => 'Text',
    );
  }
}
