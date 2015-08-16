<?php

/**
 * GcrEschool filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrEschoolFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'full_name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'external_url'    => new sfWidgetFormFilterInput(),
      'suspended'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'logo'            => new sfWidgetFormFilterInput(),
      'can_sell'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'contact1'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'contact2'        => new sfWidgetFormFilterInput(),
      'address'         => new sfWidgetFormFilterInput(),
      'eschool_type'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'visible'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'short_name'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password_salt'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'reset_keys'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_public'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'admin_password'  => new sfWidgetFormFilterInput(),
      'eschool_creator' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'creation_date'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'organization_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'full_name'       => new sfValidatorPass(array('required' => false)),
      'external_url'    => new sfValidatorPass(array('required' => false)),
      'suspended'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'logo'            => new sfValidatorPass(array('required' => false)),
      'can_sell'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'contact1'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'contact2'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'address'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'eschool_type'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'visible'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'short_name'      => new sfValidatorPass(array('required' => false)),
      'password_salt'   => new sfValidatorPass(array('required' => false)),
      'reset_keys'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_public'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'admin_password'  => new sfValidatorPass(array('required' => false)),
      'eschool_creator' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'creation_date'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'organization_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_eschool_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEschool';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'full_name'       => 'Text',
      'external_url'    => 'Text',
      'suspended'       => 'Boolean',
      'logo'            => 'Text',
      'can_sell'        => 'Boolean',
      'contact1'        => 'Number',
      'contact2'        => 'Number',
      'address'         => 'Number',
      'eschool_type'    => 'Number',
      'visible'         => 'Boolean',
      'short_name'      => 'Text',
      'password_salt'   => 'Text',
      'reset_keys'      => 'Boolean',
      'is_public'       => 'Boolean',
      'admin_password'  => 'Text',
      'eschool_creator' => 'Number',
      'creation_date'   => 'Number',
      'organization_id' => 'Number',
    );
  }
}
