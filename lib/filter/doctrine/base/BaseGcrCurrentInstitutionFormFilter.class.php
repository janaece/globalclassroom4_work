<?php

/**
 * GcrCurrentInstitution filter form base class.
 *
 * @package    globalclassroom
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGcrCurrentInstitutionFormFilter extends GcrInstitutionFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('gcr_current_institution_filters[%s]');
  }

  public function getModelName()
  {
    return 'GcrCurrentInstitution';
  }
}
