<?php

/**
 * GcrPurchaseItem form base class.
 *
 * @method GcrPurchaseItem getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrPurchaseItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'short_name'  => new sfWidgetFormInputHidden(),
      'description' => new sfWidgetFormTextarea(),
      'amount'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'short_name'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('short_name')), 'empty_value' => $this->getObject()->get('short_name'), 'required' => false)),
      'description' => new sfValidatorString(array('required' => false)),
      'amount'      => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_purchase_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPurchaseItem';
  }

}
