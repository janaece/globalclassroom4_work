<?php

/**
 * GcrEschoolMonthlyData form base class.
 *
 * @method GcrEschoolMonthlyData getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrEschoolMonthlyDataForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'eschool_id'         => new sfWidgetFormTextarea(),
      'month_value'        => new sfWidgetFormInputText(),
      'year_value'         => new sfWidgetFormInputText(),
      'eschool_balance'    => new sfWidgetFormInputText(),
      'eclassroom_balance' => new sfWidgetFormInputText(),
      'gross'              => new sfWidgetFormInputText(),
      'gc_fee'             => new sfWidgetFormInputText(),
      'seller_fee'         => new sfWidgetFormInputText(),
      'num_users'          => new sfWidgetFormInputText(),
      'num_courses'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'eschool_id'         => new sfValidatorString(array('required' => false)),
      'month_value'        => new sfValidatorInteger(array('required' => false)),
      'year_value'         => new sfValidatorInteger(array('required' => false)),
      'eschool_balance'    => new sfValidatorNumber(array('required' => false)),
      'eclassroom_balance' => new sfValidatorNumber(array('required' => false)),
      'gross'              => new sfValidatorNumber(array('required' => false)),
      'gc_fee'             => new sfValidatorNumber(array('required' => false)),
      'seller_fee'         => new sfValidatorNumber(array('required' => false)),
      'num_users'          => new sfValidatorInteger(array('required' => false)),
      'num_courses'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_eschool_monthly_data[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEschoolMonthlyData';
  }

}
