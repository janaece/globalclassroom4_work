<?php

/**
 * GcrAddress filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrAddressFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'street1' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'street2' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'city'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zipcode' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'state'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'country' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'street1' => new sfValidatorPass(array('required' => false)),
      'street2' => new sfValidatorPass(array('required' => false)),
      'city'    => new sfValidatorPass(array('required' => false)),
      'zipcode' => new sfValidatorPass(array('required' => false)),
      'state'   => new sfValidatorPass(array('required' => false)),
      'country' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_address_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrAddress';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'street1' => 'Text',
      'street2' => 'Text',
      'city'    => 'Text',
      'zipcode' => 'Text',
      'state'   => 'Text',
      'country' => 'Text',
    );
  }
}
