<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A WordPress cron task to email Vouching Request reminders to Vouchers every
  CCGN_REMIND_VOUCHER_AFTER_DAYS days.
*/

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

// Be careful changing this, you may send a reminder sooner than expected
define( 'CCGN_REMIND_VOUCHER_AFTER_DAYS', 10 );

////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

function ccgn_should_remind_voucher ( $today, $vouch_request_date ) {
    # php 5.2.2 or later for DateTime comparisons....
    $num_days_elapsed = $today->diff($vouch_request_date)->days;
    return ( $num_days_elapsed > 0 )
        && ( ( $num_days_elapsed % CCGN_REMIND_VOUCHER_AFTER_DAYS )  === 0 );
}

// Send reminders to those that need them

function ccgn_email_vouch_request_reminders () {
    $today = new DateTime( 'today' );
    $vouchers = ccgn_members_vouchers_with_requests_older_than (
        CCGN_REMIND_VOUCHER_AFTER_DAYS
    );
    foreach ( $vouchers as $voucher_id => $dates ) {
        // Dates are unsorted, so sort them
        sort($dates);
        $earliest_request = new DateTime( $dates[ 0 ] );
        if ( ccgn_should_remind_voucher ( $today, $earliest_request ) ) {
            ccgn_registration_email_vouching_request_reminder( $voucher_id );
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