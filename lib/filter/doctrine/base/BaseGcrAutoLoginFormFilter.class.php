<?php

/**
 * GcrAutoLogin filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrAutoLoginFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'app_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_password' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_token'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'expire'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'app_id'        => new sfValidatorPass(array('required' => false)),
      'user_password' => new sfValidatorPass(array('required' => false)),
      'user_token'    => new sfValidatorPass(array('required' => false)),
      'username'      => new sfValidatorPass(array('required' => false)),
      'expire'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_auto_login_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrAutoLogin';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'app_id'        => 'Text',
      'user_password' => 'Text',
      'user_token'    => 'Text',
      'username'      => 'Text',
      'expire'        => 'Number',
    );
  }
}
