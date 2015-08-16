<?php

/**
 * GcrChatSessionUsers form base class.
 *
 * @method GcrChatSessionUsers getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrChatSessionUsersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'         => new sfWidgetFormInputHidden(),
      'session_id'      => new sfWidgetFormInputHidden(),
      'user_eschool_id' => new sfWidgetFormTextarea(),
      'time_created'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'session_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('session_id')), 'empty_value' => $this->getObject()->get('session_id'), 'required' => false)),
      'user_eschool_id' => new sfValidatorString(array('required' => false)),
      'time_created'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_chat_session_users[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrChatSessionUsers';
  }

}
