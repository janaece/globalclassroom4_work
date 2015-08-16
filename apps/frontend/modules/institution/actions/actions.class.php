<?php

/**
 * institution actions.
 *
 * @package    globalclassroom
 * @subpackage institution
 * @author     Ron Stewart
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class institutionActions extends sfActions
{
    public function executeVerify(sfWebRequest $request)
    {
        global $CFG;
        $this->setTrialApplicationToVerify($request->getParameter('aid'));
        $hash = $request->getParameter('verify');

        if ($CFG->current_app->getCurrentUser()->getRoleManager()->hasPrivilege('GCUser'))
        {
            // bypass verification for site admins
            $hash = $this->application->getVerifyHash();
        }

        if ($hash == $this->application->getVerifyHash())
        {
            $this->institution_form = new GcrInstitutionForm();
            $contact = $this->application->getContactObject();
            $this->institution_form->setDefaults(array(
                'first_name_2' => $contact->getFirstName(),
                'first_name_2' => $contact->getFirstName(),
                'last_name_2' => $contact->getLastName(),
                'email_2' => $contact->getEmail(),
                'phone1_2' => $contact->getPhone1(),
                'phone2_2' => $contact->getPhone2(),
                'aid' => $this->application->getId(),
                'verify' => $hash,
            ));
            $this->setTemplate('newInstitutionForm');
        }
        else
        {
            global $CFG;
            $CFG->current_app->gcError('Incorrect Verify Hash ' . $hash . ' for application id ' .
                    $request->getParameter('aid'));
        }
    }
    public function executeEclassrooms (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $CFG->current_app->requireMahara();
        if (!$CFG->current_app->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError('Unauthorized attempted access to eClassrooms Administration', 'gcpageaccessdenied');
        }
        $this->institution = $CFG->current_app;
        $this->eclassroom_table = new GcrEclassroomManagementTable($this->institution);
        $this->getResponse()->setTitle('eClassroom Administration');
    }
    public function executeEclassroom (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $CFG->current_app->requireMahara();
        if (!$CFG->current_app->hasPrivilege('EclassroomUser'))
        {
            $CFG->current_app->gcError('Unauthorized attempted access to eClassroom Administration', 'gcpageaccessdenied');
        }
        $this->current_user = $CFG->current_app->getCurrentUser();
        $this->user = false;
        $user_id = $request->getParameter('id');
        if ($user_id)
        {
            if ($CFG->current_app->hasPrivilege('EschoolAdmin'))
            {
                $this->user = $CFG->current_app->getUserById($user_id);
            }  
        }
        if (!$this->user)
        {
            $this->user = $this->current_user;
        }
        $this->eclassroom_table = new GcrEclassroomUserManagementTable($this->user);
        $this->getResponse()->setTitle('eClassroom - ' . $this->user->getFullnameString());
    }
    public function executeCreate(sfWebRequest $request)
    {
        global $CFG;
        
        // GC_NOTE 2: At this time, we are not allowing anyone outside of GC to create new platforms
        // If this should change, remove this security check. All code to support user creation is in place
        if (!$CFG->current_app->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError('Non GCUser attempted access to institution/create', 'gcpageaccessdenied');
        }
        // END GC_NOTE 2
        $form = $request->getPostParameters();
        $this->setTrialApplicationToVerify($form['aid']);
        if (!$form['verify'] == $this->application->getVerifyHash())
        {
            global $CFG;
            $CFG->current_app->gcError('Incorrect Verify Hash ' . $form['verify'] . ' for application id ' .
                    $form['aid']);
        }
        $this->institution_form = new GcrInstitutionForm();
        if (!$this->formErrors)
        {
            $this->formErrors = array();
        }

        // validate form values
        $this->validateInstitutionShortName($form['short_name']);
        if ($form['short_name'] == $form['default_eschool_id'])
        {
            $this->formErrors['short_nameUnique'] = 'Short Name Home and Short Name Courses cannot be identical';
        }
        $this->validateEschoolShortName($form['default_eschool_id']);
        $this->validateInstitutionType($form['institution_type']);

        // make a person object to insert in to the person table for contact 2
        $person2Object = new GcrPerson();
        $person2Object->setFirstName($form['first_name_2']);
        $person2Object->setLastName($form['last_name_2']);
        $person2Object->setAddress($this->application->getAddress());
        $person2Object->setPhone1($form['phone1_2']);
        $person2Object->setPhone2($form['phone2_2']);
        $person2Object->setEmail($form['email_2']);

        // try to add the contact 2 to the person table
        $person2Object->save();

               // make an array of values to validate as the institution form
        $institutionFields = array( 'id' => '',
                                    'full_name' => $form['full_name'],
                                    'short_name' => strtolower($form['short_name']),
                                    'default_eschool_id' => strtolower($form['default_eschool_id']),
                                    'external_url' => $form['external_url'],
                                    'suspended' => '',
                                    'contact1' => $this->application->getContact(),
                                    'contact2' => $person2Object->getId(),
                                    'address' => $this->application->getAddress(),
                                    'institution_type' => $form['institution_type'],
                                    'creator_id' => -1,
                                    'admin_password' => '',
                                    'verify' => $form['verify'],
                                    'creation_date' => time(),
                                    'visible' => '1',
                                    'first_name_2' => $form['first_name_2'],
                                    'last_name_2' => $form['last_name_2'],
                                    'phone1_2' => $form['phone1_2'],
                                    'phone2_2' => $form['phone2_2'],
                                    'email_2' => $form['email_2'],
                                    'aid' => $form['aid'],
                                    'admin_password_user' => $form['admin_password_user'],
                                    'admin_password_verify' => $form['admin_password_verify'],
                                    'admin_username' => $form['admin_username'],
                                    '_csrf_token' => $form['_csrf_token']);

        // try to add the eschool data to the eschool table
        if ($trial_application_record = $this->processForm($institutionFields,
                $this->institution_form, $request->getFiles()))
        {
            $this->institution = $trial_application_record;
        }
        else
        {
            $this->formErrors['institutionRecord'] = 'Some Field(s) Have Missing or Incorrect Data';
        }
        if (!$this->formErrors['admin_username'] = GcrInstitutionTable::verifyUsername($form['admin_username']))
        {
            unset($this->formErrors['admin_username']);
        }
        if ($form['admin_password_user'] != $form['admin_password_verify'])
        {
            $this->formErrors['admin_password_user'] = 'Password entries did not match, please try again.';
        }
        else
        {
            if (!$this->formErrors['admin_password_user'] = GcrInstitutionTable::verifyPassword($form['admin_password_user']))
            {
                unset($this->formErrors['admin_password_user']);
            }
        }

        // If the institution record was saved, we send an email to verify the user before creating the new institution
        if (count($this->formErrors) == 0)
        {
            $this->institution->create(array('username' => $form['admin_username'], 
                'password' => $form['admin_password_user']));

            
            if ($CFG->current_app->hasPrivilege('GCUser'))
            {
                $current_user = $CFG->current_app->getCurrentUser();
                if ($form['admin_username'] == $current_user->getObject()->username)
                {
                    $owner_person = $this->institution->getPersonObject();
                    if ($owner_person->getEmail() == $current_user->getObject()->email)
                    {
                        // give a logged in site admin the same privileges on the new mahara
                        if ($mhr_user_obj = $this->institution->selectFromMhrTable('usr', 'username', $form['admin_username'], true))
                        {
                            $admin_user = new GcrMhrUser($mhr_user_obj, $this->institution);
                            $admin_user->setAdminRole();
                        }
                    }
                }
            }

            // make a trial object to save this new eschool trial
            $eschool = $this->institution->getDefaultEschool();
            $this->institution->createNewTrial();

            // send emails to new eschool owner and to us
            $this->emailNewEschoolOwner();
            $this->emailNewEschoolGC($owner_credentials);
            $this->application->delete();

            if ((!$CFG->current_app->hasPrivilege('GCUser')) || isset($owner_person))
            {
                // Send user to the newly created institution, auto-logging them in.
                $this->redirect($this->institution->setupAutoLogin($form['admin_username'], 
                        $form['admin_password_user'], 600));
            }
            else
            {
                // send GC User to new platform via the gotoplatform tool.
                $this->redirect($CFG->current_app->getAppUrl() . 'local/platform_access.php?id=' . 
                        $this->institution->getShortName());
            }
        }
        // otherwise reload the form with error messages displayed and delete address and person records
        else
        {
            if ($this->institution)
            {
                $this->institution->delete();
            }
            $person2Object->delete();
            $this->getResponse()->setTitle('Create a Trial Platform');
            $this->setTemplate('newInstitutionForm');
        }
    }
    public function executeNew(sfWebRequest $request)
    {
        // NOTE 1: At this time, we are not allowing anyone outside of GC to create new platforms
        // If this should change, remove this security check. All code to support user creation is in place
        global $CFG;
        if (!$CFG->current_app->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError('Non GCUser attempted access to institution/create', 'gcpageaccessdenied');
        }
        // END NOTE 1
        $this->institutionForm = new GcrTrialApplicationForm();

        if (!$this->formErrors)
        {
            $this->formErrors = array();
        }
        $this->getResponse()->setTitle('Create a Trial Platform');
    }
    protected function setTrialApplicationToVerify($id)
    {
        if (!$this->application = Doctrine::getTable('GcrTrialApplication')->find($id))
        {
            global $CFG;
            $CFG->current_app->gcError('No trial application record for: ' . $id, 'gcdatabaseerror');
        }
    }
    public function executeSendVerificationEmail(sfWebRequest $request)
    {
        global $CFG;
        $this->setTrialApplicationToVerify($request->getParameter('id'));
        $this->sendVerificationEmail();
        $this->redirect($CFG->current_app->getUrl() . '/institution/verify?aid=' .
                $this->application->getId());
    }
    public function executeProcess(sfWebRequest $request)
    {
        global $CFG;
        $this->formErrors = array();
        $this->institutionForm = new GcrTrialApplicationForm();
        $form = $request->getPostParameters();

        // make an address object to insert in to the address table for the address submitted
        $addressObject = new GcrAddress();
        $addressObject->setStreet1($form['street1']);
        $addressObject->setStreet2($form['street2']);
        $addressObject->setCity($form['city']);
        $addressObject->setState($form['state']);
        $addressObject->setCountry($form['country']);
        $addressObject->setZipcode($form['zipcode']);

        // Try to add the eschool's address to the address table
        $addressObject->save();

        // make a person object to insert in to the person table for contact 1
        $personObject = new GcrPerson();
        $personObject->setFirstName($form['first_name']);
        $personObject->setLastName($form['last_name']);
        $personObject->setAddress($addressObject->getId());
        $personObject->setPhone1($form['phone1']);
        $personObject->setPhone2($form['phone2']);
        $personObject->setEmail($form['email']);

        // Try to add the contact 1 to the person table
        $personObject->save();

        $verify = GcrEschoolTable::generateRandomString();

        // make an array of values to validate as the institution form
        $institutionFields = array( 'id' => '',
                                    'contact' => $personObject->getId(),
                                    'address' => $addressObject->getId(),
                                    'verify_hash' => $verify,
                                    'street1' => $form['street1'],
                                    'street2' => $form['street2'],
                                    'city' => $form['city'],
                                    'state' => $form['state'],
                                    'country' => $form['country'],
                                    'zipcode' => $form['zipcode'],
                                    'first_name' => $form['first_name'],
                                    'last_name' => $form['last_name'],
                                    'phone1' => $form['phone1'],
                                    'phone2' => $form['phone2'],
                                    'email' => $form['email'],
                                    '_csrf_token' => $form['_csrf_token']);

        // try to add the eschool data to the eschool table
        if (!$trial_application_record = $this->processForm($institutionFields,
                $this->institutionForm, $request->getFiles()))
        {
            $this->formErrors['institutionRecord'] = 'Some Field(s) Have Missing or Incorrect Data';
        }

        // If the institution record was saved, we send an email to verify the user before creating the new institution
        if (count($this->formErrors) == 0)
        {
            //create Constant Contact entry for user of newly created eschool
            $this->ccCreateContact($form);

            if ($CFG->current_app->hasPrivilege('GCUser'))
            {
                // skip email verification is this is a gc admin
                $this->redirect($CFG->current_app->getUrl() . '/institution/verify?aid=' .
                    $trial_application_record->getId());
            }
            $this->redirect($CFG->current_app->getUrl() . '/institution/sendVerificationEmail?id=' . 
                    $trial_application_record->getId());
        }
        // otherwise reload the form with error messages displayed and delete address and person records
        else
        {
            $addressObject->delete();
            $personObject->delete();
            $this->getResponse()->setTitle('Create a Trial Platform');
            $this->setTemplate('new');
        }
    }

    public function executeSettings(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access institution/settings.", 'gcpageaccessdenied');
        }
        $this->getResponse()->setTitle('Platform Settings');
    }

    public function executeSetConfigVar(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        global $CFG;
        $CFG->current_app->requireMahara();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access institution/settings.", 'gcpageaccessdenied');
        }
        $form = $request->getPostParameters();
        $keys = array_keys($form);
        $allowed_settings = $this->getConfigVarArray();
        if (isset($allowed_settings[$keys[0]]))
        {
            if ($this->validateConfigVar($allowed_settings[$keys[0]], $form[$keys[0]]))
            {
                $CFG->current_app->setConfigVar($allowed_settings[$keys[0]], $form[$keys[0]]);
            }
            else
            {
                $CFG->current_app->gcError('Invalid config value: ' . $form[$keys[0]], 'gcdatabaseerror');
            }
        }
        return sfView::NONE;
    }

    protected function getConfigVarArray()
    {
        $allowed_settings = array('color' => 'gc_background_color',
                                  'texture' => 'gc_background_image');
        return $allowed_settings;
    }
    protected function validateConfigVar($name, $value)
    {
        $regex_array = array('gc_background_color' => '/^#([0-9a-f]{1,2}){3}$/i',
                             'gc_background_image' => '/^[a-z0-9_-]+\.[a-z]{3,4}|none$/i');
        global $CFG;
        return preg_match($regex_array[$name], $value);
    }
    // This action returns a json object of the current users Amazon s3 File Urls 
    public function executeGetUserStorageFileList(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $return_array = array();
        $user_storage = new GcrUserStorageAccessS3();
        $file_list = $user_storage->getFileList(false);
        foreach ($file_list as $filename)
        {
            $file_array = array('filename' => $filename, 'mimetype' => GcrFileLib::mimeinfo($filename));
            $return_array[$user_storage->getStaticUrl($filename)] = $file_array;
        }
        $this->getResponse()->setHttpHeader('Content-type', 'application/json');
        return $this->renderText(json_encode($return_array));
    }
    public function executeSetCatalogConfigVar(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        global $CFG;
        $CFG->current_app->requireMahara();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access institution/settings.", 'gcpageaccessdenied');
        }
        $form = $request->getPostParameters();
        $keys = array_keys($form);
        $allowed_settings = $this->getConfigVarArray();
        
        $eschools = $CFG->current_app->getEschools();
        foreach($eschools as $eschool)
        {
            if ($this->validateConfigVar($allowed_settings[$keys[0]], $form[$keys[0]]))
            {
                $eschool->setConfigVar($allowed_settings[$keys[0]], $form[$keys[0]]);
            }
            else
            {
                $CFG->current_app->gcError('Invalid config value: ' . $form[$keys[0]], 'gcdatabaseerror');
            }
        }
        return sfView::NONE;
    }
    public function executeGetUserStorageFile(sfWebRequest $request)
    {
        global $CFG;
        
        $get_params = $request->getGetParameters();
        $signed_request = new GcrSignedRequest($get_params);
        if (!$signed_request->validateSignature())
        {
            $CFG->current_app->gcError('Signature Invalid', 'gcpageaccessdenied');
        }
        $file = $get_params[GcrStorageAccessS3::FILE_GET_PARAMETER];
        
        if ($file)
        {
            if (!isset($get_params['app']))
            {
                $app = $CFG->current_app->getInstitution();
            }
            else
            {
                $app = GcrInstitutionTable::getApp($get_params['app']);
            }
            $s3_storage = new GcrStorageAccessS3($app);
            if (!$s3_storage->isPublicObject($file))
            {
                $CFG->current_app->requireLogin();
                $current_user = $CFG->current_app->getCurrentUser();
                $role_manager = $current_user->getRoleManager();
                if (isset($get_params['course_id']) && !$role_manager->hasPrivilege('EschoolAdmin'))
                {
                    // make sure the current user has access to this course
                    $flag = false;
                    $mdl_course = $CFG->current_app->getCourse($get_params['course_id']);
                    if ($mdl_course)
                    {
                        // For new course instances, we want to maintain access to
                        // Cloud Storage URLs with course id signed to parent course.
                        $course_collection = $mdl_course->getCourseCollection();
                        if ($course_collection)
                        {
                            foreach ($course_collection->getCourses() as $course_instance)
                            {
                                if ($role_manager->hasCourseAccess($course_instance))
                                {
                                    $flag = true;
                                    break;
                                }
                            }
                        }
                        else
                        {
                            $flag = $role_manager->hasCourseAccess($mdl_course);
                        }
                    }
                    else
                    {
                        $CFG->current_app->gcError('course_id parameter ' . $get_params['course_id'] .
                                'does not exist', 'gcdatabaseerror');
                    }
                    if (!$flag)
                    {
                        $CFG->current_app->gcError('User Does Not Have Course Access', 'gcpageaccessdenied');
                    }         
                }
            }
            $url = $s3_storage->getObjectUrl($file);
        }
        else
        {
            $url = $CFG->current_app->getUrl();
        }
        $this->redirect($url);
    }
   
    public function executeViewUserStorage(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        $role_manager = $CFG->current_app->getCurrentUser()->getRoleManager();
        $this->gc_admin = $role_manager->hasPrivilege('GCUser');
        $this->owner = $role_manager->hasPrivilege('EschoolAdmin');
        $folder = $request->getParameter('folder');
        $this->user_storage = new GcrUserStorageAccessS3($folder);
        $this->current_folder = $this->user_storage->getKey();
        $this->file_list = $this->user_storage->getFileList();
        $this->folder_list = $this->user_storage->getFolders();
        $this->user_storage_table = new GcrUserstorageAccessS3Table($this->user_storage);
        $this->getResponse()->setTitle('Remote Storage Management');
        sfConfig::set('sf_escaping_strategy', 'off');
    }
    public function executeUpdateMnetConnections(sfWebRequest $request)
    {
        global $CFG;
        if ($CFG->current_app->isMahara() && $CFG->current_app->getConfigVar('gc_new_mnet_key'))
        {
            $CFG->current_app->updateEschoolMnetConnections();
            $CFG->current_app->deleteFromMhrTable('config', 'field', 'gc_new_mnet_key');
            if ($default_eschool = $CFG->current_app->getDefaultEschool())
            {
                if ($default_eschool->getResetKeys())
                {
                    $this->redirect($default_eschool->getAppUrl());
                }
            }
        }
        $this->redirect($CFG->current_app->getAppUrl());
    }
    public function executeMoveUserStorageFile(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        $form = $request->getPostParameters();
        if (isset($form['gc_move_file_form_file_id']) && 
                isset($form['gc_move_file_form_file_new_id']))
        {
            $user_storage = new GcrUserStorageAccessS3();
            $user_storage->moveObject($form['gc_move_file_form_file_id'], $form['gc_move_file_form_file_new_id']);
        }
        $url = $CFG->current_app->getUrl() . '/institution/viewUserStorage';
        if (isset($form['gc_move_file_form_key']))
        {
            $url .= '?folder=' . $form['gc_move_file_form_key'];
        }
        $this->redirect($url);
    }
    public function executeDeleteUserStorageFile(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        $form = $request->getPostParameters();
        if ($file = $request->getParameter('file'))
        {
            $user_storage = new GcrUserStorageAccessS3();
            $user_storage->deleteObject($file);
        }
        $url = $CFG->current_app->getUrl() . '/institution/viewUserStorage';
        $folder = $request->getParameter('key');
        if (isset($folder))
        {
            $url .= '?folder=' . $folder;
        }
        $this->redirect($url);
    }
    
    public function executeSuspendEclassroom(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        if (!$CFG->current_app->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError('Unauthorized access by user id ' . 
                    $CFG->current_app->getCurrentUser()->getObject()->id, 'gcpageaccessdenied');
        }
        $id = $request->getParameter('id');
        if (!$id)
        {
            $CFG->current_app->gcError('Missing Required parameter "id"', 'gcdatabaseerror');
        }
        $eclassroom = GcrEclassroomTable::getInstance()->find($id);
        if (!$eclassroom)
        {
            $CFG->current_app->gcError('eClassroom record not found', 'gcdatabaseerror');
        }
        if ($eclassroom->getSuspended() == 't')
        {
            $eclassroom->setSuspended(false);
        }
        else
        {
            $eclassroom->setSuspended(true);
        }
        $eclassroom->save();
        $this->redirect($CFG->current_app->getUrl() . '/institution/eclassrooms');
    }
    
    public function executeDeleteEclassroom(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        if (!$CFG->current_app->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError('Unauthorized access by user id ' . 
                    $CFG->current_app->getCurrentUser()->getObject()->id, 'gcpageaccessdenied');
        }
        $id = $request->getParameter('id');
        if (!$id)
        {
            $CFG->current_app->gcError('Missing Required parameter "id"', 'gcdatabaseerror');
        }
        $eclassroom = GcrEclassroomTable::getInstance()->find($id);
        if (!$eclassroom)
        {
            $CFG->current_app->gcError('eClassroom record not found', 'gcdatabaseerror');
        }
        $eclassroom->delete();
        $this->redirect($CFG->current_app->getUrl() . '/institution/eclassrooms');
    }

    // This function attempts to save form values to the database if the values are valid
    protected function processForm($formFields, sfForm $form, $files = array())
    {
        $form->bind($formFields, $files);
        if (count($this->formErrors) == 0 && $form->isValid())
        {
            $record = $form->save();
            return $record;
        }
        return false;
    }
    // This function handles validating the short name.
    // It adds a form error to $this->formError for every error found.
    protected function validateInstitutionShortName ($shortName)
    {
        global $CFG;
        $shortNameValid = true;
        if (!GcrEschoolTable::isShortNameValid($shortName))
        {
            $this->formErrors['short_nameSize'] = 'URL must be 3-32 alphanumeric characters (1st character must be a letter).';
            $shortNameValid = false;
        }
        if (GcrEschoolTable::isShortNameUsed($shortName))
        {
            $this->formErrors['short_nameUsed'] = 'URL is already in use.';
            $shortNameValid = false;
        }
        if (GcrEschoolTable::isShortNameReserved($shortName))
        {
            $this->formErrors['short_nameReserved'] = 'URL is a reserved word.';
            $shortNameValid = false;
        }
        return $shortNameValid;
    }
    // This function handles validating the short name.
    // It adds a form error to $this->formError for every error found.
    protected function validateEschoolShortName ($shortName)
    {
        global $CFG;
        $shortNameValid = true;
        if (!GcrEschoolTable::isShortNameValid($shortName))
        {
            $this->formErrors['short_nameSize'] = 'eClassroom URL must be 2-32 alphanumeric characters (1st character must be a letter).';
            $shortNameValid = false;
        }
        if (GcrEschoolTable::isShortNameUsed($shortName))
        {
            $app = GcrInstitutionTable::getApp($shortName);
            if (!$CFG->current_app->hasPrivilege('GCUser') || $app->isMahara())
            {
                $this->formErrors['short_nameUsed'] = 'eClassroom URL is already in use.';
                $shortNameValid = false;
            }
        }
        if (GcrEschoolTable::isShortNameReserved($shortName))
        {
            $this->formErrors['short_nameReserved'] = 'eClassroom URL is a reserved word.';
            $shortNameValid = false;
        }
        return $shortNameValid;
    }
    // This function handles validating the eschool type to make sure it is not hacked.
    protected function validateInstitutionType($type)
    {
        global $CFG;
        if ($institution_type = Doctrine::getTable('GcrInstitutionType')->find($type))
        {
            if ($institution_type->getIsPublic() || $CFG->current_app->hasPrivilege('GCUser'))
            {
                return true;
            }
        }
        $this->formErrors['eschool_type'] = 'Platform type is invalid.';
        return false;
    }
    //This function creates a new Constant Contact entry called from the creation of a new eSchool
    protected function ccCreateContact($form)
    {
        $constantContact = new constantContact();
        $params = array();
        //format an xml document with the relevant information to add a contact
        $params['email_address']	= $form['email'];
        $params['first_name']		= $form['first_name'];
        $params['last_name']		= $form['last_name'];
        $params['work_number']		= $form['phone1'];
        $params['home_number']		= $form['phone2'];
        $params['address_line_1']	= $form['street1'];
        $params['address_line_2']	= $form['street2'];
        $params['city_name']		= $form['city'];
        $params['state_name']		= $form['state'];
        $params['country_code']		= $form['country'];
        $params['zip_code']			= $form['zipcode'];
        //getLists() returns an array of lists, we need to select one
        //this is defined in gcr.class.php
        $params['lists']			= $constantContact->getLists();

        $xml = $constantContact->createContactXML(null, $params);

        try
        {
            if (!$constantContact->subscriberExists($form['email']))
            {
                $constantContact->addSubscriber($xml);
            }
        }
        catch (Exception $e)
        {
            error_log("\n" . date('d/m/Y H:i:s', time()) . ": Constant Contact error, {$e->getMessage()}", 3, gcr::rootDir . 'debug/error.log');
        }
    }
    protected function sendVerificationEmail()
    {
        $subject = 'Verification of New Stratus Platform';
        $params = array('application' => $this->application);
        $email = new GcrEmailer('verify_new_institution',
                $this->application->getContactObject()->getEmail(), $subject, $params);
        $email->sendHtmlEmail();
    }
    // This function sends an email to a user who has just created a new trial eschool
    protected function emailNewEschoolOwner()
    {
        $person = $this->institution->getPerson2Object();
        //send email to eschool owner
        $trial_length = gcr::trialLengthInDays;
        $trial_end_date = date('F j, Y', $trial_length * 86400 + time());
        $params = array('eschool_full_name' => $this->institution->getFullName(),
                        'eschool_short_name' => $this->institution->getShortName(),
                        'eschool_url' => $this->institution->getAppUrl(),
                        'default_eschool_url' => $this->institution->getDefaultEschool()->getAppUrl(),
                        'trial_end_date' => $trial_end_date,
                        'trial_length' => $trial_length);
        $email = new GcrEmailer('trial_created', trim($person->getEmail()),
                'Your Global Classroom Stratus Platform has been created!', $params);
        $email->sendHtmlEmail();
    }

    // This function sends a notifiction email to a list of GC personnel that a new
    // trial eschool was created.
    protected function emailNewEschoolGC($owner_credentials)
    {
        $person = $this->institution->getPerson2Object();
        $contact_person = $this->institution->getPersonObject();
        $to = array
        (   //addresses receive information about transactions
            gcr::gcEschoolNotification
        );
        //send email to global classroom
        $from = "NewEschoolNotification@globalclassroom.us"; //set the headers
        $replyto = "noreply@globalclassroom.us";
        $params = array('time' 	=> date("D: m/d/Y H:i"),
                        'user' 	=> $owner_credentials->username,
                        'name' 	=> $person->getFirstName() . " " . $person->getLastName(),
                        'email'	=> $person->getEmail(),
                        'eschool_full_name' => $this->institution->getFullName(),
                        'eschool_url' 		=> $this->institution->getAppUrl(),
                        'default_eschool_url' => $this->institution->getDefaultEschool()->getAppUrl(),
                        'public_contact_phone'	=> $contact_person->getPhone1(),
                        'public_contact_phone_alt'	=> $contact_person->getPhone2(),
                        'public_contact_email' => $contact_person->getEmail(),
                        'admin_contact_phone'		=> $person->getPhone1(),
                        'admin_contact_phone_alt'	=> $person->getPhone2());
        $email = new GcrEmailer('trial_created_gc', $to, 'A New Stratus Platform Was Successfully Created', $params, $from, $replyto);
        $email->sendHtmlEmail();
    }
}
