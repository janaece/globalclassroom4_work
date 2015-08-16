<?php

/**
 * GcrCurrentEschool form base class.
 *
 * @method GcrCurrentEschool getObject() Returns the current form's model object
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGcrCurrentEschoolForm extends GcrEschoolForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('gcr_current_eschool[%s]');
  }

  public function getModelName()
  {
    return 'GcrCurrentEschool';
  }

}
