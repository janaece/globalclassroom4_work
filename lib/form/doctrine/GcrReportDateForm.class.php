<?php

/**
 * eschool form.
 *
 * @package    globalclassroom3
 * @subpackage form
 * @author     John Battaline
 */

class GcrReportDateForm extends sfForm {
	public function configure()
	{
		for ($i = date('U')-5184000; $i < date('U'); $i+=86400){
			$begin_days[] = date('M j, Y',$i);}
		unset($i);
		for ($i = date('U')-5097600; $i < date('U')+86400; $i+=86400){
			$end_days[] = date('M j, Y',$i);}

		
	  	$this->setWidgets(array(
	  		'begin_date'			=> new sfWidgetFormSelect(array('choices' => $begin_days, 'default' => '')),
	  		'end_date'				=> new sfWidgetFormSelect(array('choices' => $end_days)),
	  	));
	  	
	  	$this->widgetSchema->setDefaults(array(
			'begin_date'		=> $this->getOption('from_date'),
			'end_date'			=> $this->getOption('to_date'),	
		));
	}
}