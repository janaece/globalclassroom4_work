<?php

/**
 * GcrCommission form base class.
 *
 * @method GcrCommission getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrCommissionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'institution_id'  => new sfWidgetFormTextarea(),
      'eschool_id'      => new sfWidgetFormTextarea(),
      'commission_rate' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'institution_id'  => new sfValidatorString(array('required' => false)),
      'eschool_id'      => new sfValidatorString(array('required' => false)),
      'commission_rate' => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_commission[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrCommission';
  }

}
