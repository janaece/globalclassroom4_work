<?php

/**
 * GcrPayoffCredentials filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrPayoffCredentialsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_business_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_first_name'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_last_name'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_paypal_email'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_tin'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_eschool_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'verify_status'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'verify_hash'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_business_name' => new sfValidatorPass(array('required' => false)),
      'user_first_name'    => new sfValidatorPass(array('required' => false)),
      'user_last_name'     => new sfValidatorPass(array('required' => false)),
      'user_paypal_email'  => new sfValidatorPass(array('required' => false)),
      'user_tin'           => new sfValidatorPass(array('required' => false)),
      'user_eschool_id'    => new sfValidatorPass(array('required' => false)),
      'user_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'verify_status'      => new sfValidatorPass(array('required' => false)),
      'verify_hash'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_payoff_credentials_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrPayoffCredentials';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'user_business_name' => 'Text',
      'user_first_name'    => 'Text',
      'user_last_name'     => 'Text',
      'user_paypal_email'  => 'Text',
      'user_tin'           => 'Text',
      'user_eschool_id'    => 'Text',
      'user_id'            => 'Number',
      'verify_status'      => 'Text',
      'verify_hash'        => 'Text',
    );
  }
}
