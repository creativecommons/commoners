<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  A wp-cli command to send reminders to Vouchers who have not yet responded
  to Vouching requests after some time, for example ten days.

  Call *weekly* from cron. Make sure day run matches defined day to send
  reminders. e.g.:

  0 0 * * MON /usr/local/bin/wp --path=/var/www/html commoners reminders

  The command makes sure it is being run on the correct day of the week and
  that it hasn't already been run on that day, providing a little protection
  against accidentally spamming Members but requiring a little care in setting
  up the system cron job that should call this.
*/

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

define( 'CCGN_SEND_VOUCH_REMINDERS_OLDER_THAN', '10' );
//NOTE: capitalised day name -------------v
define( 'CCGN_SEND_VOUCH_REMINDERS_DAY', 'Monday' );

////////////////////////////////////////////////////////////////////////////////
// Command
////////////////////////////////////////////////////////////////////////////////

// Check that time has passed and that we are on the day to check for reminders.
// If so, send reminders and update the date we last checked.

function ccgn_email_vouch_requests_reminders ( $args ) {
    $today = new DateTime( 'today' );
    $last = get_option( 'ccgn_vouch_reminders_sent_at', false );
    # php 5.2.2 or later for DateTime comparisons....
    if ( ( $last < $today )
           && ( $today->format( 'l' ) == CCGN_SEND_VOUCH_REMINDERS_DAY ) ) {
        $vouchers = ccgn_members_with_voucher_requests_older_than (
            CCGN_SEND_VOUCH_REMINDERS_OLDER_THAN
        );
        $vouchers_ids = array_keys( $vouchers );
        foreach ( $vouchers_ids as $voucher_id ) {
            ccgn_registration_email_vouching_request_reminder( $voucher_id );
        }
        update_option( 'ccgn_vouch_reminders_sent_at', $today );
    }
    WP_CLI::success( $args[0] );
}

// Here rather than in cc-global-network.php so we can load this whole file
// conditionally based on whether WP_CLI is available or not.

WP_CLI::add_command(
    'commoners reminders',
    'ccgn_email_vouch_requests_reminders'
);
