<?php

/**
 * GcrEclassroom form base class.
 *
 * @method GcrEclassroom getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrEclassroomForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'user_institution_id'  => new sfWidgetFormTextarea(),
      'eschool_id'           => new sfWidgetFormTextarea(),
      'mhr_institution_name' => new sfWidgetFormTextarea(),
      'suspended'            => new sfWidgetFormInputCheckbox(),
      'user_id'              => new sfWidgetFormInputText(),
      'category_id'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_institution_id'  => new sfValidatorString(array('required' => false)),
      'eschool_id'           => new sfValidatorString(array('required' => false)),
      'mhr_institution_name' => new sfValidatorString(array('required' => false)),
      'suspended'            => new sfValidatorBoolean(array('required' => false)),
      'user_id'              => new sfValidatorInteger(array('required' => false)),
      'category_id'          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_eclassroom[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEclassroom';
  }

}
