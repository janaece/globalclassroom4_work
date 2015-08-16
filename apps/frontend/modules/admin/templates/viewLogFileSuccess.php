<?php
global $CFG;
print '<h3>Contents of ' . $log_file->getLogFileLocation() .
        ' (last ' . $log_file->getNumLines() . ' lines)' .
        ' - <a href="' . $CFG->current_app->getUrl() .
        '/admin/eschool">Return To Admin Page</a></h3>';

foreach ($log_file->getLogFile() as $line)
{
    print $line . '<br />';
}
print '<br /><b><a href="' . $CFG->current_app->getUrl() .
        '/admin/eschool">Return To Admin Page</a></b>';
?>

