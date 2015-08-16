<?php

/**
 * GcrInstitutionSaltHistory filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrInstitutionSaltHistoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'institutionid' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'salt'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'institutionid' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'salt'          => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_institution_salt_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrInstitutionSaltHistory';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'institutionid' => 'Number',
      'salt'          => 'Text',
    );
  }
}
