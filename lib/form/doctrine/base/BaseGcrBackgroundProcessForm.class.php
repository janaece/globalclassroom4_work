<?php

/**
 * GcrBackgroundProcess form base class.
 *
 * @method GcrBackgroundProcess getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrBackgroundProcessForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'job_data'     => new sfWidgetFormTextarea(),
      'process_type' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'job_data'     => new sfValidatorString(array('required' => false)),
      'process_type' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_background_process[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrBackgroundProcess';
  }

}
