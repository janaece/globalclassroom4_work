<?php

/**
 * Institution form.
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GcrInstitutionForm extends BaseGcrInstitutionForm
{
    public function configure()
    {
        global $CFG;
        // Get a list of eschool types. Only show public templates if user is not an admin.
        $typeList = GcrInstitutionTypeTable::getTypes(!$CFG->current_app->hasPrivilege('GCUser'));
        foreach ($typeList as $type)
        {
            $existingTypes[$type->id] = $type->name;
        }

        $this->setWidgets
        (
            array
            (
                'id'			=> new sfWidgetFormInputHidden(),
                'full_name'		=> new sfWidgetFormInput(),
                'admin_username'        => new sfWidgetFormInput(),
                'admin_password_user'   => new sfWidgetFormInputPassword(),
                'admin_password_verify' => new sfWidgetFormInputPassword(),
                'aid'                   => new sfWidgetFormInputHidden(),
                'external_url'          => new sfWidgetFormInputHidden(),
                'suspended'		=> new sfWidgetFormInputHidden(),
                'logo'			=> new sfWidgetFormInputFile(),
                'contact1'		=> new sfWidgetFormInputHidden(),
                'contact2'		=> new sfWidgetFormInputHidden(),
                'address'		=> new sfWidgetFormInputHidden(),
                'institution_type'      => new sfWidgetFormSelect(array('choices' => $existingTypes)),
                'visible'		=> new sfWidgetFormInputHidden(),
                'short_name'            => new sfWidgetFormInput(array(), array('onchange' => 'updateCoursesSuggestion(this)')),
                'default_eschool_id'    => new sfWidgetFormInput(),
                'admin_password'        => new sfWidgetFormInputHidden(),
                'verify'                => new sfWidgetFormInputHidden(),
                'creator_id'            => new sfWidgetFormInputHidden(),
                'creation_date'         => new sfWidgetFormInputHidden(),
                'first_name_2'          => new sfWidgetFormInput(),
                'last_name_2'           => new sfWidgetFormInput(),
                'phone1_2'		=> new sfWidgetFormInput(),
                'phone2_2'		=> new sfWidgetFormInput(),
                'email_2'		=> new sfWidgetFormInput(),
            )
        );

        $this->widgetSchema->setLabels
        (
            array
            (
                'institution_type' => 'Platform Type:',
                'admin_username' => 'Administrator Username:',
                'admin_password_user' => 'Administrator Password:',
                'admin_password_verify' => 'Password Confirmation:',
                'logo'    => 'Platform Banner:',
                'full_name' => 'Platform Full Name:',
                'short_name'    => 'Short Name Home:',
                'default_eschool_id' => 'Short Name Courses:',
                'first_name_2'  => 'First Name:',
                'last_name_2'  => 'Last Name:',
                'phone1_2'  => 'Phone:',
                'phone2_2'  => 'Phone (cell):',
                'email_2'   => 'Email:',
                'state'    => 'State/Province:',
                'zipcode' => 'Zipcode:',
            )
        );

        $this->setValidators
        (
            array
            (
              'id'        	=> new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
              'full_name'       => new sfValidatorString(array('max_length' => 60)),
              'admin_username'  => new sfValidatorString(array('max_length' => 40)),
              'admin_password_user' => new sfValidatorString(array('max_length' => 40)),
              'admin_password_verify' => new sfValidatorString(array('max_length' => 40)),
              'external_url'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
              'suspended'       => new sfValidatorBoolean(),
              'logo'            => new sfValidatorFile(array('required' => false, 'path' => gcr::moodledataDir, 'mime_types' => 'web_images')),
              'contact1'        => new sfValidatorInteger(),
              'aid'             => new sfValidatorInteger(),
              'contact2'        => new sfValidatorInteger(array('required' => false)),
              'address'         => new sfValidatorInteger(array('required' => false)),
              'institution_type' => new sfValidatorInteger(array('min' => 0), array('min' => 'Stratus Type Is Invalid')),
              'visible'         => new sfValidatorBoolean(),
              'short_name'      => new sfValidatorString(array('max_length' => 32)),
              'default_eschool_id' => new sfValidatorString(array('max_length' => 32)),
              'admin_password'  => new sfValidatorString(array('required' => false, 'max_length' => 64)),
              'verify'  	=> new sfValidatorString(array('max_length' => 255)),
              'creator_id'		=> new sfValidatorInteger(),
              'creation_date'	=> new sfValidatorInteger(),
              'first_name_2' => new sfValidatorString(array('max_length' => 50)),
              'last_name_2'  => new sfValidatorString(array('max_length' => 50)),
              'phone1_2'     => new sfValidatorString(array('max_length' => 50)),
              'phone2_2'     => new sfValidatorString(array('max_length' => 50, 'required' => false)),
              'email_2'      => new sfValidatorEmail(array('max_length' => 100)),
            )
        );
    }
}
