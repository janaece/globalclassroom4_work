<?php

/**
 * eschool admin form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Ron Stewart
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class GcrEschoolAdminForm extends BaseGcrEschoolForm
{
  public function configure()
  {
  	$trialList = Doctrine_Core::getTable('GcrTrial')->findAll();
  	foreach ($trialList as $trial)
	{
		
		if (!$trial->end_date || $trial->end_date > time())
		{
			$institution = Doctrine::getTable('GcrInstitution')->find($trial->organization_id);
			$existingTrials[$trial->id] = $institution->short_name . ", started: " . date('d-m-Y', $trial->start_date);
		}
	}
	
	asort($existingTrials);
	
	$this->setWidgets
  	(
  		array
  		(
	      
	      'trials'    		=> new sfWidgetFormSelect(array('choices' => $existingTrials)),
  		)
  	);
  	$this->setValidators
    (
    	array
    	(
	      'trials'        	=> new sfValidatorInteger(),
	    )
	);
  }
}