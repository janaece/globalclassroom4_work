<?php global $CFG; ?>
<?php print $this->params['header_image']; ?>
<p>
	You have been invited to a Global Classroom course!  This email provides all the details you need to register and
	login to a Platform powered by Global Classroom.  If you do not have a username or password you will need to register.
    Please follow the steps below to accept your course invitation.
	<br />
	<br />
	1. You first need an account on the Platform to take a course.  If you already have an account, skip to step 2 below.  
    Clicking on the link will send you to the signup page for the Platform where your course should be.  Once you
    fill out all the required information, you will be sent an confirmation email.  Click the link within the
    email to confirm your new account registration. Once you have confirmed, follow step two below.
	<br />
	<br />
	<?php print $CFG->current_app->getInstitution()->getAppUrl() ?>register.php
	<br />
	<br />
	2. Supplied below is the course information.  If you click on the course link, you will be presented 
    with an option to enter an enrollment key (if provided) and an enroll button.  Click this button and you will
    be enrolled into the course!
</p>
<p>
	Course Name: <?php print $this->params['fullname']; ?>
	<br />
	Course Short Name: <?php print $this->params['shortname']; ?>
	<br />
	<?php if (isset($this->params['password']))
	{?>
        Enrollment Key: <?php print $this->params['password']; ?><br /><?php
	}?>
    <?php $eschool = Doctrine::getTable('GcrEschool')->findOneById($this->params['eschoolid']); ?>
	Course Link: <?php print $eschool->getAppUrl(); ?>/course/view.php?id=<?php print $this->params['courseid']; ?>
	<br />
</p>
<p>
	If you have any questions or problems with enrolling into this course, please contact us at rwillis@globalclassroom.us.
	<br />
	Thank you for using Global Classroom!
</p>
<p>
	<?php print $this->params['powered_by_GC']; ?>
</p>