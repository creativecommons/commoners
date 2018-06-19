<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A WordPress cron task to automatically close applications if the Applicant
  hasn't updated their Voucher requests a fixed period of time after being
  notified to by ccgn_email_update_vouchers_reminders () and send them an email
  notification of this fact.
  NOTE: This code closes the application as well as sending the email!
*/

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

// Be careful changing this, you may send a reminder sooner than expected
define( 'CCGN_CLOSE_UPDATE_VOUCHERS_AFTER_DAYS',
        CCGN_REMIND_UPDATE_VOUCHERS_AFTER_DAYS + 3 );

////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

// CLOSE THE APPLICATION
// And email the applicant to let them know.
// Because this function only has to update each application once,
// we don't have to ensure that the function is called only on the precise day
// that the application becomes eligible for closing. So we don't have an
// equivalent of ccgn_should_remind_applicant_to_update_vouchers here.

function ccgn_email_update_vouchers_closed () {
    $applicants = ccgn_applicants_with_cannot_vouches_older_than (
        CCGN_CLOSE_UPDATE_VOUCHERS_AFTER_DAYS
    );
    foreach ( $applicants as $applicant_id ) {
        ccgn_user_level_set_didnt_update_vouchers ( $applicant_id );
        ccgn_registration_email_voucher_cannot_closed( $applicant_id );
    }
}

function ccgn_schedule_email_upate_vouchers_closed () {
    if (! wp_next_scheduled ( 'ccgn_email_update_vouchers_closed_event' )) {
        wp_schedule_event(
            time(),
            'daily',
            'ccgn_email_update_vouchers_closed_event'
        );
    }
}

function ccgn_schedule_remove_email_update_vouchers_closed () {
    wp_clear_scheduled_hook( 'ccgn_email_update_vouchers_closed_event' );
}