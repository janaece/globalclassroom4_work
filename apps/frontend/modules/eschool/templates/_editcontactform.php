<form enctype="multipart/form-data" id="eschoolForm" name="eschoolForm" action="/eschool/updateContact" method="POST">
 <?php
	  // display all form errors (if any)
	  foreach ($formErrors as $errorName => $error)
	  {
	  	echo '<div id="' . $errorName . 'Error">' . $error . '</div>';
	  }
  ?>
  <table id="eschool_form">
  <?php echo $editContactForm->renderHiddenFields(); ?>
  	<tr>
  	  <td colspan="3" class="form_section_label">
  	  	<strong>Primary Platform Contact</strong>
  	  </td>
  	</tr>
    <tr>
      <td>
      	<?php echo $editContactForm['first_name']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['first_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['first_name']->render(); ?>
      </td>  
      <td class="form_text" rowspan="9">
      	This information is for the primary contact of your Stratus Platform.
      	The email and phone number given in this section will appear in the contact box of your Platform that appears on your homepage.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['last_name']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['last_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['last_name']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['street1']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['street1']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['street1']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['street2']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['street2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['street2']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['city']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['city']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['city']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['state']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['state']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['state']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['zipcode']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['zipcode']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['zipcode']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['phone']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['phone']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['phone']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $editContactForm['email']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $editContactForm['email']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $editContactForm['email']->render(); ?>
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