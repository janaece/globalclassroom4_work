<form enctype="multipart/form-data" id="eschoolForm" name="eschoolForm" action="/eschool/update" method="POST">
  <?php
  // display all form errors (if any)
  foreach ($formErrors as $errorName => $error)
  {
  	echo '<div id="' . $errorName . 'Error">' . $error . '</div>';
  }
  ?>
  <table id="eschool_form">
    <?php echo $eschoolForm->renderHiddenFields(); ?>
    <tr>
      <td colspan="3" class="form_section_label">
      	<strong>Contact Information</strong>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['full_name']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['full_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['full_name']->render(); ?>
      </td>
      <td class="form_text">
      	Enter the full name of your Stratus Platform as you want it to appear online.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['external_url']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['external_url']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['external_url']->render(); ?>
      </td>
      <td class="form_text">
      	<span class="cautiontext">
                A fee will be charged anytime a URL is changed.
        </span>
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['visible']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['visible']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['visible']->render(); ?>
      </td>
      <td class="form_text">
      	Being "visible" means your Stratus Platform will appear in the search.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['street1']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['street1']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['street1']->render(); ?>
      </td>
      <td class="form_text" rowspan="6">
      	The fields below are for contact information and will appear in the contact box of your Stratus Platform.
      	This information is visible from your homepage.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['street2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['street2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['street2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['city']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['city']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['city']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['state']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['state']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['state']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['zipcode']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['zipcode']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['zipcode']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['country']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['country']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['country']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="3" class="form_section_label">
      	<strong>Primary Platform Contact</strong>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['first_name']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['first_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['first_name']->render(); ?>
      </td>  
      <td class="form_text" rowspan="5">
      	This information is for the primary contact of your Stratus Platform.
      	The email and phone number given in this section will appear in the contact box that appears on your homepage.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['last_name']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['last_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['last_name']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['phone1']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['phone1']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['phone1']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['phone2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['phone2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['phone2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['email']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['email']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['email']->render(); ?>
      </td>
    </tr>
    <tr>
      <td colspan="3" class="form_section_label">
      	<strong>Master Admin Contact</strong>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['first_name_2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['first_name_2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['first_name_2']->render(); ?>
      </td>
      <td class="form_text" rowspan="5">
      	This information applies to the master admin of your Stratus Platform.
      	This information is used by Global Classroom to contact the main person in charge of your Stratus Platform.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['last_name_2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['last_name_2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['last_name_2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['phone1_2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['phone1_2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['phone1_2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['phone2_2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['phone2_2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['phone2_2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['email_2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['email_2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['email_2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	&nbsp
      </td>
    </tr>
    <tr>
      <td>&nbsp</td>
      <td>&nbsp</td>
      <td>
      	<input class="eschoolSubmit button" type="submit" value="Submit" />
      </td>
    </tr>
  </table>
</form>