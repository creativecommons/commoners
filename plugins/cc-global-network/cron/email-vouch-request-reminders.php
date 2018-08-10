<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A WordPress cron task to email Vouching Request reminders to Vouchers,
  and automatically close the requests if they do not eventually respond.

  These emails go *to* vouchers *about* their pending requests,
  AND *to* applicants *when* the voucher gets the close email.

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
    // If re-implementing this, make sure to check for latest reminder first so
    // that the user always gets only the latest reminder for a given time
    // period.
    switch ( $day ) {
    case CCGN_VOUCH_REQUEST_REMINDER_DAY_FINAL_REMINDER:
        ccgn_registration_email_to_voucher (
            $applicant_id,
            $voucher_id,
            'ccgn-email-vouch-request-final-reminder'
        );
        break;
    case CCGN_VOUCH_REQUEST_REMINDER_DAY_SECOND_REMINDER:
        ccgn_registration_email_to_voucher (
            $applicant_id,
            $voucher_id,
            'ccgn-email-vouch-request-second-reminder'
        );
        break;
    case CCGN_VOUCH_REQUEST_REMINDER_DAY_FIRST_REMINDER:
        ccgn_registration_email_to_voucher (
            $applicant_id,
            $voucher_id,
            'ccgn-email-vouch-request-first-reminder'
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
        // Notify the applicant that the voucher could not vouch for them.
        ccgn_registration_email_voucher_cannot (
            $applicant_id,
            $voucher_id
        );
    }
}

// Send reminders to those that need them, close those that haven't responded

function ccgn_email_vouch_request_reminders () {
    $today = new DateTime( 'today' );
    // There may be a more efficient SQL query but I don't want to be
    // dependent on database structure - RobM.
    $applicants = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    foreach ( $applicants as $applicant_id ) {
        $vouchers_form = ccgn_application_vouchers ( $applicant_id );
        $request_date = new DateTime(
            ccgn_entry_created_or_updated(
                $vouchers_form
            )
        );
        # php 5.2.2 or later for DateTime comparisons....
        $day = ($today->diff($request_date))->days;
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

// Debugging but it may be useful so leaving it in for now

function _ccgn_check_autos () {
    $autos = GFAPI::get_entries(
        44,
        array(
            'field_filters' => array(
                array(
                    'key' => '4',
                    'value' => 'AUTOMATICALLY CLOSED: NO RESPONSE'
                )
            )
        ),
        null,
        array( 'offset' => 0, 'page_size' => 200 )
    );

    for($i = 0; $i < count($autos); $i++){
        $search_criteria = array();
        $search_criteria['field_filters'][]
            = array(
                'key' => 'created_by',
                'value' => $autos[$i]['7']
            );
        $request = GFAPI::get_entries(41, $search_criteria)[0];
        $a = new DateTime(ccgn_entry_created_or_updated($request));
        $b = new DateTime(ccgn_entry_created_or_updated($autos[$i]));
        $day = ($b->diff($a))->days;
        echo $day . "\t";
        //if( $day <= 1) {
        //            echo json_encode($request);
        echo $autos[$i]['id']
            . ': '
            . ccgn_entry_created_or_updated($autos[$i])
            . ' - '
            . $request['id']
            . ': '
            . ccgn_entry_created_or_updated($request)
            . "\n";
        //}
        //            echo json_encode($autos[$i]) . "\n";
    }
}
