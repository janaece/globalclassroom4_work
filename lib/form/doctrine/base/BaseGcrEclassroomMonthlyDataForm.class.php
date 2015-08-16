<?php

/**
 * GcrEclassroomMonthlyData form base class.
 *
 * @method GcrEclassroomMonthlyData getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrEclassroomMonthlyDataForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'month_value'        => new sfWidgetFormInputText(),
      'year_value'         => new sfWidgetFormInputText(),
      'eclassroom_id'      => new sfWidgetFormInputText(),
      'eclassroom_balance' => new sfWidgetFormInputText(),
      'gross'              => new sfWidgetFormInputText(),
      'gc_fee'             => new sfWidgetFormInputText(),
      'owner_fee'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'month_value'        => new sfValidatorInteger(array('required' => false)),
      'year_value'         => new sfValidatorInteger(array('required' => false)),
      'eclassroom_id'      => new sfValidatorInteger(array('required' => false)),
      'eclassroom_balance' => new sfValidatorNumber(array('required' => false)),
      'gross'              => new sfValidatorNumber(array('required' => false)),
      'gc_fee'             => new sfValidatorNumber(array('required' => false)),
      'owner_fee'          => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_eclassroom_monthly_data[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEclassroomMonthlyData';
  }

}
