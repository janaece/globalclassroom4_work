<?php

/**
 * GcrAddress form base class.
 *
 * @method GcrAddress getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrAddressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'street1' => new sfWidgetFormTextarea(),
      'street2' => new sfWidgetFormTextarea(),
      'city'    => new sfWidgetFormTextarea(),
      'zipcode' => new sfWidgetFormTextarea(),
      'state'   => new sfWidgetFormTextarea(),
      'country' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'street1' => new sfValidatorString(array('required' => false)),
      'street2' => new sfValidatorString(array('required' => false)),
      'city'    => new sfValidatorString(array('required' => false)),
      'zipcode' => new sfValidatorString(array('required' => false)),
      'state'   => new sfValidatorString(array('required' => false)),
      'country' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrAddress';
  }

}
