<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A WordPress cron task to email Vouching Request reminders to Vouchers,
  and automatically close the requests if they do not eventually respond.
*/

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

define('CCGN_VOUCH_REQUEST_REMINDER_DAY_FIRST_REMINDER', 1);
define('CCGN_VOUCH_REQUEST_REMINDER_DAY_SECOND_REMINDER', 3);
define('CCGN_VOUCH_REQUEST_REMINDER_DAY_FINAL_REMINDER', 5);
define('CCGN_VOUCH_REQUEST_REMINDER_DAY_CLOSE', 6);

////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

function ccgn_email_vouch_request_reminder_send (
    $voucher_id,
    $applicant_id,
    $day
) {
    switch ( $day ) {
    case CCGN_VOUCH_REQUEST_REMINDER_DAY_FIRST_REMINDER:
        ccgn_registration_email_to_voucher (
            $applicant_id,
            $voucher_id,
            'ccgn-email-vouch-request-first-reminder'
        );
        break;
    case CCGN_VOUCH_REQUEST_REMINDER_DAY_SECOND_REMINDER:
        ccgn_registration_email_to_voucher (
            $applicant_id,
            $voucher_id,
            'ccgn-email-vouch-request-second-reminder'
        );
        break;
    case CCGN_VOUCH_REQUEST_REMINDER_DAY_FINAL_REMINDER:
        ccgn_registration_email_to_voucher (
            $applicant_id,
            $voucher_id,
            'ccgn-email-vouch-request-final-reminder'
        );
        break;
    default:
        if ( $day >= CCGN_VOUCH_REQUEST_REMINDER_DAY_CLOSE ) {
            ccgn_registration_email_to_voucher (
                $applicant_id,
                $voucher_id,
                'ccgn-email-vouch-request-close-reminder'
            );
        }
        break;
    }
}

function ccgn_email_vouch_request_reminder_maybe_close (
    $voucher_id,
    $applicant_id,
    $day
) {
    if ( $day >= CCGN_VOUCH_REQUEST_REMINDER_DAY_CLOSE ) {
        // This function does take applicant/voucher in this order
        ccgn_vouching_request_spoof_cannot ( $applicant_id, $voucher_id );
    }
}

// Send reminders to those that need them, close those that haven't responded

function ccgn_email_vouch_request_reminders () {
    $today = new DateTime( 'today' );
    // There may be a more efficient SQL query but I don't want to be
    // debendent on database structure - RobM.
    $applicants = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    foreach ( $applicants as $applicant_id ) {
        $vouchers_form = ccgn_application_vouchers ( $applicant_id );
        $request_date = new DateTime($vouchers_form[ 'date_created' ]);
        # php 5.2.2 or later for DateTime comparisons....
        $day = $today->diff($request_date)->days;
        $vouchers = ccgn_application_vouchers_users_ids ( $applicant_id );
        foreach ( $vouchers as $voucher_id ) {
            if ( ccgn_vouching_request_open ( $applicant_id, $voucher_id ) ) {
                 ccgn_email_vouch_request_reminder_maybe_close (
                    $voucher_id,
                    $applicant_id,
                    $day
                );
                // This will notify the voucher if the request was closed
                 ccgn_email_vouch_request_reminder_send (
                    $voucher_id,
                    $applicant_id,
                    $day
                 );
            }
        }
    }
}

function ccgn_schedule_email_vouch_request_reminders () {
    if (! wp_next_scheduled ( 'ccgn_email_vouch_request_reminders_event' ) ) {
        wp_schedule_event(
            time(),
            'daily',
            'ccgn_email_vouch_request_reminders_event'
        );
    }
}

function ccgn_schedule_remove_email_vouch_request_reminders () {
    wp_clear_scheduled_hook( 'ccgn_email_vouch_request_reminders_event' );
}