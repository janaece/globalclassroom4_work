<div style="text-align:center">
    <?php global $CFG; ?>
    <h3>
        Course repair process on <?php print $course->getObject()->fullname . ' (' . $course->getObject()->shortname . ')'; ?> complete.
    </h3>
    <div id="repairLog">
        <?php print $repair_count_question; ?> repair(s) were made to questions.
    </div>
    <div id="repairLog">
        <?php print $repair_count_resource; ?> repair(s) were made to resources.
    </div>
    <div id="repairLog">
        <?php print $repair_count_section; ?> repair(s) were made to sections.
    </div>
    <div id="repairLog">
        <?php print $repair_count_label; ?> repair(s) were made to labels.
    </div>
    <div id="repairLog">
        <?php print $repair_count_event; ?> repair(s) were made to events.
    </div>
    <br />
    <a href="<?php print $CFG->current_app->getAppUrl() . '/course/view.php?id=' . $course->getObject()->id; ?>">
        <button>
            Continue
        </button>
    </a>
</div>