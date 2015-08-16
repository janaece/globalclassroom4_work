<?php

/**
 * GcrChatSession filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrChatSessionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'time_created' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'room_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eschool_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'time_created' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'room_id'      => new sfValidatorPass(array('required' => false)),
      'eschool_id'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_chat_session_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrChatSession';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'time_created' => 'Number',
      'room_id'      => 'Text',
      'eschool_id'   => 'Text',
    );
  }
}
