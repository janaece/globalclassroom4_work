<?php global $CFG; ?>
<div id="error_container">
    <h2>Error Page</h2>
    <p style="color:red;"><?php print $error ?></p>
    <p>We apologize for your inconvenience. If you need help with this problem, please contact:</p>
    <p>
        <strong>Russell Willis</strong>
        <br />Vice President
        <br />Global Classroom
        <br />(866) 535-3772
        <br /><a href="mailto:rwillis@globalclassroom.us" target="_blank">rwillis@GlobalClassroom.us</a>
    </p>
    <a href="<?php print $CFG->current_app->getAppUrl(); ?>"><button>Continue</button></a>
    <br/><br/>
</div>