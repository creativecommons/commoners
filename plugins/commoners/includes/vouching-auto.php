<?php

// This should be an Admin config setting

define(
    'COMMONERS_AUTOVOUCH_EMAIL_DOMAINS',
    ['creativecommons.org']
);

function commoners_vouching_autovouch ($userid) {
    global $auto_vouch_reason;
    global $auto_voucher_id;
    global $commoners_vouching_can_vouch_threshold;
    $vouch_count = commoners_vouching_count_vouchee( $userid );
    // This will result in n vouches by the same userid, which we usually
    // don't allow. So be careful with this logic elsewhere.
    while ( $vouch_count < $commoners_vouching_can_vouch_threshold ) {
        commoners_vouching_add(
            $userid,
            $auto_voucher_id,
            $auto_vouch_reason,
           true
        );
        $vouch_count++;
     }
}

function commoners_vouching_should_autovouch( $email ) {
    return
        // Make sure the explode won't give an Undefined Offset error
        (strpos( $email, '@') !== false)
        && in_array(
            explode( '@', $email )[1],
            COMMONERS_AUTOVOUCH_EMAIL_DOMAINS
        );
}

function commoners_vouching_maybe_autovouch( $userid ) {
    $userdata = get_userdata( $userid );
    if ( $userdata ) {
        $email = $userdata->user_email;
        if ( commoners_vouching_should_autovouch( $email ) ) {
            commoners_vouching_autovouch( $userid );
        }
    }
}
