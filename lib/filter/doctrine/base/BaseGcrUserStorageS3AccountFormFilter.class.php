<?php

/**
 * GcrUserStorageS3Account filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrUserStorageS3AccountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'access_key_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'secret_access_key' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'access_key_id'     => new sfValidatorPass(array('required' => false)),
      'secret_access_key' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_user_storage_s3_account_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrUserStorageS3Account';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'access_key_id'     => 'Text',
      'secret_access_key' => 'Text',
    );
  }
}
