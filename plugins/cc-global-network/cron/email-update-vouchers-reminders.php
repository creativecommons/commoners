<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A WordPress cron task to email Applicants who have not yet updated their
  Vouching Requests after a previous Voucher declined to Vouch for them every
  CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS days.

  These emails go *to* applicants about their voucher choices.

  This code may close an application!
*/

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

// Be careful changing this value, you may send a reminder sooner than expected
// Also beware any knock-on effect on CCGN_CLOSE_UPDATE_VOUCHERS_AFTER_DAYS
define( 'CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS', 7 );

define( 'CCGN_CLOSE_UPDATE_VOUCHERS_AFTER_DAYS',
        CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS + 3 );

////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

// Close overdue vouchers

function ccgn_close_vouchers ( $applicant_id ) {
    ccgn_user_level_set_didnt_update_vouchers ( $applicant_id );
    ccgn_registration_email_voucher_cannot_closed( $applicant_id );
}

function ccgn_days_in_state ( $applicant_id, $now ) {
    $state = ccgn_registration_user_get_stage_and_date ( $applicant_id );
    // Calculate days in the state
    $state_date = new DateTime($state['date']);
    return $state_date->diff($now)->days;
}

// Send reminders to those that need them

function ccgn_email_update_vouchers_reminders () {
    $now = new DateTime('now');
    $applicants = ccgn_applicant_ids_with_state (
        CCGN_APPLICATION_STATE_UPDATE_VOUCHERS
    );
    foreach ( $applicants as $applicant_id) {
        $days_in_state = ccgn_days_in_state ( $applicant_id, $now );
        if ( $days_in_state > CCGN_CLOSE_UPDATE_VOUCHERS_AFTER_DAYS ) {
            ccgn_close_vouchers ( $applicant_id );
        } elseif ( $days_in_state > CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS ) {
            ccgn_registration_email_voucher_cannot_reminder( $applicant_id );
        }
    }
}

function ccgn_schedule_email_upate_vouchers_reminders () {
    if (! wp_next_scheduled ( 'ccgn_email_update_vouchers_reminders_event' )) {
        wp_schedule_event(
            time(),
            'daily',
            'ccgn_email_update_vouchers_reminders_event'
        );
    }
}

function ccgn_schedule_remove_email_update_vouchers_reminders () {
    wp_clear_scheduled_hook( 'ccgn_email_update_vouchers_reminders_event' );
}