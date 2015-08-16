<?php

/**
 * help actions.
 *
 * @package    globalclassroom
 * @subpackage help
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class helpActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex (sfWebRequest $request)
  {
  	$this->redirect("http://support.globalclassroom.us");
  }

  public function executeDemos (sfWebRequest $request)
  {
  	$this->redirect("http://support.globalclassroom.us/demos");
  }

  public function executeFaq (sfWebRequest $request)
  {
  	$this->redirect("http://support.globalclassroom.us/faq");
  }

  public function executeFeedback (sfWebRequest $request)
  {
  }

  public function executeGuides (sfWebRequest $request)
  {
  	$this->redirect("http://support.globalclassroom.us/guides");
  }

  public function executeLogin (sfWebRequest $request)
  {
  	$this->searchForm = new SearchEschoolForm();
  }

  public function executeRegistration (sfWebRequest $request)
  {
  }

  public function executeRequirements (sfWebRequest $request)
  {
  	$this->redirect("http://support.globalclassroom.us/faq/system-requirements");
  }

  public function executeTutorials (sfWebRequest $request)
  {
    $this->redirect("http://support.globalclassroom.us/tutorials");
  }
}