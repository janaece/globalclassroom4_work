<?php
/**
 * eschool settings form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Ron Stewart
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class GcrSettingsForm extends BaseForm
{
    public function configure()
    {
        $this->setWidgets
        (
            array
            (
                'short_name'                => new sfWidgetFormInputHidden(),
                'force_membership'          => new sfWidgetFormInputCheckbox(),
                'membership_includes_eclassroom' => new sfWidgetFormInputCheckbox(),
                'eclassroom_create_institution' => new sfWidgetFormInputCheckbox(),
                'is_internal'               => new sfWidgetFormInputCheckbox(),
                'eclassroom_min_balance'    => new sfWidgetFormInput(),
                'eschool_min_balance'       => new sfWidgetFormInput(),
                'membership_cost_month'     => new sfWidgetFormInput(),
                'membership_cost_year'      => new sfWidgetFormInput(),
                'membership_trial_length'   => new sfWidgetFormInput(),
                'membership_fee_percent'    => new sfWidgetFormInput(),
            )
        );
        $this->widgetSchema->setLabels
        (
            array
            (
                'force_membership'  => 'Require Membership:',
                'is_internal'       => 'Internally Owned:',
                'membership_cost_month' => 'Membership Fee per Month: $',
                'membership_cost_year' => 'Membership Fee per Year: $',
                'eclassroom_min_balance' => 'Minimum eClassroom User Balance: $',
                'membership_includes_eclassroom' => 'Membership Includes eClassroom:',
                'eclassroom_create_institution' => 'eClassrooms Allow User Creation:',
                'eschool_min_balance' => 'Minimum Platform Balance:',
                'membership_trial_length' => 'Length of Membership Trial (Days):',
                'membership_fee_percent' => 'GC Fee for memberships: %',
            )
        );
        $this->setValidators
        (
            array
            (
                'short_name'		=> new sfValidatorString(array('required' => true)),
                'force_membership'      => new sfValidatorBoolean(),
                'is_internal'           => new sfValidatorBoolean(),
                'eclassroom_min_balance' => new sfValidatorNumber(array('required' => false), array('min' => 0)),
                'membership_cost_month' => new sfValidatorNumber(array('required' => false), array('min' => 0)),
                'membership_cost_year' => new sfValidatorNumber(array('required' => false), array('min' => 0)),
                'membership_fee_percent' => new sfValidatorNumber(array('required' => false), array('min' => 0, 'max' => 100)),
                'membership_trial_length' => new sfValidatorNumber(array('required' => false), array('min' => 0)),
                'membership_includes_eclassroom' => new sfValidatorBoolean(),
                'eclassroom_create_institution' => new sfValidatorBoolean(),
                'eschool_min_balance' => new sfValidatorNumber(array('required' => false), array('min' => 0)),
            )
        );
    }
}
