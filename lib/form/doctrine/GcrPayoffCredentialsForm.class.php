<?php

/**
 * PayoffCredentials form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Justin England
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GcrPayoffCredentialsForm extends BaseGcrPayoffCredentialsForm
{
    public function configure()
    {
        $this->setWidgets
        (
            array
            (
                'id'                    => new sfWidgetFormInputHidden(),
                'user_business_name'    => new sfWidgetFormInputText(),
                'user_first_name'       => new sfWidgetFormInputText(),
                'user_last_name'        => new sfWidgetFormInputText(),
                'user_paypal_email'     => new sfWidgetFormInputText(),
                'user_tin'              => new sfWidgetFormInputText(),
                'user_eschool_id'       => new sfWidgetFormInputHidden(),
                'user_id'               => new sfWidgetFormInputHidden(),
                'verify_status'         => new sfWidgetFormInputHidden(),
                'verify_hash'           => new sfWidgetFormInputHidden(),
            )
        );

        $this->widgetSchema->setLabels
	(
            array
            (
                'user_business_name' => 'Business Name:',
                'user_first_name'    => 'First Name:',
                'user_last_name'     => 'Last Name:',
                'user_paypal_email'  => 'Paypal Account Email:',
                'user_tin'           => 'SSN/TIN:',
            )
	);
        
        $this->setValidators
        (
            array
            (
              'id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
              'user_business_name' => new sfValidatorString(array('required' => false)),
              'user_first_name'    => new sfValidatorString(array('required' => true)),
              'user_last_name'     => new sfValidatorString(array('required' => true)),
              'user_paypal_email'  => new sfValidatorEmail(array('required' => true)),
              'user_tin'           => new sfValidatorString(array('required' => true)),
              'user_eschool_id'    => new sfValidatorString(array('required' => true)),
              'user_id'            => new sfValidatorInteger(array('required' => true)),
              'verify_status'	   => new sfValidatorString(array('required' => true)),
              'verify_hash'        => new sfValidatorString(array('required' => true)),
            )
        );
    }
}
