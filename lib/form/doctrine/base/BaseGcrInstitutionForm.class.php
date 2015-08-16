<?php

/**
 * GcrInstitution form base class.
 *
 * @method GcrInstitution getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrInstitutionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'full_name'          => new sfWidgetFormTextarea(),
      'external_url'       => new sfWidgetFormTextarea(),
      'suspended'          => new sfWidgetFormInputCheckbox(),
      'logo'               => new sfWidgetFormTextarea(),
      'contact1'           => new sfWidgetFormInputText(),
      'contact2'           => new sfWidgetFormInputText(),
      'address'            => new sfWidgetFormInputText(),
      'visible'            => new sfWidgetFormInputCheckbox(),
      'short_name'         => new sfWidgetFormTextarea(),
      'is_new'             => new sfWidgetFormInputCheckbox(),
      'is_internal'        => new sfWidgetFormInputCheckbox(),
      'admin_password'     => new sfWidgetFormTextarea(),
      'creator_id'         => new sfWidgetFormInputText(),
      'creation_date'      => new sfWidgetFormInputText(),
      'default_eschool_id' => new sfWidgetFormTextarea(),
      'institution_type'   => new sfWidgetFormInputText(),
      'password_salt'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'full_name'          => new sfValidatorString(array('required' => false)),
      'external_url'       => new sfValidatorString(array('required' => false)),
      'suspended'          => new sfValidatorBoolean(array('required' => false)),
      'logo'               => new sfValidatorString(array('required' => false)),
      'contact1'           => new sfValidatorInteger(array('required' => false)),
      'contact2'           => new sfValidatorInteger(array('required' => false)),
      'address'            => new sfValidatorInteger(array('required' => false)),
      'visible'            => new sfValidatorBoolean(array('required' => false)),
      'short_name'         => new sfValidatorString(array('required' => false)),
      'is_new'             => new sfValidatorBoolean(array('required' => false)),
      'is_internal'        => new sfValidatorBoolean(array('required' => false)),
      'admin_password'     => new sfValidatorString(array('required' => false)),
      'creator_id'         => new sfValidatorInteger(array('required' => false)),
      'creation_date'      => new sfValidatorInteger(array('required' => false)),
      'default_eschool_id' => new sfValidatorString(array('required' => false)),
      'institution_type'   => new sfValidatorInteger(array('required' => false)),
      'password_salt'      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_institution[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrInstitution';
  }

}
