<?php

/**
 * GcrUserStorageS3 filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrUserStorageS3FormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'app_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bucket_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'account_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'app_id'      => new sfValidatorPass(array('required' => false)),
      'bucket_name' => new sfValidatorPass(array('required' => false)),
      'account_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gcr_user_storage_s3_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrUserStorageS3';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'app_id'      => 'Text',
      'bucket_name' => 'Text',
      'account_id'  => 'Number',
    );
  }
}
