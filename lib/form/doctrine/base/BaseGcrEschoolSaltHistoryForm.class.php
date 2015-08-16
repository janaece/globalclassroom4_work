<?php

/**
 * GcrEschoolSaltHistory form base class.
 *
 * @method GcrEschoolSaltHistory getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrEschoolSaltHistoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'eschoolid' => new sfWidgetFormInputText(),
      'salt'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'eschoolid' => new sfValidatorInteger(array('required' => false)),
      'salt'      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_eschool_salt_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEschoolSaltHistory';
  }

}
