<?php

/**
 * GcrUserStorageS3Account form base class.
 *
 * @method GcrUserStorageS3Account getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrUserStorageS3AccountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'access_key_id'     => new sfWidgetFormTextarea(),
      'secret_access_key' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'access_key_id'     => new sfValidatorString(array('required' => false)),
      'secret_access_key' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_user_storage_s3_account[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrUserStorageS3Account';
  }

}
