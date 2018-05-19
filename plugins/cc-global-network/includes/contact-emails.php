<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Send a contact email to a list of addresses
// NOTE: It sends To: the current user, and CC: $addresses

function ccgn_contact_email_to_many ( $addresses, $email_option, $subject,
                                      $body ) {
    $options = get_option( $email_option );
    $prefix = $options[ 'prefix' ];
    $wrapper = $options[ 'wrapper' ];
    $sender = wp_get_current_user();
    $message = ccgn_registration_email_sub(
        'MESSAGE',
        $body,
        $wrapper
    );
    $result = ccgn_email_send (
        $sender->user_email,
        $prefix . ' ' . $subject,
        $message,
        array_map(
            function ( $value ) { return 'cc: ' . $value; },
            $addresses
        )
    );
    ccgn_registration_email_log_append (
        $addresses, $email_option, $result, $subject, $body
    );
    return $result;
}
