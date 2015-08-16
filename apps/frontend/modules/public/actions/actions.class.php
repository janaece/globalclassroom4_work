<?php

/**
 * public actions.
 *
 * @package    globalclassroom
 * @subpackage public
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class publicActions extends sfActions
{
     /**
      * Executes index action
      *
      * @param sfRequest $request A request object
      */
    public function executeIndex (sfWebRequest $request)
    {
        if (!($request->getHost() == gcr::rootDomainName || $request->getHost() == gcr::domainName))
        {
            global $CFG;
            $this->redirect($CFG->current_app->getAppUrl());
        }
        $this->getResponse()->setTitle('Global Classroom - Cloud-based, private social network and eLearning Platform');
        $this->getResponse()->addMeta('description',
        'Global Classroom provides a cloud-based private social and elearning platform for business, education, ' .
        'government, non-profits, and membership organizations.');
    }
    public function executeError404 (sfWebRequest $request)
    {
    }

    public function executeBlog (sfWebRequest $request)
    {
        $this->redirect("http://globalclassroom.us/blog/");
    }

    public function executeCredits (sfWebRequest $request)
    {
        $this->getResponse()->setTitle('Global Classroom - Credit Information');
    }

    public function executePolicy (sfWebRequest $request)
    {
        // This checks the site files of an eSchool for policy.html.  If it exists, include the file and wrap it with a div.
        // If not, do nothing.
        global $CFG;
        $policy_url = $CFG->wwwroot . '/file.php/1/policy.txt';
        if($user_policy = file_get_contents($policy_url))
        {
                $this->getResponse()->setSlot('user_policy', '<div id="user_policy">' . $user_policy . '</div>');
        }
    }

    public function executeRefunds (sfWebRequest $request)
    {
        $this->getResponse()->setTitle('Global Classroom - Refund Policy');
    }

    public function executeTerms (sfWebRequest $request)
    {
    }

    public function executeRequirements (sfwebRequest $request)
    {
        $this->getResponse()->setTitle('Global Classroom - System Requirements');
    }

    public function executeMigration (sfwebRequest $request)
    {
        $this->getResponse()->setTitle('Global Classroom - Migration');
    }

    public function executeSearch (sfWebRequest $request)
    {
        $form = $request->getPostParameters();
        $this->searchForm = new GcrSearchEschoolForm();
        $this->eschoolList = '';
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

                        $institutions = Doctrine::getTable('GcrInstitution')->findAll();
                        foreach($institutions as $institution)
                        {
            if(!$institution->is_internal)
            {
                $full_name = strtolower($institution->getFullName());
                $matches = $search->search($full_name);
                if (count($matches) > 0)
                {
                    $index = strpos($full_name, $pattern);
                    if ($index || $index === 0)
                    {
                        if ($index == 0)
                        {
                            $exact_matches_start_of_first_word[$institution->getShortName()] = $institution;
                        }
                        else if ($full_name[$index - 1] == ' ')
                        {
                            $exact_matches_start_of_word[$institution->getShortName()] = $institution;
                        }
                        else
                        {
                            $exact_matches_middle_of_word[$institution->getShortName()] = $institution;
                        }
                    }
                    else
                    {
                        $inexact_matches[$institution->getShortName()] = $institution;
                    }
                }
            }
                        }
                        $this->eschoolList = array($exact_matches_start_of_first_word, $exact_matches_start_of_word, 
                                $exact_matches_middle_of_word, $inexact_matches);
                }
        }
        $this->getResponse()->setTitle('Global Classroom - Search for a Community');
        $this->getResponse()->addMeta('description',
        'Search for a Community on the Global Classroom platform');
    }

    public function executeNotfound (sfWebRequest $request)
    {
    }

    // Top-Level Routing Rules
    public function executeLogin (sfWebRequest $request)
    {
        $this->getResponse()->setTitle('Global Classroom - Login');
        $this->getResponse()->addMeta('description',
        'Log in to the Act48 Center, GlobalExpert, GlobalK12, GlobalBusiness, or Find an eSchool');
    }

    // Classic Alert Page
    public function executeClassicAlert (sfWebRequest $request)
    {
    }
}