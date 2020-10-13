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
define('CCGN_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS', 7);
define( 'CCGN_CLOSE_UPDATE_DETAILS_AFTER_DAYS', 10 );

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

function ccgn_update_details_set_first_reminder( $applicant_id ) {
    ccgn_registration_email_update_details_first_reminder($applicant_id);
    $update_details_meta = array(
        'state' => 'first-reminder',
        'updated' => 0,
        'date' => date('Y-m-d H:i:s', strtotime('now')),
        'done' => true
    );
    update_user_meta( $applicant_id, 'ccgn_applicant_update_details_state', $update_details_meta );
}

function ccgn_update_details_set_second_reminder( $applicant_id ) {
    ccgn_registration_email_update_details_second_reminder($applicant_id);
    $update_details_meta = array(
        'state' => 'second-reminder',
        'updated' => 0,
        'date' => date('Y-m-d H:i:s', strtotime('now')),
        'done' => true
    );
    update_user_meta($applicant_id, 'ccgn_applicant_update_details_state', $update_details_meta);
}

// Send reminders to those who need them

function ccgn_email_update_details_reminders()
{
    $now = new DateTime('now');
    $applicants = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_UPDATE_DETAILS
    );
    foreach ($applicants as $applicant_id) {
        $now = new DateTime( 'now' );
        $status = get_user_meta($applicant_id, 'ccgn_applicant_update_details_state', true);
        if (!empty($status)) {
            $state_date = new DateTime($status['date']);
        } else {
            $state_date = get_user_meta($applicant_id, CCGN_APPLICATION_STATE_DATE, true);
        }
        $days_in_state = $state_date->diff($now)->days;
        $current_state = $status['state'];
        if ( ( ($current_state == 'none') || (empty($current_state))) && ($days_in_state < CCGN_FIRST_REMINDER_UPDATE_DETAILS_AFTER_DAYS) ) {
            ccgn_update_details_set_first_reminder($applicant_id);
        } elseif ( ( ($current_state == 'first-reminder') ) && ($days_in_state < CCGN_SECOND_REMINDER_UPDATE_DETAILS_AFTER_DAYS) ) {
            ccgn_update_details_set_second_reminder($applicant_id);
        } elseif ( $days_in_state > CCGN_CLOSE_UPDATE_DETAILS_AFTER_DAYS )  {
            if ( ($current_state == 'second-reminder') && ($status['done']) ) {
                ccgn_close_update_details_applicant($applicant_id);
            }
            elseif ( $status['state'] == 'none' ) {
                ccgn_update_details_set_first_reminder($applicant_id);
            } elseif ( $status['state'] == 'first-reminder' ) {
                ccgn_update_details_set_second_reminder($applicant_id);
            } else {
                //update user status date
                ccgn_update_details_set_first_reminder($applicant_id);
                update_user_meta(
                    $applicant_id,
                    CCGN_APPLICATION_STATE_DATE,
                    date('Y-m-d H:i:s', strtotime('now'))
                );
            }
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
