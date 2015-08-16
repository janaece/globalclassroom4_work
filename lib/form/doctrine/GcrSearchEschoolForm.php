<?php
/**
 * eschool search form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Ron Stewart
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class GcrSearchEschoolForm extends BaseGcrEschoolForm
{
  public function configure()
  {
  	$this->setWidgets(array('eschoolPattern' => new sfWidgetFormInput()));
  	$this->setValidators(array('eschoolPattern' => new sfValidatorString(array('min_length' => 3, 'max_length' => 100))));
  	$this->widgetSchema->setLabels(array('eschoolPattern' => 'Search for Platforms:'));
		     
  }
}