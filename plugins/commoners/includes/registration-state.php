<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////

define ('COMMONERS_NUMBER_OF_VOUCHES_NEEDED', 2);

// The WordPress user metadata property that tracks application state
define( 'COMMONERS_APPLICATION_STATE', 'commoners-application-state' );

// The user is filling out the details form
define( 'COMMONERS_APPLICATION_STATE_DETAILS', 'details-form' );
// The user is selecting vouchers
define( 'COMMONERS_APPLICATION_STATE_VOUCHERS', 'vouchers-form' );
// The user has filled out all the forms and is waiting for pre-approval
define( 'COMMONERS_APPLICATION_STATE_RECEIVED', 'received' );
// The user has been pre-approved and is waiting for vouchers
define( 'COMMONERS_APPLICATION_STATE_VOUCHING', 'vouching' );
// The user's application has been rejected in pre- or final approval
define( 'COMMONERS_APPLICATION_STATE_REJECTED', 'rejected' );
// The user's application has been accepted in final approval
define( 'COMMONERS_APPLICATION_STATE_ACCEPTED', 'accepted' );

// States that indicate that the user is past the initial application form-
// filling stage.
define(
    'COMMONERS_APPLICATION_STATE_PAST_FORMS',
    [
        COMMONERS_APPLICATION_STATE_RECEIVED,
        COMMONERS_APPLICATION_STATE_VOUCHING,
        COMMONERS_APPLICATION_STATE_REJECTED,
        COMMONERS_APPLICATION_STATE_ACCEPTED
    ]
);

// Note that this isn't general-purpose: it will refuse to update if the user
// is already past the form entry stage

function commoners_registration_user_set_stage ( $user, $stage ) {
    $current = get_user_meta( $user, COMMONERS_APPLICATION_STATE );
    if ( ! in_array( $current, COMMONERS_APPLICATION_STATE_PAST_FORMS ) ) {
        update_user_meta(
            $user,
            COMMONERS_APPLICATION_STATE,
            $stage
        );
    }
}

function commoners_registration_current_user_set_stage ( $stage ) {
    $user = get_current_user_id();
    commoners_registration_user_set_stage ( $user, $stage );
}

function commoners_registration_user_get_stage ( $user_id ) {
    commoners_registration_user_set_stage ( $user, $stage );
}

// Is the request active?
// Not the best name, as it's pre/post approval as well as vouching.

function commoners_vouching_request_active ( $applicant_id ) {
    $state = commoners_registration_user_get_stage ( $applicant_id );
    // Check registration-form-callbacks.php if this isn't happy
    return
        $state[ 0 ] == COMMONERS_APPLICATION_STATE_RECEIVED
        || $state[ 0 ] == COMMONERS_APPLICATION_STATE_VOUCHING;
}

// Is the user at the vouching stage?
// They may have no vouches yet, or have enough vouches to be approved but
// not have received final approval.

function commoners_vouching_request_vouching ( $applicant_id ) {
    $state = commoners_registration_user_get_stage ( $applicant_id );
    // Check registration-form-callbacks.php if this isn't happy
    return $state[ 0 ] == COMMONERS_APPLICATION_STATE_VOUCHING;
}

////////////////////////////////////////////////////////////////////////////////
// List WordPress User IDs for pre-approval and post-approval
////////////////////////////////////////////////////////////////////////////////

function commoners_applicants_with_state ( $state ) {
    $users = get_users(
        array(
            'fields' => array(
                'ID'
            ),
            'meta_query' => array(
                array(
                    'key' => COMMONERS_APPLICATION_STATE,
                    'value' => $state,
                    'compare' => '='
                )
            )
        )
    );
    return $users;
}

function commoners_applicants_for_pre_approval () {
    return commoners_applicants_with_state(
        COMMONERS_APPLICATION_STATE_RECEIVED
    );
}

function commoners_applicants_for_final_approval () {
    return commoners_applicants_with_state(
        COMMONERS_APPLICATION_STATE_VOUCHING
    );
}
