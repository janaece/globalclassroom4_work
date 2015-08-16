<?php

/**
 * GcrEclassroom filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrEclassroomFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_institution_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'eschool_id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mhr_institution_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'suspended'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_id'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'category_id'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'user_institution_id'  => new sfValidatorPass(array('required' => false)),
      'eschool_id'           => new sfValidatorPass(array('required' => false)),
      'mhr_institution_name' => new sfValidatorPass(array('required' => false)),
      'suspended'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_eclassroom_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrEclassroom';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'user_institution_id'  => 'Text',
      'eschool_id'           => 'Text',
      'mhr_institution_name' => 'Text',
      'suspended'            => 'Boolean',
      'user_id'              => 'Number',
      'category_id'          => 'Number',
    );
  }
}
