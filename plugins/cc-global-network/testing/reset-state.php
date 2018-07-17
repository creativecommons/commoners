<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

defined( 'CCGN_DEVELOPMENT' )
    or defined( 'CCGN_TESTING' )
    or die( 'Only for use in testing environments' );

////////////////////////////////////////////////////////////////////////////////
// Code to reset applicant workflow state for development/testing
// There is much redundancy in here.
////////////////////////////////////////////////////////////////////////////////

function _ccgn_user_level_state ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    echo "------------------------------------------------------------------------\n";
    echo "User:\t\t\t\t" . $user_id . "\n";
    echo "Roles:\t\t\t\t" . json_encode( $user->roles ) . "\n";
    echo "Is new:\t\t\t\t" . var_export( ccgn_user_is_new( $user_id ), true )
                         . "\n";

    echo "Application Stage:\t\t"
        . ccgn_registration_user_get_stage( $user_id ) . "\n";

    echo "Application Type:\t\t"
        . ccgn_registration_user_get_application_type( $user_id ) . "\n";
    echo "Is individual applicant:\t"
        . var_export( ccgn_user_is_individual_applicant( $user_id ), true )
        . "\n";
    echo "Is institutional applicant:\t"
        . var_export( ccgn_user_is_institutional_applicant( $user_id ), true )
        . "\n";

    $vote_counts = ccgn_application_votes_counts( $user_id );
    echo "Vote Counts:\t\t\t" . json_encode( $vote_counts ) . "\n";
    $vouch_counts = ccgn_application_vouches_counts( $user_id );
    echo "Vouch Counts:\t\t\t" . json_encode( $vouch_counts ) . "\n";
    echo "Is vouched:\t\t\t"
        . var_export( ccgn_user_is_vouched( $user_id ), true ) . "\n";
    echo "Is autovouched:\t\t\t"
        . var_export( ccgn_user_is_autovouched( $user_id ), true )  . "\n";

    echo "BuddyPress member type:\t\t"
        . bp_get_member_type( $user_id ) . "\n";
    echo "Is individual member:\t\t"
        . var_export( ccgn_member_is_individual( $user_id ), true ) . "\n";
    echo "Is institutional member:\t"
        . var_export( ccgn_member_is_institution( $user_id ), true ) . "\n";
    echo "------------------------------------------------------------------------\n";
}

function _ccgn_user_level_rollback_to_applicant ( $user_id ) {
    assert( $user_id != 1 );
    bp_remove_member_type( $user_id, 'individual-member' );
    bp_remove_member_type( $user_id, 'institutional-member' );
    xprofile_delete_field_data( '', $user_id );
    assert( ! ccgn_user_is_individual() );
    assert( ! ccgn_user_is_institution() );
}

// This is called in testing to reset a user's application
// It really does wipe their application details, so be careful.

function _ccgn_user_level_total_reset ( $user_id ) {
    assert( $user_id != 1 );
    _ccgn_user_level_rollback_to_applicant( $user_id );
    _ccgn_user_level_rollback_to_pre_approval ( $user_id );
    // This will delete the application forms
    _ccgn_application_delete_entries_created_by( $user_id );
    delete_user_meta( $user_id, CCGN_APPLICATION_TYPE );
    delete_user_meta( $user_id, CCGN_APPLICATION_STATE );
    delete_user_meta( $user_id, CCGN_USER_IS_AUTOVOUCHED );
    ccgn_user_level_set_applicant_new( $user_id );
    assert( ccgn_user_is_new() );
    assert( ! ccgn_user_is_individual_applicant() );
    assert( ! ccgn_user_is_institutional_applicant() );
    assert( ! ccgn_user_is_vouched() );
}

