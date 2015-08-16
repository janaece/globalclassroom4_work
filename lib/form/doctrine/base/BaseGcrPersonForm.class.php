<?php

/**
 * GcrPerson form base class.
 *
 * @method GcrPerson getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrPersonForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'first_name' => new sfWidgetFormTextarea(),
      'last_name'  => new sfWidgetFormTextarea(),
      'address'    => new sfWidgetFormInputText(),
      'phone1'     => new sfWidgetFormTextarea(),
      'phone2'     => new sfWidgetFormTextarea(),
      'email'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'first_name' => new sfValidatorString(array('required' => false)),
      'last_name'  => new sfValidatorString(array('required' => false)),
      'address'    => new sfValidatorInteger(),
      'phone1'     => new sfValidatorString(array('required' => false)),
      'phone2'     => new sfValidatorString(array('required' => false)),
      'email'      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_person[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPerson';
  }

}
