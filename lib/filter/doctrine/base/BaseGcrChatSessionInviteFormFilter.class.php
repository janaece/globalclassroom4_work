<?php

/**
 * GcrChatSessionInvite filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrChatSessionInviteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'time_created'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'session_id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_eschool_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'from_user_id'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'from_user_eschool_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'time_created'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'session_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_eschool_id'      => new sfValidatorPass(array('required' => false)),
      'from_user_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'from_user_eschool_id' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_chat_session_invite_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrChatSessionInvite';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'time_created'         => 'Number',
      'session_id'           => 'Number',
      'user_id'              => 'Number',
      'user_eschool_id'      => 'Text',
      'from_user_id'         => 'Number',
      'from_user_eschool_id' => 'Text',
    );
  }
}
