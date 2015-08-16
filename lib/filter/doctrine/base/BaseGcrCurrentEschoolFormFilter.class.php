<?php

/**
 * GcrCurrentEschool filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrCurrentEschoolFormFilter extends GcrEschoolFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('gcr_current_eschool_filters[%s]');
  }

  public function getModelName()
  {
    return 'GcrCurrentEschool';
  }
}
