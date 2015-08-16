<?php

/**
 * GcrAutoLogin form base class.
 *
 * @method GcrAutoLogin getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrAutoLoginForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'app_id'        => new sfWidgetFormTextarea(),
      'user_password' => new sfWidgetFormTextarea(),
      'user_token'    => new sfWidgetFormTextarea(),
      'username'      => new sfWidgetFormTextarea(),
      'expire'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'app_id'        => new sfValidatorString(array('required' => false)),
      'user_password' => new sfValidatorString(array('required' => false)),
      'user_token'    => new sfValidatorString(array('required' => false)),
      'username'      => new sfValidatorString(array('required' => false)),
      'expire'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_auto_login[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrAutoLogin';
  }

}
