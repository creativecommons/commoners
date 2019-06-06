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
// The WordPress user metadata property that tracks application state date
define( 'CCGN_APPLICATION_STATE_DATE', 'ccgn-application-state-date' );

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

// These cannot clash, as details are updated during the Spam Check stage
// and vouchers are updated during the Vouching stage, which comes after.
// The applicant must update their vouchers or else be rejected
define( 'CCGN_APPLICATION_STATE_UPDATE_VOUCHERS', 'update-vouchers' );
// The user must update their details form
define( 'CCGN_APPLICATION_STATE_UPDATE_DETAILS', 'update-details' );

// The user's application has been rejected in pre- or final approval
define( 'CCGN_APPLICATION_STATE_REJECTED', 'rejected' );
// The user's application has been accepted in final approval
define( 'CCGN_APPLICATION_STATE_ACCEPTED', 'accepted' );
// We have had to pause the application for some unspecified reason
define('CCGN_APPLICATION_STATE_ON_HOLD', 'on-hold' );
// The applicant did not update their vouchers and so has been rejected
define(
    'CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS',
    'rejected-because-didnt-update-vouchers'
);

define(
    'CCGN_APPLICATION_STATE_CAN_BE_PRE_APPROVED',
    [
        CCGN_APPLICATION_STATE_RECEIVED,
        CCGN_APPLICATION_STATE_UPDATE_DETAILS
    ]
);

// States that indicate that the user is past the final approval stage.
define(
    'CCGN_APPLICATION_STATE_PAST_APPROVAL',
    [
        CCGN_APPLICATION_STATE_REJECTED,
        CCGN_APPLICATION_STATE_ACCEPTED,
        CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS
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
        CCGN_APPLICATION_STATE_VOUCHING,
        CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS
    ]
);

define(
    'CCGN_APPLICATION_STATE_VOUCHABLE',
    [
        CCGN_APPLICATION_STATE_VOUCHING,
        CCGN_APPLICATION_STATE_UPDATE_VOUCHERS
    ]
);

function ccgn_registration_user_get_stage ( $user_id ) {
    return get_user_meta( $user_id, CCGN_APPLICATION_STATE, true );
}

function ccgn_registration_user_get_stage_and_date ( $user_id ) {
    $meta = get_user_meta( $user_id );
    return array(
        'stage' => $meta[CCGN_APPLICATION_STATE][0],
        'date' => $meta[CCGN_APPLICATION_STATE_DATE][0]
    );
}

function _ccgn_registration_user_set_stage( $user_id, $stage ) {
    update_user_meta(
        $user_id,
        CCGN_APPLICATION_STATE,
        $stage
    );
    // Keep track of when we updated the state (and thereby how long we have
    // been in this state).
    update_user_meta(
        $user_id,
        CCGN_APPLICATION_STATE_DATE,
        date( 'Y-m-d H:i:s', strtotime( 'now' ) )
    );
}

// Note that this isn't general-purpose: it will refuse to update if the user
// is past final approval

function ccgn_registration_user_set_stage ( $user_id, $stage ) {
    $current = ccgn_registration_user_get_stage( $user_id );
    if ( ! in_array( $current, CCGN_APPLICATION_STATE_PAST_APPROVAL ) ) {
        _ccgn_registration_user_set_stage( $user_id, $stage );
    } else {
        error_log( 'Attempt to set user ' . $user_id . ' to stage '
                   . $stage . ' when user is past mutable application state.' );
    }
}

// This one will set user to update vouchers but only if you should be able to

