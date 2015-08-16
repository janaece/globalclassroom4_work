<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php global $CFG; ?>
<!-- CSS -->
<link rel="stylesheet" href="/lib/leightbox/css/screen.css" media="screen,projection" type="text/css" />

<!-- JavaScript -->
<script type="text/javascript" src="/lib/leightbox/scripts/prototype.js"></script>
<script type="text/javascript" src="/lib/leightbox/scripts/lightbox.js"></script>

<form action="/purchase/create" id="purchaseForm" name="purchaseForm" method="post">
  <?php
  if (count($formErrors) > 0)
  {
      print '<ul>';
      // display all form errors
      foreach ($formErrors as $errorName => $error)
      {
          if ($error != '')
          {
              print '<li id="' . $errorName . 'Error" class="gcErrors">' . $error . '</li>';
          }
      }
      print '</ul>';
  }
  ?>
  <table>
    <?php echo $form->renderHiddenFields(); ?>
     <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['first_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['first_name']->render(); ?>
      </td>  
      <td>
      	<?php echo $form['first_name']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['last_name']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['last_name']->render(); ?>
      </td>
      <td>
      	<?php echo $form['last_name']->renderError(); ?>
      </td>
    </tr>
    
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['cc_number']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['cc_number']->render(); ?>
      </td>
      <td>
      	<?php echo $form['cc_number']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['cc_type']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['cc_type']->render(); ?>
      </td>
      <td>
      	<?php echo $form['cc_type']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['cc_ccv2']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['cc_ccv2']->render(); ?>
      </td>
      <td>
      	<?php echo $form['cc_ccv2']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['cc_exp_month']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['cc_exp_month']->render(); ?>
      </td>
      <td>
      	<?php echo $form['cc_exp_month']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['cc_exp_year']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['cc_exp_year']->render(); ?>
      </td>
      <td>
      	<?php echo $form['cc_exp_year']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['address']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['address']->render(); ?>
      </td>
      <td>
      	<?php echo $form['address']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['city']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['city']->render(); ?>
      </td>
      <td>
      	<?php echo $form['city']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['state']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['state']->render(); ?>
      </td>
      <td>
      	<?php echo $form['state']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['country']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['country']->render(); ?>
      </td>
      <td>
      	<?php echo $form['country']->renderError(); ?>
      </td>
    </tr>
    <tr>
      <td class="symfonyFormLabel">
      	<?php echo $form['zip']->renderLabel(); ?>
      </td>
      <td>
      	<?php echo $form['zip']->render(); ?>
      </td>
      <td>
      	<?php echo $form['zip']->renderError(); ?>
      </td>
    </tr>
    <tr><td colspan="3">&nbsp</td></tr>
    <tr>
      <td>&nbsp</td>
      <td>
        <a href="" id="lightboxlink" rel="lightbox2" class="lbOn">
        	<input class="button" type="button" value="Click Here to Purchase" />
        </a>
      </td>
      <td>&nbsp</td>
    </tr>
  </table>
  <div id="lightbox2" name="lightbox2" class="leightbox">
	
	<h1>Processing Transaction</h1>

	<p>We are attempting to process your purchase using the credit card information you have provided...</p>

	<div id="loader">
		<img src="<?php print $CFG->current_app->getUrl(); ?>/lib/leightbox/loader.gif" />
	</div>
  </div>
</form>
