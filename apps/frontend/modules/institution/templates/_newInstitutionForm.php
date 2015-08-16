<?php global $CFG; ?>
<link rel="stylesheet" href="/lib/leightbox/css/screen.css" media="screen,projection" type="text/css" />

<!-- JavaScript -->
<script type="text/javascript" src="/lib/leightbox/scripts/prototype.js"></script>
<script type="text/javascript" src="/lib/leightbox/scripts/lightbox.js"></script>
<script type="text/javascript">
    function updateCoursesSuggestion(obj)
    {
        var courses_short_name = document.getElementById('default_eschool_id');
        courses_short_name.value = obj.value + 'courses';
    }
</script>

<form enctype="multipart/form-data" id="newInstitutionForm" name="newInstitutionForm" action="/institution/create" method="POST">
  <ul>
      <?php
      // display all form errors (if any)
      foreach ($formErrors as $errorName => $error)
      {
          echo '<li id="' . $errorName . 'Error" class="gcErrors">' . $error . '</li>';
      }
      ?>
  </ul>
  <table id="eschool_form">
      <?php echo $newInstitutionForm->renderHiddenFields(); ?>
      <tr>
          <td colspan="4" class="form_section_label">
  	  	<strong>Platform Information</strong>
  	  </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['institution_type']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['institution_type']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['institution_type']->render(); ?>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['logo']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['logo']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['logo']->render(array('size' => '9')); ?>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['full_name']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['full_name']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['full_name']->render(); ?>
          </td>
          <td class="form_text">
              Please choose the full name of your trial Stratus Platform.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['short_name']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['short_name']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['short_name']->render(); ?>
          </td>
          <td class="form_text">
              Please choose the unique short name of your Stratus Platform.
              The URL https://<b>shortname</b>.globalclassroom.us will act as the <b>homepage</b> of your
              Stratus Platform. This information cannot be changed after creation of the Platform, so please
              choose this name carefully.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['default_eschool_id']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['default_eschool_id']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['default_eschool_id']->render(); ?>
          </td>
          <td class="form_text">
              Please choose the unique short name of the course URL for your Stratus Platform.
              The URL https://<b>shortname</b>.globalclassroom.us will be <b>where courses are located</b> for your 
              Stratus Platform.  This information cannot be changed after creation of the Platform, so please
              choose this name carefully.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['admin_username']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['admin_username']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['admin_username']->render(); ?>
          </td>
          <td class="form_text">
              Please choose the username for your administrator account on your trial Stratus Platform.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['admin_password_user']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['admin_password_user']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['admin_password_user']->render(); ?>
          </td>
          <td class="form_text">
              Please choose the password for your administrator account on your trial Stratus Platform.
              This password must be at least 6 characters, and include at least one numeric digit.
          </td>
      </tr>
       <tr>
          <td>
            <?php echo $newInstitutionForm['admin_password_verify']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['admin_password_verify']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['admin_password_verify']->render(); ?>
          </td>
          <td class="form_text">
              Please re-enter your administrator password for verification.
          </td>
      </tr>
      <tr>
          <td colspan="4" class="form_section_label">
  	  	<strong>Public Contact Information</strong>
  	  </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['first_name_2']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['first_name_2']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['first_name_2']->render(); ?>
          </td>
          <td>
              This contact information will be displayed publically to users on your Stratus Platform.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['last_name_2']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['last_name_2']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['last_name_2']->render(); ?>
          </td>
      </tr>
      <tr>
        <td>
            <?php echo $newInstitutionForm['email_2']->renderError(); ?>
        </td>
        <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['email_2']->renderLabel(); ?>
        </td>
        <td>
            <?php echo $newInstitutionForm['email_2']->render(); ?>
        </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['phone1_2']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['phone1_2']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['phone1_2']->render(); ?>
          </td>
          <td class="form_text">
              Please provide us your telephone number(s) (include area code).
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $newInstitutionForm['phone2_2']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $newInstitutionForm['phone2_2']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $newInstitutionForm['phone2_2']->render(); ?>
          </td>
      </tr>
      <tr><td colspan="3">&nbsp</td></tr>
      <tr>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td>
              <a href="" id="lightboxlinkeschool" rel="lightbox2" class="lbOn">
                  <input type="button" class="button" value="Create your Platform" />
              </a>
          </td>
      </tr>
  </table>
  <div id="lightbox2" name="lightbox2" class="leightbox">
    <h1>Creating Your Trial</h1>
    <p>We are attempting to create your trial Stratus Platform using the information you supplied. This process may take a few minutes to complete. You will be redirected when your Platform is ready. Thank you for creating your Platform with Global Classroom.</p>
    <div id="loader">
        <img src="<?php print GcrInstitutionTable::getHome()->getUrl(); ?>/lib/leightbox/loader.gif" />
    </div>
  </div>
</form>