function ccgn_registration_user_set_stage_update_vouchers ( $user_id ) {
    $current = ccgn_registration_user_get_stage( $user_id );
    if ( ( $current == CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS )
         || ( $current == CCGN_APPLICATION_STATE_VOUCHING ) ) {
        _ccgn_registration_user_set_stage(
            $user_id,
            CCGN_APPLICATION_STATE_UPDATE_VOUCHERS
        );
    } else {
        error_log( 'Attempt to set user ' . $user_id
                   . ' to stage CCGN_APPLICATION_STATE_UPDATE_VOUCHERS when user is not in stage CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS.' );
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
        || ccgn_registration_user_get_stage ( $applicant_id );
}

// Is the user at the vouching stage?
// They may have no vouches yet, or have enough vouches to be approved but
// not have received final approval.

function ccgn_vouching_request_vouching ( $applicant_id ) {
    // Check registration-form-callbacks.php if this isn't happy
    return ccgn_registration_user_is_vouchable ( $applicant_id );
}


function ccgn_registration_user_is_vouchable ( $user_id ) {
    return in_array(
        ccgn_registration_user_get_stage ( $user_id ),
        CCGN_APPLICATION_STATE_VOUCHABLE
    );
}

//NOTE: This returns, and is meant to be, the number of days since the
// CCGN_APPLICATION_STATE_DATE was last updated. If the Applicant's state
// had been set to the same state ten times over the course of 100 days and the
// last time was one day ago, this function MUST return 1.

function ccgn_days_since_state_set ( $applicant_id, $now ) {
    $state = ccgn_registration_user_get_stage_and_date ( $applicant_id );
    // Calculate days in the state
    $state_date = new DateTime($state['date']);
    return $state_date->diff($now)->days;
}

////////////////////////////////////////////////////////////////////////////////
// List WordPress User IDs for pre-approval and post-approval
////////////////////////////////////////////////////////////////////////////////

function _ccgn_wp_user_id ( $user ) {
    return $user->ID;
}

function _ccgn_wp_users_ids ( $users ) {
    return array_map( "_ccgn_wp_user_id", $users);
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

function ccgn_applicant_ids_with_state ( $state ) {
    return _ccgn_wp_users_ids ( ccgn_applicants_with_state ( $state ) );
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
/*
    Application status for user status page
 */
function ccgn_show_current_application_status($user_id) {
    $current_status = ccgn_registration_user_get_stage_and_date($user_id);
    if ( ($current_status['stage'] == 'vouching') && ( ccgn_application_can_be_voted($user_id) )) {
        $current_status['stage'] = 'voting';
    }
    $link_form = (ccgn_user_is_individual_applicant($user_id)) ? site_url('sign-up/individual/form') : site_url('sign-up/institution/form');
    $steps = array(
        'charter-form' => array(
            'step' => 1,
            'msg' => 'You have to agree with the Charter',
            'class' => 'success',
            'link' => $link_form,
            'link_text' => 'Agree with the Charter'   
        ),
        'details-form' => array(
            'step' => 1,
            'msg' =>'You have to fill out the details form',
            'class' => 'success',
            'link' => $link_form,
            'link_text' => 'Fill Details' 
        ),
        'vouchers-form' => array(
            'step' => 1,
            'msg' => 'You have to select your vouchers',
            'class' => 'success',
            'link' => $link_form,
            'link_text' => 'Select vouchers'
        ),
        'update-details' => array(
            'step' => 1,
            'msg' => 'You have to update your details',
            'class' => 'success',
            'link' => $link_form,
            'link_text' => 'Update details'
        ),
        'received' => array(
            'step' => 2,
            'msg' => 'You have selected your Vouchers and now you have to wait for pre-approval',
            'class' => 'on-hold'
        ),
        'vouching' => array(
            'step' => 2,
            'msg' => 'You have been pre-approved and you have to wait for your vouchers',
            'class' => 'on-hold'
        ),
        'voting' => array(
            'step' => 3,
            'msg' => 'Your application going OK and it’s now under review for being approved.',
            'class' => 'on-hold'
        ),
        'update-vouchers' => array(
            'step' => 2,
            'msg' => 'You have to update your vouchers',
            'class' => 'success',
            'link' => $link_form,
            'link_text' => 'Update vouchers'
        ),
        'rejected-because-didnt-update-vouchers' => array(
            'step' => 2,
            'msg' => 'Your application has been declined, because you didn \'t update your vouchers on time',
            'class' => 'error'
        ),
        'accepted' => array(
            'step' => 3,
            'msg' => 'Your application has been accepted in final approval',
            'class' => 'success'
        ),
        'legal' => array(
            'step' => 3,
            'msg' => 'Your application has been approved and now is waiting for Legal approval',
            'class' => 'on-hold'
        ),
        'on-hold' => array(
            'step' => 3,
            'msg' => 'The application has been paused for some reason. Contact us at '.antispambot('network-support@creativecommons.org'),
            'class' => 'error'
        ),
        'rejected' => array(
            'step' => 3,
            'msg' => 'Your application has been rejected. Please get in touch with us at' . antispambot('network-support@creativecommons.org'),
            'class' => 'error'
        )
    );
    $current_state = array();
    $current_state['step'] = $steps[$current_status['stage']];
    $current_state['date'] = $current_status['date'];
    return $current_state;
}

//Remove old applications left in custom state
//The main reason is to wipe old applications considered abandoned
function ccgn_get_old_applications_with_state($state, $months_ago)
{
    $args = array(
        'role' => 'new-user',
        'posts_per_page' => -1,
        'date_query' => array(
            array(
                'column' => 'user_registered',
                'before' => $months_ago . ' months ago'
            )
        ),
        'meta_query' => array(
            array(
                'key' => 'ccgn-application-state',
                'value' => $state
            )
        )
    );
    
    $query = new WP_User_Query($args);
    return $query;
}
function ccgn_get_applications_with_state($state)
{
    $args = array(
        'number' => -1,
        'meta_query' => array(
            array(
                'key' => 'ccgn-application-state',
                'value' => $state
            )
        )
    );
    
    $query = new WP_User_Query($args);
    return $query;
}
function ccgn_list_all_applications_with_state($state)
{
    if ( current_user_can( 'administrator' ) ) {
        $query = ccgn_get_applications_with_state($state);
        echo '-------------------------------------------------------------------------------------' . "\n";
        echo 'LISTING USERS WITH THE STATE "' . $state  . '"' . "\n";
        echo '-------------------------------------------------------------------------------------' . "\n";
        foreach ($query->get_results() as $user) {
            $user_id = $user->data->ID;
            echo $user_id . ' - ' . $user->data->display_name . '( ' . $user->data->user_login . ' ) - registered on: ' . $user->data->user_registered . "\n";
        }
    }
}
function ccgn_list_old_applications_with_state($state, $months_ago)
{
    if ( current_user_can( 'administrator' ) ) {
        $query = ccgn_get_old_applications_with_state($state, $months_ago);
        echo '-------------------------------------------------------------------------------------' . "\n";
        echo 'LISTING USERS WITH THE STATE "' . $state . '" AND REGISTERED ' . $months_ago . ' MONTHS AGO' . "\n";
        echo '-------------------------------------------------------------------------------------' . "\n";
        foreach ($query->get_results() as $user) {
            $user_id = $user->data->ID;
            echo $user_id . ' - ' . $user->data->display_name . '( ' . $user->data->user_login . ' ) - registered on: ' . $user->data->user_registered . "\n";
        }
    }
}
function ccgn_remove_old_applications_with_state($state, $months_ago)
{
    if ( current_user_can( 'administrator' ) ) {
        $query = ccgn_get_old_applications_with_state($state, $months_ago);
        foreach ($query->get_results() as $user) {
            $user_id = $user->data->ID;
            $user_name = $user->data->display_name;
            $user_login = $user->data->user_login;
            _ccgn_application_delete_entries_created_by($user_id);
            delete_user_meta($user_id, CCGN_APPLICATION_TYPE);
            delete_user_meta($user_id, CCGN_APPLICATION_STATE);
            delete_user_meta($user_id, CCGN_USER_IS_AUTOVOUCHED);

            $delete = wp_delete_user($user_id);
            if ($delete) {
                echo 'DELETED USER: ' . $user_id . ' - ' . $user_name . '( ' . $user_login . ' )' . "\n";
            }
        }
    }
}
function ccgn_remove_all_applications_with_state($state)
{
    if ( current_user_can( 'administrator' ) ) {
        $query = ccgn_get_applications_with_state($state);
        foreach ($query->get_results() as $user) {
            $user_id = $user->data->ID;
            $user_name = $user->data->display_name;
            $user_login = $user->data->user_login;
            _ccgn_application_delete_entries_created_by($user_id);
            delete_user_meta($user_id, CCGN_APPLICATION_TYPE);
            delete_user_meta($user_id, CCGN_APPLICATION_STATE);
            delete_user_meta($user_id, CCGN_USER_IS_AUTOVOUCHED);

            $delete = wp_delete_user($user_id);
            if ($delete) {
                echo 'DELETED USER: ' . $user_id . ' - ' . $user_name . '( ' . $user_login . ' )' . "\n";
            }
        }
    }
}