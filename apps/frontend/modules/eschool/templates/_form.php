<!-- CSS -->
<?php global $CFG; ?>
<link rel="stylesheet" href="/lib/leightbox/css/screen.css" media="screen,projection" type="text/css" />

<!-- JavaScript -->
<script type="text/javascript" src="/lib/leightbox/scripts/prototype.js"></script>
<script type="text/javascript" src="/lib/leightbox/scripts/lightbox.js"></script>

<form enctype="multipart/form-data" id="eschoolForm" name="eschoolForm" action="/eschool/create" method="POST">
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
  	<?php echo $eschoolForm->renderHiddenFields(); ?>
  	<tr>
  	   <td colspan="3" class="form_section_label">
  	  	<strong>Catalog Information</strong>
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
      	Enter the full name of your Platform Catalog as you want it to appear online.
      </td>      
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['eschool_type']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['eschool_type']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['eschool_type']->render(); ?>
      </td>
    </tr>
    <tr>
      <td>
      	<?php echo $eschoolForm['short_name']->renderError(); ?>
      </td>    
      <td class="symfonyFormLabel">
      	<?php echo $eschoolForm['short_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $eschoolForm['short_name']->render(); ?>
      </td>
      <td class="form_text">
      	The URL is the prefix which will come before ".globalclassroom.us", e.g. "http://myschool.globalclassroom.us"<br/>
		<span class="cautiontext">
			The URL may only contain letters and numbers, e.g. no dashes, periods, etc.<br/>
			A fee will be charged for changing URLs.
		</span>
      </td>      
    </tr>
    <tr>
      <td>
      <br/>
      </td>
    </tr>
    <tr>
       <td colspan="3" class="form_section_label">
      	<strong>Location</strong>
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
      	The information in this section will appear in the contact box of your Catalog. Please keep in mind this contact
		information will be visible to users visiting your Catalog online. This information can be changed.
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
      	<strong>Main Contact Information</strong>
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
      	This section should be filled out for the person who will be the main contact of the Catalog. This information will
		show up in the contact box of your Catalog. This information can also be changed.
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
      	<strong>Master Admin Information</strong>
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
      	The contact information in this section will only be used by Global Classroom in the event that we need to contact
		the master admin. This information is not intended to be changed.
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
    <tr><td colspan="3">&nbsp</td></tr>
    <tr>
      <td>&nbsp</td>
      <td>&nbsp</td>
      <td>
      	<a href="" id="lightboxlinkeschool" rel="lightbox2" class="lbOn">
        	<input class="button" type="button" value="Create a Catalog" />
        </a>
      </td>
    </tr>
  </table>
  <div id="lightbox2" name="lightbox2" class="leightbox">
	
	<h1>Creating Your Catalog</h1>

	<p>We are attempting to create your Platform Catalog using the information you supplied. This process may take a few minutes to complete. You will be redirected to your Platform Dashboard when it is ready. Thank you for using Global Classroom.</p>

	<div id="loader">
		<img src="/lib/leightbox/loader.gif" />
	</div>
  </div>

</form>