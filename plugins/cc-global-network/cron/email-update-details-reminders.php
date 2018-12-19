<?php

defined('ABSPATH') or die('No script kiddies please!');

/*
  A WordPress cron task to email Applicants who have not yet updated their
  details meaning updated information already requested has been not yet submitted even after
  CCGN_REMIND_UPDATE_DETAILS_AFTER_DAYS days.

  These emails go *to* applicants about to update their details in the application form.

  This code may close an application!
 */

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

// Be careful changing this value, you may send a reminder sooner than expected
// Also beware any knock-on effect on CCGN_CLOSE_UPDATE_VOUCHERS_AFTER_DAYS
define('CCGN_FIRST_REMINDER_UPDATE_DETAILS_AFTER_DAYS', 7);
define('CCGN_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS', CCGN_FIRST_REMINDER_UPDATE_DETAILS_AFTER_DAYS + 5);
//define('CCGN_SEND_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS', CCGN_SEND_REMINDER_UPDATE_DETAILS_AFTER_DAYS + 3 );
define( 'CCGN_CLOSE_UPDATE_DETAILS_AFTER_DAYS', CCGN_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS + 3 );

////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

// Close overdue vouchers

function ccgn_close_update_details_applicant($applicant_id)
{
    _ccgn_application_delete_entries_created_by($applicant_id);
    delete_user_meta($applicant_id, CCGN_APPLICATION_TYPE);
    delete_user_meta($applicant_id, CCGN_APPLICATION_STATE);
    delete_user_meta($applicant_id, CCGN_USER_IS_AUTOVOUCHED);

    $delete = wp_delete_user($applicant_id);
}

// Send reminders to those that need them

function ccgn_email_update_details_reminders()
{
    $now = new DateTime('now');
    $applicants = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_UPDATE_DETAILS
    );
    foreach ($applicants as $applicant_id) {
        $days_in_state = ccgn_days_since_state_set($applicant_id, $now);
        if ($days_in_state > CCGN_CLOSE_UPDATE_DETAILS_AFTER_DAYS) {
            ccgn_close_update_details_applicant($applicant_id);
        } elseif ( ($days_in_state > CCGN_FIRST_REMINDER_UPDATE_DETAILS_AFTER_DAYS) && ($days_in_state <= CCGN_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS) ) {
            // Send first reminder
            ccgn_registration_email_update_details_first_reminder($applicant_id);
        } elseif ( ($days_in_state > CCGN_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS) && ($days_in_state <= CCGN_CLOSE_UPDATE_DETAILS_AFTER_DAYS) ) {
            // Send second reminder
            ccgn_registration_email_update_details_second_reminder($applicant_id);
        }
    }
}

function ccgn_schedule_email_upate_details_reminders()
{
    if (!wp_next_scheduled('ccgn_email_update_details_reminders_event')) {
        wp_schedule_event(
            time(),
            'daily',
            'ccgn_email_update_details_reminders_event'
        );
    }
}

function ccgn_schedule_remove_email_update_details_reminders()
{
    wp_clear_scheduled_hook('ccgn_email_update_details_reminders_event');
}