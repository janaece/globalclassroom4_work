<?php

/**
 * eschool actions.
 *
 * @package    globalclassroom
 * @subpackage eschool
 * @author     Ron Stewart
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */

class eschoolActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex (sfWebRequest $request)
    {
        $this->redirect("/help");
    }
    /**
     *  Executes edit action
     *  @param sfRequest $request A request object
    */
    public function executeEdit(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $role_manager = $CFG->current_app->getCurrentUser()->getRoleManager();

        if (!$role_manager->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("You do not have permission to access this page.", 'gcdatabaseerror');
        }
        if ($CFG->current_app->isMoodle())
        {
            $this->eschoolForm = new GcrEschoolEditForm(Doctrine::getTable($CFG->current_app->getTableName())->find($CFG->current_app->getId()));
        }
        else
        {
            $this->eschoolForm = new GcrInstitutionEditForm(Doctrine::getTable($CFG->current_app->getTableName())->find($CFG->current_app->getId()));
        }
        if (!$this->formErrors)
        {
            $this->formErrors = array();
        }
        $this->getResponse()->setTitle('Edit Platform Information');
    }
    // This action redirects users to the Mahara login page while passing the wantsurl
    // to be caught on the Mahara side and used in the mnet jump to get the user back
    // to the page they had requested when they got sent to Moodle's login page.
    public function executeLogin(sfWebRequest $request)
    {
        global $CFG, $SESSION;
        if ($CFG->current_app->isMoodle())
        {
            // Check to see if the logged out user has a cookie showing what institution they are from
            $institution = $CFG->current_app->getInstitutionFromCookie();    
            $url = $CFG->current_app->getInstitutionJumpUrl($SESSION->wantsurl, $institution);
            if (!$url)
            {
                $url = $CFG->current_app->getInstitutionJumpUrl($SESSION->wantsurl);
            }
            
            // If a unauthenicated user clicks on a course instance link from the course category page
            // in Moodle, we can assume that they are probably a new user on the system. Therefore,
            // we set up a registration type gcr_wants_url record to bring them back to the course
            // after registration is complete.
            if (strpos($request->getReferer(), $CFG->current_app->getAppUrl() . '/course/category.php') === 0 && 
                    strpos($SESSION->wantsurl, $CFG->current_app->getAppUrl() . '/course/view.php') === 0)
            {
                if (!$institution)
                {
                    $institution = $CFG->current_app->getInstitution();
                }
                $wants_url = GcrWantsUrlTable::createWantsUrl('simple', $institution, $url);
                $this->redirect($institution->getUrl() . '/eschool/registration?url=' . $wants_url);
                
            }
            else // Assume this is an existing user with a timed out session.
            {
                $this->redirect($url);
            }
        }
        else
        {
            $this->redirect($CFG->current_app->getAppUrl());
        }
    }
    
    // This action is provided to maintain a wantsurl to a Moodle page upon
    // new user registration. Courses accessed from the catgeory.php
    // page in Moodle trigger this action when the user is not logged in.
    public function executeRegistration(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $wants_url_id = $request->getParameter('url');
        if ($wants_url_id)   
        {
            $wants_url = GcrWantsUrlTable::getInstance()->find($wants_url_id);
            if ($wants_url)
            {
                $url = substr($wants_url->getWantsUrl(), strlen($CFG->current_app->getUrl()));
                // We need this action primarily so that we can call GcrWantsUrlTable::createWantsUrl
                // from the institution's domain because it sets a cookie as its means of saving a wantsurl
                GcrWantsUrlTable::createWantsUrl('registration', $CFG->current_app, $url);
                $wants_url->delete();
            }   
        }
        $this->redirect($CFG->current_app->getAppUrl() . 'register.php');
    }
    public function executeLogout()
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $current_user = $CFG->current_app->getCurrentUser();
        if (isset($current_user))
        {
            $current_user->logout();
        }
        $this->redirect($CFG->current_app->getAppUrl());
    }
    /**
     * Executes update action
     * @param sfRequest $request A request object
     */
    public function executeUpdate(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("You do not have permission to access this page.", 'gcdatabaseerror');
        }
        // get the post values
        $form = $request->getPostParameters();
        // convert allows_sponsors and visible fields in to a 't' or 'f' format
        $visible = $form['visible'] ? 't' : 'f';
        // create an eschoolObject based on the existing values in the DB
        $table = $CFG->current_app->getTableName();
        $app = Doctrine::getTable($table)->find($form['id']);
        $oldFullName = $app->getFullName();
        // make a new eschoolEditForm to be overwritten with modified fields from $form
        if ($CFG->current_app->isMoodle())
        {
            $this->eschoolForm = new GcrEschoolEditForm(Doctrine::getTable($table)->find($form['id']));
        }
        else
        {
            $this->eschoolForm = new GcrInstitutionEditForm(Doctrine::getTable($table)->find($form['id']));
        }
        // prepare an array of values to pass on the processForm function
        $eschoolFields = array(	'id' => $form['id'],
                                'full_name' => $form['full_name'],
                                'external_url' => $form['external_url'],
                                'contact1' => $app->getPersonObject()->id,
                                'contact2' => $app->getPerson2Object()->id,
                                'address' => $app->getAddress(),
                                'visible' => $visible,
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
                                'first_name_2' => $form['first_name_2'],
                                'last_name_2' => $form['last_name_2'],
                                'phone1_2' => $form['phone1_2'],
                                'phone2_2' => $form['phone2_2'],
                                'email_2' => $form['email_2'],
                                '_csrf_token' => $form['_csrf_token']);

        // Attempt to save the form data to the global table. If success, update all related tables
        if ($this->processForm($eschoolFields, $this->eschoolForm, $request->getFiles()))
        {
            // If fullname has been changed, we must update the Moodle or Mahara database as well
            if ($oldFullName != $form['full_name'])
            {
                $app->setAppTitle($form['full_name']);
            }

            // Update the address table entry for this eschool
            $addressObject = $app->getAddressObject();
            $addressObject->setStreet1($form['street1']);
            $addressObject->setStreet2($form['street2']);
            $addressObject->setCity($form['city']);
            $addressObject->setState($form['state']);
            $addressObject->setCountry($form['country']);
            $addressObject->setZipcode($form['zipcode']);
            $addressObject->save();

            // Update contact 1 for this eschool in person table
            $personObject = $app->getPersonObject();
            $personObject->setFirstName($form['first_name']);
            $personObject->setLastName($form['last_name']);
            $personObject->setAddress($app->getAddress());
            $personObject->setPhone1($form['phone1']);
            $personObject->setPhone2($form['phone2']);
            $personObject->setEmail($form['email']);
            $personObject->save();

            // Update contact 2 for this eschool in person table
            $person2Object = $app->getPerson2Object();
            $person2Object->setFirstName($form['first_name_2']);
            $person2Object->setLastName($form['last_name_2']);
            $person2Object->setAddress($app->getAddress());
            $person2Object->setPhone1($form['phone1_2']);
            $person2Object->setPhone2($form['phone2_2']);
            $person2Object->setEmail($form['email_2']);
            $person2Object->save();

            $this->redirect($app->getAppUrl());
        }
        else
        {
            $this->getResponse()->setTitle('Edit eSchool Information');
            $this->setTemplate('edit');
        }
    }
    public function executeUpdateMnetConnections(sfWebRequest $request)
    {
        global $CFG;
        if ($CFG->current_app->isMoodle() && $CFG->current_app->getConfigVar('gc_new_mnet_key'))
        {
            $CFG->current_app->updateInstitutionMnetConnections();
            $CFG->current_app->deleteFromMdlTable('config', 'name', 'gc_new_mnet_key');
        }
        $this->redirect($CFG->current_app->getInstitution()->getAppUrl());
    }
    public function executeEditContact (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("You do not have permission to access this page.", 'gcpageaccessdenied');
        }
        $table = $CFG->current_app->getTableName();
        if ($CFG->current_app->isMoodle())
        {
            $this->editContactForm = new GcrEditContactForm(Doctrine::getTable($table)->find($CFG->current_app->getId()));
        }
        else
        {
            $this->editContactForm = new GcrEditInstitutionContactForm(Doctrine::getTable($table)->find($CFG->current_app->getId()));
        }
        if (!$this->formErrors)
        {
            $this->formErrors = array();
        }
        $this->getResponse()->setTitle('Edit Contact Information');
    }
    public function executeError(sfWebRequest $request)
    {
        if (!$this->error = GcrErrorHandler::getError($request->getParameter('msg')))
        {
            $this->forward404();
        }
        $this->getResponse()->setTitle('Error Page');
    }
    public function executeUpdateContact (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("You do not have permission to access this page.", 'gcdatabaseerror');
        }
        // get the post values
        $form = $request->getPostParameters();

        $table = $CFG->current_app->getTableName();
        $app = Doctrine::getTable($table)->find($form['app_id']);

        // make a new eschoolEditForm to be overwritten with modified fields from $form
        if ($CFG->current_app->isMoodle())
        {
            $this->editContactForm = new GcrEditContactForm(Doctrine::getTable($table)->find($form['app_id']));
        }
        else
        {
            $this->editContactForm = new GcrEditInstitutionContactForm(Doctrine::getTable($table)->find($form['app_id']));
        }
        // prepare an array of values to pass on the processForm function
        $editContactFields = array( 'first_name' => $form['first_name'],
                                    'last_name' => $form['last_name'],
                                    'street1' => $form['street1'],
                                    'street2' => $form['street2'],
                                    'city' => $form['city'],
                                    'state' => $form['state'],
                                    'zipcode' => $form['zipcode'],
                                    'phone' => $form['phone'],
                                    'email' => $form['email'],
                                    'app_id' => $form['app_id'],
                                    'address_id' => $form['address_id'],
                                    'person_id'	=> $form['person_id'],
                                    '_csrf_token' => $form['_csrf_token']);
        // Attempt to save the form data to the global table. If success, update all related tables
        if ($this->processForm($editContactFields, $this->editContactForm, $request->getFiles()))
        {
            // Update the address table
            $addressObject = $app->getAddressObject();
            $addressObject->setStreet1($form['street1']);
            $addressObject->setStreet2($form['street2']);
            $addressObject->setCity($form['city']);
            $addressObject->setState($form['state']);
            $addressObject->setZipcode($form['zipcode']);
            $addressObject->save();

            // Update contact 1 for this eschool in person table
            $personObject = $app->getPersonObject();
            $personObject->setFirstName($form['first_name']);
            $personObject->setLastName($form['last_name']);
            $personObject->setAddress($app->getAddress());
            $personObject->setPhone1($form['phone']);
            $personObject->setEmail($form['email']);
            $personObject->save();

            $this->redirect($app->getAppUrl());
        }
        else
        {
            $this->getResponse()->setTitle('Edit Contact Information');
            $this->setTemplate('editContact');
        }
    }
    public function executeNew (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access eschool/new", 'gcpageaccessdenied');
        }
        $this->eschoolForm = new GcrEschoolForm();
        if (!$this->formErrors)
        {
            $this->formErrors = array();
        }
        $this->getResponse()->setTitle('Create a Catalog');
    }
    public function executeSearch(sfWebRequest $request)
    {
        $form = $request->getPostParameters();
        $this->searchForm = new GcrSearchEschoolForm();
        if ($request->isMethod(sfRequest::POST))
        {
            $form['eschoolPattern'] = stripslashes(trim($form['eschoolPattern']));
            $this->searchForm->bind($form);
            if ($this->searchForm->isValid())
            {
                $exact_matches_start_of_first_word = array();
                $exact_matches_start_of_word = array();
                $exact_matches_middle_of_word = array();
                $inexact_matches = array();
                $pattern = strtolower($form['eschoolPattern']);
                $search = new Approximate_Search($pattern, 1);
                $eschools = Doctrine::getTable('GcrEschool')->findAll();
                $institutions = Doctrine::getTable('GcrInstitution')->findAll();
                $appList = array();
                foreach($eschools as $eschool)
                {
                    $appList[$eschool->getShortName] = strtolower($eschool->getFullName());
                }
                foreach($institutions as $institution)
                {
                    $appList[$institution->getShortName] = strtolower($institution->getFullName());
                }
                foreach($appList as $short_name => $full_name)
                {
                    $matches = $search->search($full_name);
                    if (count($matches) > 0)
                    {
                        $index = strpos($full_name, $pattern);
                        if ($index || $index === 0)
                        {
                            if ($index == 0)
                            {
                                $exact_matches_start_of_first_word[$short_name] = $eschool;
                            }
                            else if ($full_name[$index - 1] == ' ')
                            {
                                $exact_matches_start_of_word[$short_name] = $eschool;
                            }
                            else
                            {
                                $exact_matches_middle_of_word[$short_name] = $eschool;
                            }
                        }
                        else
                        {
                            $inexact_matches[$short_name] = $eschool;
                        }
                    }
                }
            }
            $this->eschoolList = array($exact_matches_start_of_first_word, $exact_matches_start_of_word,
                    $exact_matches_middle_of_word, $inexact_matches);
        }
        $this->getResponse()->setTitle('Search for an eSchool');
    }
    public function executeSale(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        if (!$type = $request->getParameter('type'))
        {
            $this->redirect($CFG->current_app->getUrl());
        }
        $template = 'sale' . ucfirst($type);
        $this->params = $request->getParameterHolder()->getAll();
        if (is_readable(gcr::rootDir . 'apps/frontend/modules/eschool/templates/'. $template . 'Success.php'))
        {
            $this->setTemplate($template);
        }
        else
        {
            $CFG->current_app->gcError('Payment Type: ' . $request->getParameter('type') . ' does not exist for this catalog', 'gcdatabaseerror');
        }
        $this->getResponse()->setTitle('Purchase ' . ucfirst($type));
    }
    public function executeActivation(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasRole('Owner'))
        {
            $this->redirect($CFG->current_app->getAppUrl());
        }
        if (!$this->monthly_cost = $CFG->current_app->getConfigVar('gc_eschool_cost_month'))
        {
            if (!$CFG->gc_eschool_cost_year)
            {
                $CFG->current_app->gcError("Eschool purchase error: eSchool has no mdl_config vars for eschool costs", 'activationnotsupported');
            }
        }
        $this->yearly_cost = $CFG->current_app->getConfigVar('gc_eschool_cost_year');
        $this->getResponse()->setTitle('Purchase Your eSchool');
    }

    // This action will be for local eSchool customizations
    public function executeSettings (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMoodle();
        $CFG->current_app->requireLogin();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access eschool/settings.", 'gcpageaccessdenied');
        }
        $this->getResponse()->setTitle('eSchool Settings');
    }
    public function executeInstallLangPack(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());

        //TODO: Add code from Moodle 2.0 to GcrCurrentEschool.class.php to perform this action

        global $CFG;
        $CFG->current_app->requireMoodle();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access eschool/settings.", 'gcpageaccessdenied');
        }
        $form = $request->getPostParameters();
        $CFG->current_app->installLangPack($form);

        return sfView::NONE;
    }

    public function executeDeleteLangPack(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());

        //TODO: Add code from Moodle 2.0 to GcrCurrentEschool.class.php to perform this action

        global $CFG;
        $CFG->current_app->requireMoodle();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access eschool/settings.", 'gcpageaccessdenied');
        }
        $form = $request->getPostParameters();
        $CFG->current_app->deleteLangPack($form);

        return sfView::NONE;
    }

    public function executeSetConfigVar(sfWebRequest $request)
    {
        $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
        global $CFG;
        $CFG->current_app->requireMoodle();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->hasPrivilege('EschoolAdmin'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access eschool/settings.", 'gcpageaccessdenied');
        }
        $form = $request->getPostParameters();
        $keys = array_keys($form);
        $allowed_settings = array(  'color' => 'gc_background_color',
                                    'enrol_cost' => 'enrol_cost',
                                    'enrol' => 'enrol',
                                    'lang' => 'lang',
                                    'texture' => 'gc_background_image');
        if (isset($allowed_settings[$keys[0]]))
        {
            $CFG->current_app->setConfigVar($allowed_settings[$keys[0]], $form[$keys[0]]);
        }
        $CFG->current_app->clearCache();
        return sfView::NONE;
    }

    // This action is called in response to the create new eschool form being submitted
    public function executeCreate (sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMahara();
        $current_user = $CFG->current_app->getCurrentUser();
        if (!$current_user->getRoleManager()->HasPrivilege('GCUser'))
        {
            $CFG->current_app->gcError("Unauthorized attempt to access eschool/create.", 'gcpageaccessdenied');
        }

        $this->formErrors = array();
        $this->eschoolForm = new GcrEschoolForm();
        $form = $request->getPostParameters();

        // validate form values
        $this->validateShortName($form['short_name']);
        if (!GcrEschoolTypeTable::validateEschoolType($form['eschool_type']))
        {
            $this->formErrors['eschool_type'] = 'eSchool type is invalid.';
        }

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

        // make a person object to insert in to the person table for contact 2
        $person2Object = new GcrPerson();
        $person2Object->setFirstName($form['first_name_2']);
        $person2Object->setLastName($form['last_name_2']);
        $person2Object->setAddress($addressObject->getId());
        $person2Object->setPhone1($form['phone1_2']);
        $person2Object->setPhone2($form['phone2_2']);
        $person2Object->setEmail($form['email_2']);

        // try to add the contact 2 to the person table
        $person2Object->save();

        // make an array of values to validate as the eschool form
        $eschoolFields = array(	'id' => '',
                                'full_name' => $form['full_name'],
                                'short_name' => strtolower($form['short_name']),
                                'external_url' => $form['external_url'],
                                'logo' => $CFG->current_app->getLogo(),
                                'suspended' => '',
                                'can_sell' => '',
                                'contact1' => $personObject->getId(),
                                'contact2' => $person2Object->getId(),
                                'address' => $addressObject->getId(),
                                'eschool_type' => $form['eschool_type'],
                                'eschool_creator' => $CFG->current_app->getId(),
                                'admin_password' => GcrEschoolTable::generateAdminPassword(),
                                'password_salt' => GcrEschoolTable::generateRandomString(),
                                'creation_date' => time(),
                                'organization_id' => $CFG->current_app->getId(),
                                'visible' => '1',
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
                                'first_name_2' => $form['first_name_2'],
                                'last_name_2' => $form['last_name_2'],
                                'phone1_2' => $form['phone1_2'],
                                'phone2_2' => $form['phone2_2'],
                                'email_2' => $form['email_2'],
                                '_csrf_token' => $form['_csrf_token']);

        // try to add the eschool data to the eschool table
        if (!$eschoolRecord = $this->processForm($eschoolFields, $this->eschoolForm, $request->getFiles()))
        {
            $this->formErrors['eschoolRecord'] = 'Some Field(s) Have Missing or Incorrect Data';
        }
        // If the eschool record was saved, we need to also add a trial record for it
        if (count($this->formErrors) == 0)
        {
            // create the eschool if everything is valid
            $eschoolRecord->create();
            $this->emailNewEschoolGC($eschoolRecord, $personObject, $person2Object);

            // send user to the newly created eschool
            $this->redirect($eschoolRecord->getUrl());
        }
        // otherwise reload the form with error messages displayed and delete address and person records
        else
        {
            $addressObject->delete();
            $personObject->delete();
            $person2Object->delete();
            $this->getResponse()->setTitle('Create a Trial eSchool');
            $this->setTemplate('new');
        }
    }
    
    public function executeResetCourseBlocks(sfWebRequest $request)
    {
        global $CFG;
        $CFG->current_app->requireMoodle();
        $CFG->current_app->requireLogin();
        if (!$CFG->current_app->hasPrivilege('GCAdmin'))
        {
            $CFG->current_app->gcError('Unauthorized access to Reset Blocks', 'gcpageaccessdenied');
        }
        $CFG->current_app->resetCourseBlocks();
        $this->getResponse()->setTitle('Course Blocks Reset');
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
    protected function validateShortName ($shortName, $testForValidNewName = true)
    {
        $shortNameValid = true;

        if (!GcrEschoolTable::isShortNameValid($shortName))
        {
            $this->formErrors['short_nameSize'] = 'eSchool URL must be 3-32 alphanumeric characters (1st character must be a letter).';
            $shortNameValid = false;
        }

        if ($testForValidNewName && GcrEschoolTable::isShortNameUsed($shortName))
        {
            $this->formErrors['short_nameUsed'] = 'eSchool URL is already in use.';
            $shortNameValid = false;
        }

        if ($testForValidNewName && GcrEschoolTable::isShortNameReserved($shortName))
        {
            $this->formErrors['short_nameReserved'] = 'eSchool URL is a reserved word.';
            $shortNameValid = false;
        }
        return $shortNameValid;
    }

    // This function sends a notifiction email to a list of GC personnel that a new
    // trial eschool was created.
    protected function emailNewEschoolGC($eschool, $contact1, $contact2)
    {
        global $CFG;
        $mhr_user_obj = $CFG->current_app->getCurrentUser()->getObject();
        $institution = $eschool->getInstitution();

        $to = array
        (   //addresses receive information about transactions
            gcr::gcEschoolNotification
        );
        //send email to global classroom
        $from = "NewEschoolNotification@globalclassroom.us"; //set the headers
        $replyto = "noreply@globalclassroom.us";
        $params = array('time' 	=> date("D: m/d/Y H:i"),
                        'user' 	=> $mhr_user_obj->username,
                        'name' 	=> $mhr_user_obj->firstname . " " . $mhr_user_obj->lastname,
                        'email'	=> $mhr_user_obj->email,
                        'eschool_full_name' => $eschool->getFullName(),
                        'eschool_url' 		=> $eschool->getAppUrl(),
                        'institution_full_name' => $institution->getFullName(),
                        'institution_url' 		=> $institution->getAppUrl(),
                        'public_contact_phone'	=> $contact1->getPhone1(),
                        'public_contact_phone_alt'	=> $contact1->getPhone2(),
                        'admin_contact_phone'		=> $contact2->getPhone1(),
                        'admin_contact_phone_alt'	=> $contact2->getPhone2());
        $email = new GcrEmailer('new_eclassroom_gc', $to, 'A New Stratus Catalog Was Successfully Created',
                $params, $from, $replyto);
        $email->sendHtmlEmail();
    }
}