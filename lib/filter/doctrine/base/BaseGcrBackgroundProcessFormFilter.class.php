<?php

/**
 * GcrBackgroundProcess filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrBackgroundProcessFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'job_data'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'process_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'job_data'     => new sfValidatorPass(array('required' => false)),
      'process_type' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_background_process_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrBackgroundProcess';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'job_data'     => 'Text',
      'process_type' => 'Text',
    );
  }
}
