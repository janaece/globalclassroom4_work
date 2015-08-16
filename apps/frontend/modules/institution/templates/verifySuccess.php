<h2>Verification</h2>
<p>
    An email has been sent to <?php print $application->getContactObject()->getEmail(); ?>
    from <?php print gcr::gcEschoolNotification?>. Please click the link in this message
    to verify the email address for your new Stratus Platform.
</p>
<p>
    If you would like to us to resend the verification email, click
    <a href="<?php print GcrInstitutionTable::getHome()->getUrl() .
            '/institution/sendVerificationEmail?id=' . $application->getId(); ?>">here</a>.
</p>