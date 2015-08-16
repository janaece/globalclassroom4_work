<?php

/**
 * GcrWantsUrl form base class.
 *
 * @method GcrWantsUrl getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrWantsUrlForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'time_created'  => new sfWidgetFormInputText(),
      'wants_url'     => new sfWidgetFormTextarea(),
      'redirect_type' => new sfWidgetFormTextarea(),
      'app_id'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'time_created'  => new sfValidatorInteger(array('required' => false)),
      'wants_url'     => new sfValidatorString(array('required' => false)),
      'redirect_type' => new sfValidatorString(array('required' => false)),
      'app_id'        => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_wants_url[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrWantsUrl';
  }

}
