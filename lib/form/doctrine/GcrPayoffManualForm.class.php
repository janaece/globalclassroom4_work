<?php
/**
 * PayoffManual form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Justin England
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GcrPayoffManualForm extends BaseGcrPayoffForm
{
  public function configure()
  {
      $this->setWidgets
      (
          array
          (
              'id'             	=> new sfWidgetFormInputHidden(),
              'user_id'		=> new sfWidgetFormInputHidden(),
              'user_eschool_id'	=> new sfWidgetFormInputHidden(),
              'eschool_id'	=> new sfWidgetFormInputHidden(),
              'amount'         	=> new sfWidgetFormInputText(),
              'street1' 	=> new sfWidgetFormInputText(),
              'street2'		=> new sfWidgetFormInputText(),
              'city'   		=> new sfWidgetFormInputText(),
              'zipcode'		=> new sfWidgetFormInputText(),
              'state'   	=> new sfWidgetFormInputText(),
              'country'		=> new sfWidgetFormInputText(),
              'reference_id'	=> new sfWidgetFormInputText(),
              'description'	=> new sfWidgetFormTextArea(),
              'type'		=> new sfWidgetFormSelect(array('choices' => array('check' => 'Check', 'paypal' => 'PayPal'))),
              'transtime'      	=> new sfWidgetFormDate(array('format' => '%month%/%day%/%year%')),
    ));
    
    $this->widgetSchema->setLabels
    (
        array
        (
            'amount'         	=> 'Payoff Amount:',
            'street1' 		=> 'Street Address:',
            'street2'		=> 'Street Address 2:',
            'city'   		=> 'City',
            'zipcode'		=> 'Zip/Postal Code:',
            'state'   		=> 'State',
            'country'		=> 'Country',
            'reference_id'	=> 'Check No./PayPal TXN ID:',
            'description'	=> 'Comments:',
            'type'		=> 'Method of Payment:',
            'transtime'		=> 'Date of Payment:'
        )
    );

    $this->setValidators(array(
      'street1' 		=> new sfValidatorString(array('required' => false)),
      'street2'			=> new sfValidatorString(array('required' => false)),
      'city'   			=> new sfValidatorString(array('required' => false)),
      'zipcode'			=> new sfValidatorString(array('required' => false)),
      'state'   		=> new sfValidatorString(array('required' => false)),
      'country'			=> new sfValidatorString(array('required' => false)),
      'reference_id'            => new sfValidatorString(array('required' => true)),
      'description'		=> new sfValidatorString(array('required' => false)),
      'id'              	=> new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'eschool_id'              => new sfValidatorString(array('required' => true)),
      'user_eschool_id'     	=> new sfValidatorString(array('required' => true)),
      'user_id'                 => new sfValidatorInteger(array('required' => true)),
      'amount'                  => new sfValidatorNumber(array('required' => true), array('min' => 0)),
      'type'			=> new sfValidatorString(array('required' => true)),
      'transtime'		=> new sfValidatorString(array('required' => true)),
    ));
  }
}
