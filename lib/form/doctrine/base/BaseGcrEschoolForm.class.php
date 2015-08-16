<?php

/**
 * GcrEschool form base class.
 *
 * @method GcrEschool getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrEschoolForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'full_name'       => new sfWidgetFormTextarea(),
      'external_url'    => new sfWidgetFormTextarea(),
      'suspended'       => new sfWidgetFormInputCheckbox(),
      'logo'            => new sfWidgetFormTextarea(),
      'can_sell'        => new sfWidgetFormInputCheckbox(),
      'contact1'        => new sfWidgetFormInputText(),
      'contact2'        => new sfWidgetFormInputText(),
      'address'         => new sfWidgetFormInputText(),
      'eschool_type'    => new sfWidgetFormInputText(),
      'visible'         => new sfWidgetFormInputCheckbox(),
      'short_name'      => new sfWidgetFormTextarea(),
      'password_salt'   => new sfWidgetFormTextarea(),
      'reset_keys'      => new sfWidgetFormInputCheckbox(),
      'is_public'       => new sfWidgetFormInputCheckbox(),
      'admin_password'  => new sfWidgetFormTextarea(),
      'eschool_creator' => new sfWidgetFormInputText(),
      'creation_date'   => new sfWidgetFormInputText(),
      'organization_id' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'full_name'       => new sfValidatorString(array('required' => false)),
      'external_url'    => new sfValidatorString(array('required' => false)),
      'suspended'       => new sfValidatorBoolean(array('required' => false)),
      'logo'            => new sfValidatorString(array('required' => false)),
      'can_sell'        => new sfValidatorBoolean(array('required' => false)),
      'contact1'        => new sfValidatorInteger(array('required' => false)),
      'contact2'        => new sfValidatorInteger(array('required' => false)),
      'address'         => new sfValidatorInteger(array('required' => false)),
      'eschool_type'    => new sfValidatorInteger(array('required' => false)),
      'visible'         => new sfValidatorBoolean(array('required' => false)),
      'short_name'      => new sfValidatorString(array('required' => false)),
      'password_salt'   => new sfValidatorString(array('required' => false)),
      'reset_keys'      => new sfValidatorBoolean(array('required' => false)),
      'is_public'       => new sfValidatorBoolean(array('required' => false)),
      'admin_password'  => new sfValidatorString(array('required' => false)),
      'eschool_creator' => new sfValidatorInteger(array('required' => false)),
      'creation_date'   => new sfValidatorInteger(array('required' => false)),
      'organization_id' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_eschool[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEschool';
  }

}
