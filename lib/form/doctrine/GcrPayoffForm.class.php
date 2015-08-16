<?php

/**
 * Payoff form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Justin England
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GcrPayoffForm extends BaseGcrPayoffForm
{
    public function configure()
    {
        $max_withdrawal = $this->getOption('max_withdrawal');
  	
  	$this->setWidgets
        (
            array
            (
                'id'                => new sfWidgetFormInputHidden(),
                'user_id'           => new sfWidgetFormInputHidden(),
                'user_eschool_id'   => new sfWidgetFormInputHidden(),
                'eschool_id'        => new sfWidgetFormInputHidden(),
                'amount'            => new sfWidgetFormInputText(),
            )
        );

        $this->widgetSchema->setLabels
	(
            array
            (
                 'amount'    => 'Withdrawal Amount: $',
            )
	);
        
        $this->setValidators
        (
            array
            (
                'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
                'eschool_id'        => new sfValidatorString(array('required' => true)),
                'user_eschool_id'   => new sfValidatorString(array('required' => true)),
                'user_id'           => new sfValidatorInteger(array('required' => true)),
                'amount'            => new sfValidatorNumber(array('required' => true), array('min' => 0, 'max' => $max_withdrawal)),
            )
        );
  }
}
