<?php

/**
 * GcrChatSessionUsers filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrChatSessionUsersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_eschool_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'time_created'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_eschool_id' => new sfValidatorPass(array('required' => false)),
      'time_created'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_chat_session_users_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrChatSessionUsers';
  }

  public function getFields()
  {
    return array(
      'user_id'         => 'Number',
      'session_id'      => 'Number',
      'user_eschool_id' => 'Text',
      'time_created'    => 'Number',
    );
  }
}
