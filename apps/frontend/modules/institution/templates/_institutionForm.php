<form enctype="multipart/form-data" id="institutionForm" name="institutionForm" action="/institution/process" method="POST">
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
      <?php echo $institutionForm->renderHiddenFields(); ?>
      <tr>
          <td colspan="3" class="form_section_label">
  	  	<strong>Your Information</strong>
  	  </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['first_name']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['first_name']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['first_name']->render(); ?>
          </td>
          <td class="form_text">
              Please provide us your full name.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['last_name']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['last_name']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['last_name']->render(); ?>
          </td>
      </tr>
      <tr>
        <td>
            <?php echo $institutionForm['email']->renderError(); ?>
        </td>    
        <td class="symfonyFormLabel">
            <?php echo $institutionForm['email']->renderLabel(); ?>
        </td>
        <td>
            <?php echo $institutionForm['email']->render(); ?>
        </td>
        <td class="form_text">
            Please enter the email address you would like to use for your Stratus account.
            We require to validate this email address in order to create your trial Stratus Platform.
        </td> 
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['phone1']->renderError(); ?>
          </td>    
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['phone1']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['phone1']->render(); ?>
          </td>
          <td class="form_text">
              Please provide us your telephone number(s) (include area code).
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['phone2']->renderError(); ?>
          </td>    
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['phone2']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['phone2']->render(); ?>
          </td>
      </tr>
      <tr>
          <td colspan="3" class="form_section_label">
              <strong>Address</strong>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['street1']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['street1']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['street1']->render(); ?>
          </td>
          <td class="form_text" rowspan="6">
            The information in this section will appear in the contact box of your collection of Stratus courses.
            Please keep in mind this contact information will be visible to users visiting your courses.
            This information can be changed.
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['street2']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['street2']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['street2']->render(); ?>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['city']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['city']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['city']->render(); ?>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['state']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['state']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['state']->render(); ?>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['zipcode']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['zipcode']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['zipcode']->render(); ?>
          </td>
      </tr>
      <tr>
          <td>
            <?php echo $institutionForm['country']->renderError(); ?>
          </td>
          <td class="symfonyFormLabel">
            <?php echo $institutionForm['country']->renderLabel(); ?>
          </td>
          <td>
            <?php echo $institutionForm['country']->render(); ?>
          </td>
      </tr>
      <tr><td colspan="3">&nbsp</td></tr>
      <tr>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td>
            <input type="submit" class="button" value="Create a Platform" />
          </td>
      </tr>
  </table>
</form>