<?php
/**
 * eschool settings form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Ron Stewart
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class GcrEschoolSettingsForm extends BaseForm
{
    public function configure()
    {
        global $CFG;
        $this->setWidgets
        (
            array
            (
                'eschool_short_name'		=> new sfWidgetFormInputHidden(),
                'course_gc_fee'             => new sfWidgetFormInput(),
                'course_owner_fee'          => new sfWidgetFormInput(),
                'classroom_trial_length'	=> new sfWidgetFormInput(),
                'classroom_cost_month'		=> new sfWidgetFormInput(),
                'classroom_cost_year'		=> new sfWidgetFormInput(),
                'classroom_gc_fee'          => new sfWidgetFormInput(),
                'is_visible'                => new sfWidgetFormInputCheckbox(),
                'is_public'                 => new sfWidgetFormInputCheckbox(),
                'gc_auto_creates_users'        => new sfWidgetFormInputCheckbox(),
            )
        );
        $this->widgetSchema->setLabels
        (
            array
            (
                'course_gc_fee'             => 'GC Fee for Course Sales %',
                'course_owner_fee'          => 'Owner Fee for Course Sales %',
                'classroom_trial_length'	=> 'Trial Length in Days (days)',
                'classroom_cost_month'		=> 'Monthly Price $',
                'classroom_cost_year'		=> 'Yearly Price $',
                'classroom_gc_fee'          => 'GC Fee for Subscriptions %',
                'is_public'                 => 'Can be Added as a Catalog?',
                'is_visible'                => 'Is Visible?',
                'gc_auto_creates_users'        => 'User\'s Auto Created?',
            )
        );
        $this->setValidators
        (
            array
            (
                'eschool_short_name'		=> new sfValidatorString(array('required' => true)),
                'is_public'                 => new sfValidatorBoolean(),
                'is_visible'                => new sfValidatorBoolean(),
                'gc_auto_creates_users'        => new sfValidatorBoolean(),
                'course_gc_fee'             => new sfValidatorNumber(array('required' => false), array('min' => 0, 'max' => 100)),
                'course_owner_fee'          => new sfValidatorNumber(array('required' => false), array('min' => 0, 'max' => 100)),
                'classroom_trial_length'	=> new sfValidatorInteger(array('required' => false), array('min' => 0)),
                'classroom_cost_month'		=> new sfValidatorNumber(array('required' => false), array('min' => 0)),
                'classroom_cost_year'		=> new sfValidatorNumber(array('required' => false), array('min' => 0)),
                'classroom_gc_fee'          => new sfValidatorNumber(array('required' => false), array('min' => 0, 'max' => 100)),
            )
        );
    }
}