function _ccgn_user_level_rollback_to_pre_approval ( $applicant_id ) {
    assert( $applicant_id != 1 );
    if(
        in_array(
            ccgn_registration_user_get_stage( $applicant_id ),
            // This includes vouching, but we may want to clear the vouches
            CCGN_APPLICATION_STATE_PAST_APPLICATION
        )
    ) {
        _ccgn_user_level_rollback_to_applicant( $applicant_id );
        _ccgn_user_level_rollback_to_vouching ( $applicant_id );
        _ccgn_application_delete_entries_applicant_id (
            CCGN_GF_PRE_APPROVAL,
            CCGN_GF_PRE_APPROVAL_APPLICANT_ID,
            $applicant_id
        );
        update_user_meta(
            $applicant_id,
            CCGN_APPLICATION_STATE,
            CCGN_APPLICATION_STATE_RECEIVED
        );
    }
    assert(
        ccgn_registration_user_get_stage(
            $applicant_id
        ) ==  CCGN_APPLICATION_STATE_VOUCHING
    );
}

function _ccgn_user_level_rollback_to_vouching ( $applicant_id ) {
    assert( $applicant_id != 1 );
    if(
        in_array(
            ccgn_registration_user_get_stage( $applicant_id ),
            // This includes vouching, but we may want to clear the vouches
            CCGN_APPLICATION_STATE_PAST_APPLICATION
        )
    ) {
        _ccgn_user_level_rollback_to_applicant( $applicant_id );
        _ccgn_user_level_rollback_to_final_approval( $applicant_id );
        // Delete vouches and votes BY CURRENT USER
        $voucher_id = wp_get_current_user();
        $vouches = ccgn_vouches_for_applicant_by_voucher(
            $applicant_id,
            $voucher_id
        );
        foreach( $vouches as $entry ) {
            GFAPI::delete_entry( $entry[ 'id' ] );
        }
        $votes = ccgn_application_votes_for_applicant_by_user(
            $applicant_id,
            $voucher_id
        );
        foreach( $vouches as $entry ) {
            GFAPI::delete_entry( $entry[ 'id' ] );
        }
        update_user_meta(
            $applicant_id,
            CCGN_APPLICATION_STATE,
            CCGN_APPLICATION_STATE_VOUCHING
        );
    }
    assert( ! ccgn_user_is_vouched() );
    assert(
        ccgn_registration_user_get_stage(
            $applicant_id
        ) ==  CCGN_APPLICATION_STATE_VOUCHING
    );
}

function _ccgn_user_level_rollback_to_final_approval ( $applicant_id ) {
    assert( $applicant_id != 1 );
    _ccgn_user_level_rollback_to_applicant( $applicant_id );
    // Rollback state if we're past final approval
    if( in_array(
            ccgn_registration_user_get_stage( $applicant_id ),
            CCGN_APPLICATION_STATE_LEGAL_APPROVAL_STATE_AVAILABLE
        )
    ) {
        update_user_meta(
            $applicant_id,
            CCGN_APPLICATION_STATE,
            CCGN_APPLICATION_STATE_VOUCHING
        );
    }
    // Always delete final approval entries
    _ccgn_application_delete_entries_applicant_id (
        CCGN_GF_FINAL_APPROVAL,
        CCGN_GF_FINAL_APPROVAL_APPLICANT_ID,
        $applicant_id
    );
    assert( ccgn_final_approval_entry_for ( $applicant_id ) === false );
    assert(
        ccgn_registration_user_get_stage(
            $applicant_id
        ) ==  CCGN_APPLICATION_STATE_VOUCHING
    );
}

function _ccgn_user_level_rollback_to_legal_approval ( $applicant_id ) {
    assert( $applicant_id != 1 );
    if( ccgn_user_is_institutional_applicant( $applicant_id )
        && in_array(
            ccgn_registration_user_get_stage( $applicant_id ),
            CCGN_APPLICATION_STATE_PAST_APPROVAL
        )
    ) {
        _ccgn_user_level_rollback_to_applicant( $applicant_id );
        _ccgn_application_delete_entries_applicant_id (
            CCGN_GF_LEGAL_APPROVAL,
            CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID,
            $applicant_id
        );
        update_user_meta(
            $applicant_id,
            CCGN_APPLICATION_STATE,
            CCGN_APPLICATION_STATE_LEGAL
        );
    }
    assert( ccgn_legal_approval_entry_for ( $applicant_id ) === false );
    assert(
        ccgn_registration_user_get_stage(
            $applicant_id
        ) ==  CCGN_APPLICATION_STATE_LEGAL_APPROVAL
    );
}