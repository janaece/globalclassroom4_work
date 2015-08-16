<?php

/**
 * GcrWantsUrl filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrWantsUrlFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'time_created'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'wants_url'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'redirect_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'app_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'time_created'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'wants_url'     => new sfValidatorPass(array('required' => false)),
      'redirect_type' => new sfValidatorPass(array('required' => false)),
      'app_id'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_wants_url_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrWantsUrl';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'time_created'  => 'Number',
      'wants_url'     => 'Text',
      'redirect_type' => 'Text',
      'app_id'        => 'Text',
    );
  }
}
