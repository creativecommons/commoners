<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Application type: individual or institutional
////////////////////////////////////////////////////////////////////////////////

// The WordPress user metadata property that tracks application kind
define(
    'CCGN_APPLICATION_TYPE',
    'ccgn-application-type'
);

define(
    'CCGN_APPLICATION_INDIVIDUAL',
    'ccgn-application-type-individual'
);
define(
    'CCGN_APPLICATION_INSTITUTIONAL',
    'ccgn-application-type-institutional'
);

function ccgn_registration_user_get_application_type ( $user_id ) {
    return get_user_meta( $user_id, CCGN_APPLICATION_TYPE, true );
}

function ccgn_registration_user_set_application_type ( $user_id, $type ) {
    update_user_meta( $user_id, CCGN_APPLICATION_TYPE, $type );
}

function ccgn_user_set_individual_applicant ( $user_id ) {
    ccgn_registration_user_set_application_type (
        $user_id,
        CCGN_APPLICATION_INDIVIDUAL
    );
}

function ccgn_user_set_institutional_applicant ( $user_id ) {
    ccgn_registration_user_set_application_type (
        $user_id,
        CCGN_APPLICATION_INSTITUTIONAL
    );
}

function ccgn_user_is_individual_applicant ( $user_id ) {
    return ccgn_registration_user_get_application_type( $user_id )
        == CCGN_APPLICATION_INDIVIDUAL;
}

function ccgn_user_is_institutional_applicant ( $user_id ) {
    return ccgn_registration_user_get_application_type( $user_id )
        == CCGN_APPLICATION_INSTITUTIONAL;
}

function ccgn_applicant_type_desc ( $user_id ) {
    $type = 'Unknown';
    if ( ccgn_user_is_individual_applicant( $user_id ) ) {
        $type = 'Individual';
    } elseif ( ccgn_user_is_institutional_applicant( $user_id ) ) {
        $type = 'Institution';
    }
    return $type;
}

////////////////////////////////////////////////////////////////////////////////
// Application state (workflow stage)
////////////////////////////////////////////////////////////////////////////////

// The WordPress user metadata property that tracks application state
define( 'CCGN_APPLICATION_STATE', 'ccgn-application-state' );

// The user is agreeing with the Charter
define( 'CCGN_APPLICATION_STATE_CHARTER', 'charter-form' );
// The user is filling out the details form
define( 'CCGN_APPLICATION_STATE_DETAILS', 'details-form' );
// The user is selecting vouchers
define( 'CCGN_APPLICATION_STATE_VOUCHERS', 'vouchers-form' );
// The user has filled out all the forms and is waiting for pre-approval
define( 'CCGN_APPLICATION_STATE_RECEIVED', 'received' );
// The user has been pre-approved and is waiting for vouchers/votes
define( 'CCGN_APPLICATION_STATE_VOUCHING', 'vouching' );
// The institutional user has been vouched/voted and received final approval
// and is waiting for final approval from the legal team
define( 'CCGN_APPLICATION_STATE_LEGAL', 'legal' );
// The user's application has been rejected in pre- or final approval
define( 'CCGN_APPLICATION_STATE_REJECTED', 'rejected' );
// The user's application has been accepted in final approval
define( 'CCGN_APPLICATION_STATE_ACCEPTED', 'accepted' );
// We have had to pause the application for some reason
define('CCGN_APPLICATION_STATE_ON_HOLD', 'on-hold' );

// States that indicate that the user is past the final approval stage.
define(
    'CCGN_APPLICATION_STATE_PAST_APPROVAL',
    [
        CCGN_APPLICATION_STATE_REJECTED,
        CCGN_APPLICATION_STATE_ACCEPTED
    ]
);

define(
    'CCGN_APPLICATION_STATE_LEGAL_APPROVAL_STATE_AVAILABLE',
    [
        CCGN_APPLICATION_STATE_LEGAL,
        CCGN_APPLICATION_STATE_REJECTED,
        CCGN_APPLICATION_STATE_ACCEPTED
    ]
);

define(
    'CCGN_APPLICATION_STATE_PAST_APPLICATION',
    [
        CCGN_APPLICATION_STATE_LEGAL,
        CCGN_APPLICATION_STATE_REJECTED,
        CCGN_APPLICATION_STATE_ACCEPTED,
        CCGN_APPLICATION_STATE_VOUCHING
    ]
);

