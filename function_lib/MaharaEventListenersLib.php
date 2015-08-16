<?php

/**
 * These functions are designed to be called as part of mahara's
 * event subscription model. To add a subscription, create the function
 * here and then add a record in the mhr_event_subscription to link the
 * mahara event to the function.
 *
 * @author Ron Stewart
 * created: 04/07/2012
 */

// When Mahara deletes a user, we want all attached Moodles
// to delete that user's associated roaming accounts.
function gcr_delete_user_event_listener($eventdata)
{
    global $CFG;
    $mhr_user = $CFG->current_app->getUserById($eventdata['id']);
    GcrMdlWebServices::deleteUser($mhr_user);
}
// When Mahara updates a user, this function sets access to
// attached catalogs based upon the access settings for that platform
function gcr_update_user_event_listener($eventdata)
{
    global $CFG;
    $mhr_user = $CFG->current_app->getUserById($eventdata['id']);
    if ($mhr_user)
    {
        $mhr_user->setAccessForMnetEschools();
    }
}
?>
