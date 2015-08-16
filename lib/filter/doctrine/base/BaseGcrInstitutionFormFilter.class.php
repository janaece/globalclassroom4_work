<?php

/**
 * GcrInstitution filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrInstitutionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'full_name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'external_url'       => new sfWidgetFormFilterInput(),
      'suspended'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'logo'               => new sfWidgetFormFilterInput(),
      'contact1'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'contact2'           => new sfWidgetFormFilterInput(),
      'address'            => new sfWidgetFormFilterInput(),
      'visible'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'short_name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_new'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_internal'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'admin_password'     => new sfWidgetFormFilterInput(),
      'creator_id'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'creation_date'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'default_eschool_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'institution_type'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password_salt'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'full_name'          => new sfValidatorPass(array('required' => false)),
      'external_url'       => new sfValidatorPass(array('required' => false)),
      'suspended'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'logo'               => new sfValidatorPass(array('required' => false)),
      'contact1'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'contact2'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'address'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'visible'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'short_name'         => new sfValidatorPass(array('required' => false)),
      'is_new'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_internal'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'admin_password'     => new sfValidatorPass(array('required' => false)),
      'creator_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'creation_date'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'default_eschool_id' => new sfValidatorPass(array('required' => false)),
      'institution_type'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'password_salt'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_institution_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrInstitution';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'full_name'          => 'Text',
      'external_url'       => 'Text',
      'suspended'          => 'Boolean',
      'logo'               => 'Text',
      'contact1'           => 'Number',
      'contact2'           => 'Number',
      'address'            => 'Number',
      'visible'            => 'Boolean',
      'short_name'         => 'Text',
      'is_new'             => 'Boolean',
      'is_internal'        => 'Boolean',
      'admin_password'     => 'Text',
      'creator_id'         => 'Number',
      'creation_date'      => 'Number',
      'default_eschool_id' => 'Text',
      'institution_type'   => 'Number',
      'password_salt'      => 'Text',
    );
  }
}