function ccgn_registration_user_get_stage ( $user_id ) {
    return get_user_meta( $user_id, CCGN_APPLICATION_STATE, true );
}

// Note that this isn't general-purpose: it will refuse to update if the user
// is past final approval

function ccgn_registration_user_set_stage ( $user_id, $stage ) {
    $current = ccgn_registration_user_get_stage( $user_id );
    if ( ! in_array( $current, CCGN_APPLICATION_STATE_PAST_APPROVAL ) ) {
        update_user_meta(
            $user_id,
            CCGN_APPLICATION_STATE,
            $stage
        );
    }
}

function ccgn_registration_current_user_set_stage ( $stage ) {
    $user_id = get_current_user_id();
    ccgn_registration_user_set_stage ( $user_id, $stage );
}

// Is the request active?
// Not the best name, as it's pre/post approval as well as vouching.

function ccgn_vouching_request_active ( $applicant_id ) {
    $state = ccgn_registration_user_get_stage ( $applicant_id );
    // Check registration-form-callbacks.php if this isn't happy
    return
        $state == CCGN_APPLICATION_STATE_RECEIVED
        || $state == CCGN_APPLICATION_STATE_VOUCHING;
}

// Is the user at the vouching stage?
// They may have no vouches yet, or have enough vouches to be approved but
// not have received final approval.

function ccgn_vouching_request_vouching ( $applicant_id ) {
    $state = ccgn_registration_user_get_stage ( $applicant_id );
    // Check registration-form-callbacks.php if this isn't happy
    return $state == CCGN_APPLICATION_STATE_VOUCHING;
}

////////////////////////////////////////////////////////////////////////////////
// List WordPress User IDs for pre-approval and post-approval
////////////////////////////////////////////////////////////////////////////////

function _ccgn_wp_user_id ( $user ) {
    return $user->ID;
}

function _ccgn_wp_users_ids ( $users ) {
    array_map( "_ccgn_wp_user_id", $users);
}

function ccgn_applicants_of_type ( $type ) {
    $users = get_users(
        array(
            'fields' => array(
                'ID'
            ),
            'meta_query' => array(
                array(
                    'key' => CCGN_APPLICATION_TYPE,
                    'value' => $type,
                    'compare' => '='
                ),
            )
        )
    );
    return $users;
}

function ccgn_applicant_ids_of_type ( $type ) {
    return _ccgn_wp_users_ids ( ccgn_applicants_of_type ( $type ) );
}

function ccgn_applicants_with_state ( $state ) {
    $users = get_users(
        array(
            'fields' => array(
                'ID'
            ),
            'meta_query' => array(
                array(
                    'key' => CCGN_APPLICATION_STATE,
                    'value' => $state,
                    'compare' => '='
                )
            ),
            // Ideally by application date but that isn't accessible here
            'orderby' => 'ID',
            'order' => 'ASC'
        )
    );
    return $users;
}

function ccgn_applicant_ids_with_state ( $type ) {
    return _ccgn_wp_users_ids ( ccgn_applicants_with_state ( $type ) );
}

function ccgn_applicants_of_type_with_state ( $state, $type ) {
    $users = get_users(
        array(
            'fields' => array(
                'ID'
            ),
            'meta_query' => array(
                array(
                    'key' => CCGN_APPLICATION_STATE,
                    'value' => $state,
                    'compare' => '='
                ),
                array(
                    'key' => CCGN_APPLICATION_TYPE,
                    'value' => $type,
                    'compare' => '='
                ),
            )
        )
    );
    return $users;
}

function ccgn_applicant_ids_of_type_with_state ( $type ) {
    return _ccgn_wp_users_ids ( ccgn_applicants_of_type_with_state ( $type ) );
}

function ccgn_applicants_for_pre_approval () {
    return ccgn_applicants_with_state(
        CCGN_APPLICATION_STATE_RECEIVED
    );
}

function ccgn_applicants_for_final_approval () {
    return ccgn_applicants_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
}

function ccgn_application_on_hold ( $user_id ) {
    $current = ccgn_registration_user_get_stage( $user_id );
    return $current == CCGN_APPLICATION_STATE_ON_HOLD;
}

function ccgn_application_put_on_hold ( $user_id ) {
    error_log(
        ((string)$user_id)
        . ' was '
        . ccgn_registration_user_get_stage ( $user_id )
        . ' , now on hold.'
    );
    ccgn_registration_user_set_stage (
        $user_id,
        CCGN_APPLICATION_STATE_ON_HOLD
    );
}
