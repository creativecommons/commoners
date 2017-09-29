<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Application type: individual or institutional
////////////////////////////////////////////////////////////////////////////////

// The WordPress user metadata property that tracks application kind
define(
    'COMMONERS_APPLICATION_TYPE',
    'commoners-application-type'
);

define(
    'COMMONERS_APPLICATION_INDIVIDUAL',
    'commoners-application-type-individual'
);
define(
    'COMMONERS_APPLICATION_INSTITUTIONAL',
    'commoners-application-type-institutional'
);

function commoners_registration_user_get_application_type ( $user_id ) {
    return get_user_meta( $user_id, COMMONERS_APPLICATION_TYPE )[0];
}

function commoners_registration_user_set_application_type ( $user_id, $type ) {
    update_user_meta( $user_id, COMMONERS_APPLICATION_TYPE, $type );
}

function commoners_user_set_individual_applicant ( $user_id ) {
    commoners_registration_user_set_application_type (
        $user_id,
        COMMONERS_APPLICATION_INDIVIDUAL
    );
}

function commoners_user_set_institutional_applicant ( $user_id ) {
    commoners_registration_user_set_application_type (
        $user_id,
        COMMONERS_APPLICATION_INSTITUTIONAL
    );
}

function commoners_user_is_individual_applicant ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL, $user->roles );
}

function commoners_user_is_institutional_applicant ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL, $user->roles );
}

////////////////////////////////////////////////////////////////////////////////
// Application state (workflow stage)
////////////////////////////////////////////////////////////////////////////////

// The WordPress user metadata property that tracks application state
define( 'COMMONERS_APPLICATION_STATE', 'commoners-application-state' );

// The user is agreeing with the Charter
define( 'COMMONERS_APPLICATION_STATE_CHARTER', 'charter-form' );
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

// States that indicate that the user is past the final approval stage.
define(
    'COMMONERS_APPLICATION_STATE_PAST_APPROVAL',
    [
        COMMONERS_APPLICATION_STATE_REJECTED,
        COMMONERS_APPLICATION_STATE_ACCEPTED
    ]
);

function commoners_registration_user_get_stage ( $user_id ) {
    return get_user_meta( $user_id, COMMONERS_APPLICATION_STATE )[0];
}

// Note that this isn't general-purpose: it will refuse to update if the user
// is past final approval

function commoners_registration_user_set_stage ( $user_id, $stage ) {
    $current = commoners_registration_user_get_stage( $user_id );
    if ( ! in_array( $current, COMMONERS_APPLICATION_STATE_PAST_APPROVAL ) ) {
        $result = update_user_meta(
            $user_id,
            COMMONERS_APPLICATION_STATE,
            $stage
        );
    }
}

function commoners_registration_current_user_set_stage ( $stage ) {
    $user_id = get_current_user_id();
    commoners_registration_user_set_stage ( $user_id, $stage );
}

// Is the request active?
// Not the best name, as it's pre/post approval as well as vouching.

function commoners_vouching_request_active ( $applicant_id ) {
    $state = commoners_registration_user_get_stage ( $applicant_id );
    // Check registration-form-callbacks.php if this isn't happy
    return
        $state == COMMONERS_APPLICATION_STATE_RECEIVED
        || $state == COMMONERS_APPLICATION_STATE_VOUCHING;
}

// Is the user at the vouching stage?
// They may have no vouches yet, or have enough vouches to be approved but
// not have received final approval.

function commoners_vouching_request_vouching ( $applicant_id ) {
    $state = commoners_registration_user_get_stage ( $applicant_id );
    // Check registration-form-callbacks.php if this isn't happy
    return $state == COMMONERS_APPLICATION_STATE_VOUCHING;
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

function commoners_applicants_of_type_with_state ( $state, $type ) {
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
                ),
                array(
                    'key' => COMMONERS_APPLICATION_TYPE,
                    'value' => $state,
                    'compare' => '='
                ),
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
