<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A WordPress cron task to email Applicants who have not yet updated their
  Vouching Requests after a previous Voucher declined to Vouch for them every
  CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS days.
*/

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

// Be careful changing this, you may send a reminder sooner than expected
define( 'CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS', 10 );

////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

function ccgn_should_remind_applicant_to_update_vouchers (
    $today,
    $vouch_cannot_date
) {
    # php 5.2.2 or later for DateTime comparisons....
    $num_days_elapsed = $today->diff($vouch_cannot_date)->days;
    return ( $num_days_elapsed > 0 )
        && ( ( $num_days_elapsed % CCGN_REMIND_VOUCHER_AFTER_DAYS )  === 0 );
}

// Send reminders to those that need them

function ccgn_email_update_vouchers_reminders () {
    $today = new DateTime( 'today' );
    $applicants = ccgn_applicant_with_cannot_vouches_older_than (
        CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS
    );
    foreach ( $applicants as $applicant_id => $dates ) {
        // Dates are unsorted, so sort them
        sort($dates);
        $earliest_cannot = new DateTime( $dates[ 0 ] );
        if ( ccgn_should_remind_applicant_to_update_vouchers (
            $today,
            $earliest_cannot
        ) ) {
            ccgn_registration_email_voucher_cannot_reminder( $applicant_id );
        }
    }
}

function ccgn_schedule_email_upate_vouchers_reminders () {
    if (! wp_next_scheduled ( 'ccgn_email_update_vouchers_reminders' )) {
        wp_schedule_event(
            time(),
            'daily',
            'ccgn_email_update_vouchers_reminders'
        );
    }
}

function ccgn_schedule_remove_email_update_vouchers_reminders () {
    wp_clear_scheduled_hook( 'ccgn_email_update_vouchers_reminders' );
}