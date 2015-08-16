<?php
	// This will send classroom related information to an email address defined by the teacher
	require_once('../config.php');
	global $CFG;
?>
<?php print $this->params['header_image']; ?>
<p>
	You have been invited to a Global Classroom course by an instructor!  This email provides all the details you need to register and 
	login to a Platform powered by Global Classroom.  If you do not have a username or password for this teacher/course's Platform, you
	will need to register.  Please follow the steps below to accept your course invitation.
	<br />
	<br />
	1. You first need an account on the Platform to take a course.  If you already have an account, skip to step 2 below.  Clicking on
	the link will send you to the signup page for the Platform where your course should be.  Once you fill out all the required
	information, you will be sent an confirmation email.  Click the link within the email to confirm your new account registration.  
	Once you have confirmed, follow step two below.
	<br />
	<br />
	<?php print $CFG->current_app->getInstitution()->getAppUrl() ?>register.php
	<br />
	<br />
	2. Supplied below is the course and teacher information.  If you click on the course link, you will be presented with an option 
	to enter an enrollment key (if provided) and an enroll button.  Click this button and you will be enrolled into the course!
</p>
<p>
	Course Name: <?php print $this->params['fullname']; ?>
	<br />
	Course Short Name: <?php print $this->params['shortname']; ?>
	<br />
	<?php if (!empty($this->params['password']))
	{?>
	Enrollment Key: <?php print $this->params['password']; ?><br /><?php
	}?>
	Course Link: <?php print $CFG->wwwroot; ?>/course/view.php?id=<?php print $this->params['courseid']; ?>
	<br />
</p>
<p>
	Teacher Information:
	<br />
	<?php
	// Begin somewhat mind-numbing code to dynamically display teacher information, especially if there are
	// multiple teachers in a course.
	
	// tracking the number of teachers in a course ahead of time helps
	$this->number_of_teachers = $this->params['numteachers'];
	
	// This gives us the total number of teacher entries needed to use the "for" loop
	$this->teacher_entries = $this->number_of_teachers * 3;

	//Attach html tags for the source of the teacher images, keeps image small and floats it to the left of the name and email
	for ($counter = 0; $counter < $this->teacher_entries; $counter+=3)
	{
		$this->params[$counter] = '<img width="50px" height="50px" style="float:left;padding:5px;" src="' . $this->params[$counter] . '&type=profileicon" alt="teacherpic">';
	}
	
	// Print out teacher information
	
	// counter to separate out individual teachers
	$everythird = 3;
	for ($counter = 0; $counter < $this->teacher_entries; $counter++)
	{	
		echo $this->params[$counter];
		echo "<br />";
		
		$everythird++;
		if ($everythird % 3 == 0)
		{
			// If there has been three printouts, then a new teacher is inserted.  This prevents image overlaps.
			echo "<br />";	
		}
	}?>
</p>
<p>
	If you have any questions or problems with enrolling into this course, please contact us at rwillis@globalclassroom.us.
	<br />
	Thank you for using Global Classroom!
</p>
<p>
	<?php print $this->params['powered_by_GC']; ?>
</p>