<?php

/**
 * GcrChatSession form base class.
 *
 * @method GcrChatSession getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrChatSessionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'time_created' => new sfWidgetFormInputText(),
      'room_id'      => new sfWidgetFormTextarea(),
      'eschool_id'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'time_created' => new sfValidatorInteger(array('required' => false)),
      'room_id'      => new sfValidatorString(array('required' => false)),
      'eschool_id'   => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_chat_session[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrChatSession';
  }

}
