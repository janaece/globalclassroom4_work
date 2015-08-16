<?php

/**
 * GcrAdminAccess filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrAdminAccessFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'userid'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'userid'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gcr_admin_access_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GcrAdminAccess';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'userid'   => 'Number',
      'username' => 'Text',
    );
  }
}
