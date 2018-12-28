<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

///////////////////////////////////////////////////////////////////////////////
// NOTES:
// We should use GFAPI::update_entry_field more often than we do.
///////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Constants and functions for interacting with GravityForms.
// We handle forms, fields and entries here.
// Note that we don't handle multiple applications by the same user yet,
// but code that sorts by date before returning the zeroth item is boilerplate
// to handle that feature.
////////////////////////////////////////////////////////////////////////////////

// The day before the first user was registered

define( 'CCGN_SITE_EPOCH', '2017-10-15' );

// Application initial application forms

define( 'CCGN_GF_AGREE_TO_TERMS', 'Agree To Terms' );
define( 'CCGN_GF_SIGN_CHARTER', 'Sign The Charter' );
define( 'CCGN_GF_INDIVIDUAL_DETAILS', 'Applicant Details' );
define( 'CCGN_GF_INSTITUTION_DETAILS', 'Institution Details' );
define( 'CCGN_GF_CHOOSE_VOUCHERS', 'Choose Vouchers' );

// Member vouching for applicant / institution

define( 'CCGN_GF_VOUCH', 'Vouch For Applicant' );

// Admin approval of applicant

define( 'CCGN_GF_PRE_APPROVAL', 'Pre Approval' );
define( 'CCGN_GF_VOTE', 'Vote on Membership' );
define( 'CCGN_GF_FINAL_APPROVAL', 'Final Approval' );

// Legal team final approval of Institutional Members

define( 'CCGN_GF_LEGAL_APPROVAL', 'Legal Approval' );

// Admin updating Application details

define( 'CCGN_GF_ADMIN_CHANGE_VOUCHERS', 'Administrator Change Vouchers' );

// Individual fields in forms

define( 'CCGN_GF_DETAILS_PRIVACY_POLICY', '5' );

// Adding anything here? Check:
// ccgn_registration_individual_shortcode_render_details

define( 'CCGN_GF_DETAILS_NAME', '1' );
define( 'CCGN_GF_DETAILS_BIO', '2' );
define( 'CCGN_GF_DETAILS_STATEMENT', '3' );
define( 'CCGN_GF_DETAILS_AREAS_OF_INTEREST', '5' );
define( 'CCGN_GF_DETAILS_LANGUAGES', '6' );
define( 'CCGN_GF_DETAILS_LOCATION', '20' );
define( 'CCGN_GF_DETAILS_CHAPTER_INTEREST', '7' );
define( 'CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS', '9' );
/*define( 'CCGN_GF_DETAILS_AVATAR_FILE', '11' );
define( 'CCGN_GF_DETAILS_AVATAR_SOURCE', '12' );
define( 'CCGN_GF_DETAILS_AVATAR_GRAVATAR', '13' );*/
define( 'CCGN_GF_DETAILS_WAS_AFFILIATE', '15' );
define( 'CCGN_GF_DETAILS_WAS_AFFILIATE_NAME', '16' );
define( 'CCGN_GF_DETAILS_RECEIVE_EMAILS', '18.1' );
define( 'CCGN_GF_DETAILS_NAME_PARAMETER', 'your-name' );
define( 'CCGN_GF_DETAILS_BIO_PARAMETER', 'brief-biography' );
define( 'CCGN_GF_DETAILS_STATEMENT_PARAMETER', 'brief-statement' );
define( 'CCGN_GF_DETAILS_AREAS_OF_INTEREST_PARAMETER', 'areas-of-interest' );
define( 'CCGN_GF_DETAILS_LANGUAGES_PARAMETER', 'languages' );
define( 'CCGN_GF_DETAILS_LOCATION_PARAMETER', 'primary-location' );
define(
    'CCGN_GF_DETAILS_CHAPTER_INTEREST_PARAMETER',
    'preferred-country-chapter'
);
define( 'CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS_PARAMETER', 'social-media-urls' );
define( 'CCGN_GF_DETAILS_WAS_AFFILIATE_PARAMETER', 'affiliate-status' );
define( 'CCGN_GF_DETAILS_WAS_AFFILIATE_NAME_PARAMETER', 'affiliate-name' );
define( 'CCGN_GF_DETAILS_RECEIVE_EMAILS_PARAMETER', 'receive-updates' );

// Adding anything here? Check:
// ccgn_registration_institution_shortcode_render_view

define( 'CCGN_GF_INSTITUTION_DETAILS_NAME', '1' );
define( 'CCGN_GF_INSTITUTION_DETAILS_WEB_SITE', '2' );
define( 'CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_NAME', '3' );
define( 'CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_EMAIL', '4' );
define( 'CCGN_GF_INSTITUTION_DETAILS_EXISTING_MEMBERS', '5' );
define( 'CCGN_GF_INSTITUTION_DETAILS_STATEMENT', '6' );
define( 'CCGN_GF_INSTITUTION_DETAILS_IS_AFFILIATE', '7' );
define(
    'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS',
    [ '9.1', '9.2', '9.3', '9.4' ]
);
define( 'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_DOMAIN_NAME', '8' );
define( 'CCGN_GF_INSTITUTION_DETAILS_ADDITIONAL_INFO', '10' );
define( 'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_TRADEMARK', '11' );
define( 'CCGN_GF_INSTITUTION_DETAILS_NAME_PARAMETER', 'institution-name' );
define( 'CCGN_GF_INSTITUTION_DETAILS_WEB_SITE_PARAMETER', 'website' );
define(
    'CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_NAME_PARAMETER',
    'representative-name'
);
define(
    'CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_EMAIL_PARAMETER',
    'representative-email'
);
define(
    'CCGN_GF_INSTITUTION_DETAILS_EXISTING_MEMBERS_PARAMETER',
    'existing-members'
);
define( 'CCGN_GF_INSTITUTION_DETAILS_STATEMENT_PARAMETER', 'statement' );
define( 'CCGN_GF_INSTITUTION_DETAILS_IS_AFFILIATE_PARAMETER', 'is-affiliate' );
define(
    'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS_PARAMETER',
    'affiliate-assets'
);
define(
    'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_DOMAIN_NAME_PARAMETER',
    'affiliate-domain-name'
);
define(
    'CCGN_GF_INSTITUTION_DETAILS_ADDITIONAL_INFO_PARAMETER',
    'additional-info'
);
define(
    'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_TRADEMARK_PARAMETER',
    'affiliate-trademark'
);


define( 'CCGN_GF_VOUCH_APPLICANT_ID', 'applicant_id' );
define( 'CCGN_GF_VOUCH_APPLICANT_ID_FIELD', '7' );
define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH', '3' );
define( 'CCGN_GF_VOUCH_REASON', '4' );
define('CCGN_GF_VOUCH_REASON_NO', '9');

define( 'CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
define(
    'CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION_PARAMETER',
    'pre_approval'
);
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_ID', '4' );
define(
    'CCGN_GF_PRE_APPROVAL_APPLICANT_MUST_UPDATE_DETAILS_PARAMETER',
    'must_update_details'
);
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_MUST_UPDATE_DETAILS', '5' );

define( 'CCGN_GF_VOTE_APPROVE_MEMBERSHIP_APPLICATION', '2' );
define( 'CCGN_GF_VOTE_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_VOTE_APPLICANT_ID', '4' );

define( 'CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
define( 'CCGN_GF_FINAL_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_FINAL_APPROVAL_APPLICANT_ID', '3' );

define( 'CCGN_GF_LEGAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
define( 'CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID', '3' );

define( 'CCGN_GF_ADMIN_CHANGE_VOUCHERS_VOUCHER_1', '1' );
define( 'CCGN_GF_ADMIN_CHANGE_VOUCHERS_VOUCHER_2', '2' );
define( 'CCGN_GF_ADMIN_CHANGE_VOUCHERS_ORIGINAL_VOUCHER_1', '3' );
define( 'CCGN_GF_ADMIN_CHANGE_VOUCHERS_ORIGINAL_VOUCHER_2', '4' );
define( 'CCGN_GF_ADMIN_CHANGE_VOUCHERS_APPLICANT_ID', '5' );
define(
    'CCGN_GF_ADMIN_CHANGE_VOUCHERS_APPLICANT_ID_PARAMETER',
    'applicant_id'
);

// Field values that we need to check

define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH_YES', 'Yes' );
define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH_NO', 'No' );
define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH_CANNOT', 'Cannot' );
// This is added by the system, not users, but it is here for completeness
define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH_REMOVED', 'REMOVED' );
define( 'CCGN_GF_PRE_APPROVAL_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_PRE_APPROVAL_APPROVED_UPDATE_DETAILS', 'Update Details' );
define( 'CCGN_GF_VOTE_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_FINAL_APPROVAL_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_LEGAL_APPROVAL_APPROVED_YES', 'Yes' );

/*define( 'CCGN_GF_DETAILS_AVATAR_SOURCE_GRAVATAR', 'gravatar' );
  define( 'CCGN_GF_DETAILS_AVATAR_SOURCE_UPLOAD', 'image' );*/

define( 'CCGN_GF_INSTITUTION_DETAILS_IS_AFFILIATE_YES', 'Yes' );
define( 'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS_MOU', 'MOU' );
define(
    'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS_TRADEMARK',
    'Trademark Agreement'
);
define(
    'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS_DOMAIN_NAME',
    'Domain Name'
);

define(
    'CCGN_GF_DETAILS_CHAPTER_INTEREST_SAME_AS_LOCATION',
    'Same as Primary Location'
);

// Fields to display in applicant/voucher profiles

define(
    'CCGN_GF_DETAILS_VOUCH_MAP',
    [
        [ 'Brief Biography', CCGN_GF_DETAILS_BIO ],
        [ 'Membership Statement', CCGN_GF_DETAILS_STATEMENT ],
        [ 'Location', CCGN_GF_DETAILS_LOCATION ],
        [ 'Chapter of Interest', CCGN_GF_DETAILS_CHAPTER_INTEREST ],
        [ 'Social Media / URLs', CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS ],
    ]
);

define(
    'CCGN_GF_DETAILS_USER_PAGE_MAP',
    [
        [ 'Is/Was Part of Affiliate Group', CCGN_GF_DETAILS_WAS_AFFILIATE ],
        [ 'Affiliate Group Name', CCGN_GF_DETAILS_WAS_AFFILIATE_NAME ],
        [ 'Brief Biography', CCGN_GF_DETAILS_BIO ],
        [ 'Membership Statement', CCGN_GF_DETAILS_STATEMENT ],
        [ 'Areas of Interest', CCGN_GF_DETAILS_AREAS_OF_INTEREST ],
        [ 'Languages', CCGN_GF_DETAILS_LANGUAGES ],
        [ 'Location', CCGN_GF_DETAILS_LOCATION ],
        [ 'Chapter of Interest', CCGN_GF_DETAILS_CHAPTER_INTEREST ],
        [ 'Social Media / URLs', CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS ],
    ]
);

define(
    'CCGN_GF_INSTITUTION_DETAILS_VOUCH_MAP',
    [
        [ 'Institution Web Site', CCGN_GF_INSTITUTION_DETAILS_WEB_SITE ],
        [
            'Representative Name',
            CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_NAME
        ],
        /*[
            'Representative Email',
            CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_EMAIL
            ],*/
        [ 'Membership Statement', CCGN_GF_INSTITUTION_DETAILS_STATEMENT ],
        [
            'Additional Information',
            CCGN_GF_INSTITUTION_DETAILS_ADDITIONAL_INFO
        ],
    ]
);

define(
    'CCGN_GF_INSTITUTION_DETAILS_USER_PAGE_MAP',
    [
        [ 'Institution Web Site', CCGN_GF_INSTITUTION_DETAILS_WEB_SITE ],
        [
            'Representative Name',
            CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_NAME
        ],
        [
            'Representative Email',
            CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_EMAIL
        ],
        [
            'Existing Global Network Members',
            CCGN_GF_INSTITUTION_DETAILS_EXISTING_MEMBERS
        ],
        [ 'Membership Statement', CCGN_GF_INSTITUTION_DETAILS_STATEMENT ],
        [ 'Is An Affiliate', CCGN_GF_INSTITUTION_DETAILS_IS_AFFILIATE ],
        [
            'Affiliate Assets',
            CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS
        ],
        [
            'Affiliate Trademark(s)',
            CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_TRADEMARK
        ],
        [
            'Affiliate Domain Name(s)',
            CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_DOMAIN_NAME
        ],
        [
            'Additional Information',
            CCGN_GF_INSTITUTION_DETAILS_ADDITIONAL_INFO
        ],
    ]
);

// The field IDs containing user names in the "Choose Vouchers" form

define(
    'CCGN_GF_VOUCH_VOUCHER_FIELDS',
    [ '1', '2' ]
);

////////////////////////////////////////////////////////////////////////////////
// Utilities
////////////////////////////////////////////////////////////////////////////////

// Use where we wish to measure a date from the most recent of either the date
// of creation or the date on which the entry was updated.

function ccgn_entry_created_or_updated ( $entry ) {
    $date = $entry[ 'date_updated' ];
    if ( is_null( $date ) ) {
        $date = $entry[ 'date_created' ];
    }
    return $date;
}

// This is hideous - RobM
// Note that this takes the entry array, not entry id.
//NOTE: BEWARE THIS BREAKING ON GRAVITYFORMS VERSION UPDATES

function ccgn_set_entry_update_date ( $entry, $date ) {
    global $wpdb;
    return $wpdb->update(
        "{$wpdb->prefix}gf_entry",
        array( 'date_updated' => $date ),
        array( 'id' => $entry['id'] )
    );
}

// Append a note to the entry's notes.
// Note that this takes the entry array, not entry id.

function ccgn_entry_append_note ( $entry, $note ) {
    // Will be user zero / username "" in cron tasks
    $current_user = wp_get_current_user();
    $username = $current_user->display_name ? $current_user->display_name : '[cron job or cli user]';
    RGFormsModel::add_note(
        $entry[ 'id' ],
        $current_user->ID,
        $username,
        $note
    );
}

////////////////////////////////////////////////////////////////////////////////
// Form cleanup
////////////////////////////////////////////////////////////////////////////////

// The number of days to wait before cleaning up application records
define( 'CCGN_CLEANUP_DAYS', 21 );

////////////////////////////////////////////////////////////////////////////////
// Paged GravityForms entries fetching
////////////////////////////////////////////////////////////////////////////////

function ccgn_gf_get_paged_all ( $form_id, $search_criteria, $sorting ) {
    $page_size = 100;
    $results = array();
    $paging = array( 'offset' => 0, 'page_size' => $page_size );
    while ( true ) {
        $entries = GFAPI::get_entries(
            $form_id,
            $search_criteria,
            $sorting,
            $paging
        );
        //FIXME: Weak logic. We should signal the caller
        if ( is_wp_error( $entries ) ) {
            break;
        }
        if ( $entries == [] ) {
            break;
        }
        $results = array_merge( $results, $entries );
        $paging[ 'offset' ] += $page_size;
    }
    return $results;
}

////////////////////////////////////////////////////////////////////////////////
// Finding entries
////////////////////////////////////////////////////////////////////////////////

// Created by user id

function ccgn_entries_created_by_user (
    $user_id,
    $form_name
) {
    // Form name is false? Use zero (every form)
    $form_id = $form_name ? RGFormsModel::get_form_id( $form_name ) : 0;
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' => 'created_by',
            'value' => $user_id
        );
    return GFAPI::get_entries(
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );
}

// Mentioning the user id

function ccgn_entries_referring_to_user (
    $user_id,
    $form_name,
    $field_id
) {
    $form_id = RGFormsModel::get_form_id( $form_name );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>   $field_id,
            'value' => $user_id
        );
    return GFAPI::get_entries(
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'ASC',
                'is_numeric' => false
            )
        )
    );
}


////////////////////////////////////////////////////////////////////////////////
// Vouching form
////////////////////////////////////////////////////////////////////////////////

function ccgn_vouches_for_applicant_by_voucher ( $applicant_id, $voucher_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_VOUCH );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>  'created_by',
            'value' => $voucher_id
        );
    $search_criteria['field_filters'][]
        = array(
            'key' =>  CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
            'value' => $applicant_id
        );
    return GFAPI::get_entries(
        $form_id,
        $search_criteria
    );
}

// Has the voucher already responded to applicant's vouching request?

function ccgn_vouching_request_open ( $applicant_id, $voucher_id ) {
    $entries = ccgn_vouches_for_applicant_by_voucher(
        $applicant_id,
        $voucher_id
    );
    return $entries == [];
}

// Spoof a "cannot" for an automatically closed Vouching request

function ccgn_vouching_request_spoof_cannot ( $applicant_id, $voucher_id ) {
    // Make sure the user hasn't been replaced as a voucher
    if ( ! in_array(
        $voucher_id,
        ccgn_application_vouchers_users_ids ( $applicant_id )
    ) ) {
        error_log( "Voucher is not currently in applicant's Voucher requests. Not spoofing." );
        return false;
    }
    $entry_id = GFAPI::add_entry (
        array(
            'form_id' => RGFormsModel::get_form_id( CCGN_GF_VOUCH ),
            'date_created' => date ( 'Y-m-d H:i:s' ),
            'created_by' => $voucher_id,
            CCGN_GF_VOUCH_DO_YOU_VOUCH => 'Cannot',
            CCGN_GF_VOUCH_REASON => 'AUTOMATICALLY CLOSED: NO RESPONSE',
            CCGN_GF_VOUCH_APPLICANT_ID_FIELD => $applicant_id
        )
    );
    // Admin log
    ccgn_entry_append_note (
        array( 'id' =>  $entry_id ),
        'SPOOFING "CANNOT" VOUCH ENTRY: this is due to the Voucher not responding in time.'
    );
    // If applicant is vouching, they must now update vouchers
    ccgn_registration_user_set_stage_update_vouchers( $application_id );
    return true;
}

///////////////////////////////////////////////////////////////////////////////
// Resetting application properties.
///////////////////////////////////////////////////////////////////////////////

// This is here rather than in application-state.php so we don't try to
// reference the vouch counting functions there.

function ccgn_application_to_vouching_if_no_current_cannots ( $applicant_id ) {
    $current_cannots = ccgn_application_vouches_cannots( $applicant_id );
    if ( count( $current_cannots ) == 0 ) {
        _ccgn_registration_user_set_stage(
            $applicant_id,
            CCGN_APPLICATION_STATE_VOUCHING
        );
    }
}

// Roll back an application that was automatically closed because the applicant
// did not update their Voucher Choices in response to a Member vouching
// "Cannot" or an automatically closed vouching request (also appearing as a
// "Cannot").

function ccgn_reopen_application_auto_closed_because_cannots(
    $applicant_id
) {
    $applicant_state = ccgn_registration_user_get_stage($applicant_id);
    if ($applicant_state != CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS) {
        error_log("Not re-opening Application for User ID "
            . $applicant_id
            . ": not in state CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS.");
        return false;
    }
    $current_cannots = ccgn_application_vouches_cannots($applicant_id);
    if (count($current_cannots) == 0) {
        error_log("Not re-opening Application for User ID "
            . $applicant_id
            . ': it does not have any "Cannot" vouches.');
        return false;
    }
    $choose_vouchers_entry = ccgn_application_vouchers($applicant_id);
    // Update the date on the Choose Vouchers form, resetting the timescale
    // for updating voucher choices
    $update_date = date('Y-m-d H:i:s', strtotime('now'));
    ccgn_set_entry_update_date($choose_vouchers_entry, $update_date);
    // Keep admin log
    ccgn_entry_append_note(
        $choose_vouchers_entry,
        'RE-OPENING APPLICATION AUTO-CLOSED DUE TO "CANNOT" VOUCHES: Setting update date to '
            . $update_date
    );
    // Delete votes from user if there are

    ccgn_delete_vote_entries_from_user( $applicant_id );

    // Restore the user to the new-user role
    ccgn_user_level_set_applicant_new($applicant_id);
    // Set the application to be in the update vouchers stage
    ccgn_registration_user_set_stage_update_vouchers($applicant_id);
    // Remind them to do so by email
    ccgn_registration_email_voucher_cannot_reminder($applicant_id);
    return true;
}
// Delete vote entries from an applicant in order to restore the application status

function ccgn_delete_vote_entries_from_user($applicant_id)
{
    $entries = ccgn_application_votes($applicant_id);
    if (count($entries) > 0) {
        foreach ($entries as $entry) {
            GFAPI::delete_entry($entry['id']);
        }
    }
}

// Remove a mistaken vouching response and reset the applicant's vouching
// timescale. This should only be called extraordinarily, in response to a
// support request.
// Note that this will remove *any* vouch for the applicant if requested,
// this includes vouches from vouchers that the user has since removed from
// their vouch requests. This is so that if the voucher wishes to change their
// vouch and the user needs to change their vouchign requests to do so,
// neither is waiting on the other.
// Be careful calling this though.

function ccgn_reset_vouch_request (
    $applicant_id,
    $voucher_id
) {
    $applicant_state = ccgn_registration_user_get_stage ( $applicant_id );
    // See logic at end as well
    assert( $applicant_state == CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS
            || $applicant_state == CCGN_APPLICATION_STATE_VOUCHING );
    $update_date = date( 'Y-m-d H:i:s', strtotime( 'now' ) );
    // Mark the vouching request as not to be considered when counting vouches
    $vouch = ccgn_vouches_for_applicant_by_voucher(
        $applicant_id,
        $voucher_id
    )[ 0 ];
    $original_vouch = $vouch[CCGN_GF_VOUCH_DO_YOU_VOUCH];
    //FIXME: Check to see if $original_vouch = REMOVED?
    $vouch[CCGN_GF_VOUCH_DO_YOU_VOUCH] = CCGN_GF_VOUCH_DO_YOU_VOUCH_REMOVED;
    GFAPI::update_entry( $vouch );
    ccgn_set_entry_update_date( $vouch, $update_date );
    // Keep an admin log.
    ccgn_entry_append_note(
        $vouch,
        "RESETTING VOUCH REQUEST STATUS: So to make sure this Vouch doesn't affect that we are changing its Status from "
        . $original_vouch . ' to '
        . CCGN_GF_VOUCH_DO_YOU_VOUCH_REMOVED . ' at ' . $update_date
    );
    // Update the date on the Choose Vouchers form, resetting the timescale
    // for updating voucher choices
    $choose_vouchers_entry = ccgn_application_vouchers( $applicant_id );
    ccgn_set_entry_update_date( $choose_vouchers_entry, $update_date );
    // Keep an admin log.
    ccgn_entry_append_note(
        $choose_vouchers_entry,
        'RESETTING VOUCH REQUEST STATUS: To make sure the Applicant has time to update their Vouch Requests, we are updating the entry update date to ' . $update_date
    );
    // If the user isn't vouching, set them back to vouching, allowing them to
    // update their voucher choices and reminding them to do so by email
    // during the next cron job run.
    ccgn_application_to_vouching_if_no_current_cannots( $applicant_id );
}

// NOTE: This resets the Applicant's Vouching timescale by modifying the
//       creation date of their Choose Vouchers form entry.

function ccgn_vouch_request_remove_spoofed_cannot (
    $applicant_id,
    $voucher_id
) {
    // Make sure the user hasn't been replaced as a voucher
    if ( ! in_array(
        $voucher_id,
        ccgn_application_vouchers_users_ids ( $applicant_id )
    ) ) {
        error_log( "Voucher is not currently in applicant's Voucher requests. Not resetting." );
        return false;
    }
    // Get spoofed cannot for voucher and applicant
    $search_criteria = array();
    $search_criteria['field_filters'][] = array(
        'key' =>   'created_by',
        'value' => $voucher_id
    );
    $search_criteria['field_filters'][] = array(
        'key' =>   CCGN_GF_VOUCH_DO_YOU_VOUCH,
        'value' => 'Cannot'
    );
    $search_criteria['field_filters'][] = array(
        'key' =>   CCGN_GF_VOUCH_REASON,
        'value' => 'AUTOMATICALLY CLOSED: NO RESPONSE'
    );
    $search_criteria['field_filters'][] = array(
        'key' =>   CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
        'value' => $applicant_id
    );

    $spoofs = GFAPI::get_entries(
        RGFormsModel::get_form_id( CCGN_GF_VOUCH ),
        $search_criteria
    );
    if ( ! is_wp_error( $spoofs )
         && count($spoofs) > 0 ) {
        // There should be only one, but just in case
        foreach ( $spoofs as $spoof ) {
            // Make sure $search_criteria worked. We don't want to delete the
            // wrong entries.
            assert($spoof['created_by'] == $voucher_id);
            // Don't delete it, just remove it from the count
            $spoof[CCGN_GF_VOUCH_DO_YOU_VOUCH]
                = CCGN_GF_VOUCH_DO_YOU_VOUCH_REMOVED;
            GFAPI::update_entry( $spoof );
        }
        // Reset the Applicant's vouching timescale so that the vouch request
        // does not time out before the Voucher responds.
        $voucher_choices = ccgn_application_vouchers ( $applicant_id );
        $update_date = date( 'Y-m-d H:i:s', strtotime( 'now' ) );
        ccgn_set_entry_update_date( $voucher_choices, $update_date );
        // Keep an admin log.
        ccgn_entry_append_note(
            $voucher_choices,
            'REMOVING SPOOFED "CANNOT" VOUCH: To do this, we are setting its status to '
            . CCGN_GF_VOUCH_DO_YOU_VOUCH_REMOVED
            . ' at ' . $update_date
        );
        // Set user back to vouching stage if they were at the update
        // vouchers stage
        ccgn_application_to_vouching_if_no_current_cannots(
            $applicant_id
        );
        // The user can now vouch, so let them know
        //FIXME: Unless there were two declines. In which case we need to
        // store a lot more state. So ignore this for now.
        ccgn_registration_email_vouching_request(
            $applicant_id,
            $voucher_id
        );
    }
    return true;
}

////////////////////////////////////////////////////////////////////////////////
// Individual / Institution details forms
////////////////////////////////////////////////////////////////////////////////

function ccgn_details_individual_form_entry ( $applicant_id ) {
    $entries = ccgn_entries_created_by_user(
        $applicant_id,
        CCGN_GF_INDIVIDUAL_DETAILS
    );
    return $entries[ 0 ];
}

function ccgn_details_institution_form_entry ( $applicant_id ) {
    $entries = ccgn_entries_created_by_user(
        $applicant_id,
        CCGN_GF_INSTITUTION_DETAILS
    );
    return $entries[ 0 ];
}

// Handle updated individual form submission

// Handle updated institutional form submission

////////////////////////////////////////////////////////////////////////////////
// Getting an applicant's various details
////////////////////////////////////////////////////////////////////////////////

// Get the applicant details for the id

function ccgn_application_details ( $applicant_id ) {
    $entries = ccgn_entries_created_by_user(
        $applicant_id,
        CCGN_GF_APPLICANT_DETAILS
    );
    return $entries[0];
}

// Get the list of vouchers for the id.
// This is the applicant's voucher choices from the form.

function ccgn_application_vouchers ( $applicant_id ) {
    $entries = ccgn_entries_created_by_user(
        $applicant_id,
        CCGN_GF_CHOOSE_VOUCHERS
    );
    return $entries[0];
}

function ccgn_application_vouchers_users_ids ( $applicant_id ) {
    $vouchers_entry = ccgn_application_vouchers ( $applicant_id );
    $users = array();
    foreach ( CCGN_GF_VOUCH_VOUCHER_FIELDS as $field ) {
        $voucher_id = $vouchers_entry[ $field ];
        if ( $voucher_id ) {
            $users[] = $voucher_id;
        }
    }
    return $users;
}

function ccgn_application_vouchers_users ( $applicant_id ) {
    $voucher_ids = ccgn_application_vouchers_users_ids ( $applicant_id );
    $users = array();
    foreach ( $voucher_ids as $voucher_id) {
        $users[] = get_user_by('ID', $voucher_id);
    }
    return $users;
}

// BuddyPress looks for original avatar files with the "-bpfull" suffix
// so we need to be able to make names that will match this.

function ccgn_avatar_filename_bpfull ( $img_path ) {
    $pathinfo = pathinfo( $img_path );
    // Note the dot after the -bpfull suffix
    return $pathinfo[ 'filename'  ] . '-bpfull.' . $pathinfo[ 'extension' ];
}

function ccgn_application_details_avatar_filepath_o ( $img_url ) {
    $upload_dir = wp_upload_dir();
    return str_replace(
        $upload_dir[ 'baseurl' ],
        $upload_dir[ 'basedir' ],
        $img_url
    );
}

//IMPLEMENTME IF NEEDED
function ccgn_application_details_avatar_filepath_thumb ( $img_url ) {
    $upload_dir = wp_upload_dir();
    //CCGN_AVATAR_THUMBSIZE
    return str_replace(
        $upload_dir[ 'baseurl' ],
        $upload_dir[ 'basedir' ],
        $img_url
    );
}

////////////////////////////////////////////////////////////////////////////////
// Vouching and voting
////////////////////////////////////////////////////////////////////////////////

// Get the list of submitted vouches for the user

function ccgn_application_vouches ( $applicant_id ) {
    return ccgn_entries_referring_to_user (
        $applicant_id,
        CCGN_GF_VOUCH,
        CCGN_GF_VOUCH_APPLICANT_ID_FIELD
    );
}

// Count the number of vouches received

function ccgn_application_vouches_counts ( $applicant_id ) {
    $yes = 0;
    $no = 0;
    $cannot = 0;
    $vouches = ccgn_application_vouches( $applicant_id );
    // Make sure to only count one vouch for each Voucher
    // this is to avoid any glitches in submission being counted
    // If the user or an admin has updated the Voucher choices then there may
    // be more vouches than Vouchers, particularly for Cannots.
    $vouchers = [];
    foreach ($vouches as $vouch) {
        $voucher = $vouch[ 'created_by' ];
        if ( ! in_array( $voucher, $vouchers ) ) {
            $did_they = $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ];
            if ( $did_they == CCGN_GF_VOUCH_DO_YOU_VOUCH_YES ) {
                $yes += 1;
            } elseif ( $did_they == CCGN_GF_VOUCH_DO_YOU_VOUCH_NO ) {
                $no += 1;
            } elseif ( $did_they == CCGN_GF_VOUCH_DO_YOU_VOUCH_CANNOT ) {
                $cannot += 1;
            }
            $vouchers[] = $voucher;
        }
    }
    return array(
        'yes' => $yes,
        'no' => $no,
        'cannot' => $cannot
    );
}

//Return true if the applicant can be voted
function ccgn_application_can_be_voted( $applicant_id ) {
    $vouches = ccgn_application_vouches_counts( $applicant_id );
    if ($vouches['yes'] >= 2) {
        return true;
    } else {
        return false;
    }
}
// A predicate function for sorting. Is this Vouching result a 'Cannot' rather
// than a 'Yes' or a 'No'?

function ccgn_is_application_vouch_cannot ( $vouch ) {
    return $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
        == CCGN_GF_VOUCH_DO_YOU_VOUCH_CANNOT;
}

// The list of CURRENT Vouches that selected the 'Cannot' state and that must
// therefore be replaced.
// Note that Vouchers cannot change their mind at this point.
// The user id for each vouch is vouch[ 'created_by' ].

function ccgn_application_vouches_cannots( $applicant_id ) {
    // Anyone who ever vouched Cannot
    $all_cannots = array_filter(
        ccgn_application_vouches ( $applicant_id ),
        'ccgn_is_application_vouch_cannot'
    );
    // Current vouchers
    $current_voucher_ids = ccgn_application_vouchers_users_ids (
        $applicant_id
    );
    // Current Cannot vouchers
    $cannots = array();
    foreach ($all_cannots as $cannot) {
        if ( in_array( $cannot['created_by'], $current_voucher_ids )  ) {
            $cannots[] = $cannot;
        }
    }
    // FIXME: extra check that vouch time > vouch request form time
    return $cannots;
}

function ccgn_application_vouches_cannots_voucher_ids ( $applicant_id ) {
    $ids = [];
    $cannots = ccgn_application_vouches_cannots( $applicant_id );
    foreach ($cannots as $cannot) {
        $ids[] = $cannot[ 'created_by' ];
    }
    return $ids;
}

// Get the list of submitted votes for the user

function ccgn_application_votes ( $applicant_id ) {
    return ccgn_entries_referring_to_user(
        $applicant_id,
        CCGN_GF_VOTE,
        CCGN_GF_VOTE_APPLICANT_ID
    );
}

// Count the number of votes received

function ccgn_application_votes_counts ( $applicant_id ) {
    $yes = 0;
    $no = 0;
    $votes = ccgn_application_votes( $applicant_id );
    // Make sure to only count one vote for each Voter
    // this is to avoid any glitches in submission being counted
    $voters = [];
    foreach ($votes as $vote) {
        $voter = $vote[ 'created_by' ];
        if ( ! in_array( $voter, $voters ) ) {
            $did_they = $vote[
                CCGN_GF_VOTE_APPROVE_MEMBERSHIP_APPLICATION
            ];
            if ( $did_they == CCGN_GF_VOTE_APPROVED_YES ) {
                $yes += 1;
            } else  {
                $no += 1;
            }
            $voters[] = $voter;
        }
    }
    return array(
        'yes' => $yes,
        'no' => $no
    );
}

function ccgn_application_votes_for_applicant_by_user (
    $applicant_id,
    $user_id
) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_VOTE );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' => CCGN_GF_VOTE_APPLICANT_ID,
            'value' => $applicant_id
        );
    $search_criteria['field_filters'][]
        = array(
            'key' => 'created_by',
            'value' => $user_id
        );
     return GFAPI::get_entries(
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'ASC',
                'is_numeric' => false
            )
        )
    );
}
function ccgn_application_votes_by_user(
    $user_id
) {
    $form_id = RGFormsModel::get_form_id(CCGN_GF_VOTE);
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
        'key' => 'created_by',
        'value' => $user_id
    );
    $total_count = 0;
    return GFAPI::get_entries(
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'ASC',
                'is_numeric' => false
            )
        ),
        array('offset' => 0, 'page_size' => 400), 
        $total_count
    );
}

// Vote *by* current user

function ccgn_application_vote_by_current_user ( $applicant_id ) {
    $user_id = get_current_user_id();
    $entries = ccgn_application_votes_for_applicant_by_user (
        $applicant_id,
        $user_id
    );
    return $entries ? $entries[0] : false;
}


////////////////////////////////////////////////////////////////////////////////
// User profile creation based on GravityForms information
////////////////////////////////////////////////////////////////////////////////

function ccgn_set_avatar ( $entry, $applicant_id ) {
    $img_url = $entry[ CCGN_GF_DETAILS_AVATAR_FILE ];
    $img_path = ccgn_application_details_avatar_filepath_o ( $img_url );
    $avatar_dir = BP_AVATAR_UPLOAD_PATH
                . '/avatars/'
                . $applicant_id
                . '/';
    mkdir( $avatar_dir, 0777, true );
    $bpfull = ccgn_avatar_filename_bpfull ( $img_path );
    copy( $img_path, $avatar_dir . $bpfull );
}

function ccgn_create_profile_individual( $applicant_id ) {
    $details = ccgn_details_individual_form_entry ( $applicant_id );
    wp_update_user(
        array(
            'ID' => $applicant_id,
            'nickname' => $details[ CCGN_GF_DETAILS_NAME ],
            'display_name' => $details[ CCGN_GF_DETAILS_NAME ]
        )
    );
    /*xprofile_set_field_data(
        'Base',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_NAME ]
        );*/
    xprofile_set_field_data(
        'Bio',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_BIO ]
    );
    $location = $details[ CCGN_GF_DETAILS_LOCATION ];
    xprofile_set_field_data(
        'Location',
        $applicant_id,
        $location
    );
    $chapter = $details[ CCGN_GF_DETAILS_CHAPTER_INTEREST ];
    if ( $chapter == CCGN_GF_DETAILS_CHAPTER_INTEREST_SAME_AS_LOCATION ) {
        $chapter = $location;
    }
    xprofile_set_field_data(
        'Preferred Country Chapter',
        $applicant_id,
        $chapter
    );
    xprofile_set_field_data(
        'Languages',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_LANGUAGES ]
    );
    xprofile_set_field_data(
        'Links',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS ]
    );
    xprofile_set_field_data(
        'Areas of Interest',
        $applicant_id,
        // The array is in a string of form "['a', 'b', 'c']" so parse it
        json_decode($details[ CCGN_GF_DETAILS_AREAS_OF_INTEREST ])
    );
    /*if ( ! ccgn_applicant_gravatar_selected ( $applicant_id ) ) {
        ccgn_set_avatar( $details, $applicant_id );
    }*/
}

function ccgn_institutional_applicant_name ( $applicant_id ) {
    $details = ccgn_details_institution_form_entry ( $applicant_id );
    return $details[ CCGN_GF_INSTITUTION_DETAILS_NAME ];
}

function ccgn_unique_nicename ( $name ) {
    $nicename = $slug = sanitize_title_with_dashes( $name );
    $uniqueness = 2;
    // slug is nicename:
    // https://developer.wordpress.org/reference/classes/wp_user/get_data_by/
    while ( get_user_by( 'slug', $nicename ) !== false ) {
        $nicename = $slug . '-' . $uniqueness;
        $uniqueness++;
    }
    return $nicename;
}

function ccgn_create_profile_institutional ( $applicant_id ) {
    $institution_name = ccgn_institutional_applicant_name ( $applicant_id );
    wp_update_user(
        array(
            'ID' => $applicant_id,
            // Overwrite user's CCID global, as this profile is for the org
            // not the CCID user per se.
            'user_nicename' => ccgn_unique_nicename( $institution_name ),
            'nickname' => $institution_name,
            'display_name' => $institution_name
        )
    );
    xprofile_set_field_data(
        'Website',
        $applicant_id,
        $details[ CCGN_GF_INSTITUTION_DETAILS_WEB_SITE ]
    );
    xprofile_set_field_data(
        'Representative',
        $applicant_id,
        $details[ CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_NAME ]
    );
}

function ccgn_create_profile( $applicant_id ) {
    if( ccgn_user_is_individual_applicant( $applicant_id ) ) {
        ccgn_create_profile_individual( $applicant_id );
    } elseif( ccgn_user_is_institutional_applicant( $applicant_id ) ) {
        ccgn_create_profile_institutional( $applicant_id );
    } else {
        wp_die('Not an applicant');
    }
}

////////////////////////////////////////////////////////////////////////////////
// Form field population
////////////////////////////////////////////////////////////////////////////////

// This excludes the initial admin user (1) and the applicant from the list

function ccgn_registration_form_list_members ( $current_user_id ) {
    // For testing
    $include_admin = defined( 'CCGN_DEVELOPMENT' )
                   || defined( 'CCGN_TESTING' );
    $individuals = ccgn_get_individual_members();
    // Remove users who have already declined to vouch for the application
    //FIXME: Remove "No" votes as well when we allow re-voting
    //NOTE: This will only list currently live Cannots, so if e.g. the
    // Applicant has changed their Vouchers already this will not list previous
    // Cannots and the Cannots can be re-chosen. This is an edge case and the
    // existing code should not be modified to handle it, rather a new function
    // should be created if we decide that this is undesirable behaviour.
    $cannot_ids = ccgn_application_vouches_cannots_voucher_ids (
        $current_user_id
    );
    $members = array();
    foreach ( $individuals as $individual ){
        if (
            ( $individual->ID != $current_user_id ) // Exclude applicant
            && ( $include_admin // Include admin if this is set
                 || ( $individual->ID !== 1 ) )
            && ( ! in_array ( $individual->ID, $cannot_ids ) )
        ) {
            $members[] = array(
                $individual->ID,
                // Add nicename as it's unique and not the user's email address
                $individual->display_name,
                $individual->user_nicename
            );
        }
    }
    return $members;
}

function ccgn_change_vouchers_form_list_members( $current_member ) {
    //TODO
}

function ccgn_set_vouchers_options_members ( $current_user, $form ) {
    //    if( $form[ 'title' ] == CCGN_GF_CHOOSE_VOUCHERS ) {
        $members = ccgn_registration_form_list_members( $current_user );
        //    } elseif( $form[ 'title' ] == CCGN_GF_ADMIN_CHANGE_VOUCHERS ) {
        //        $members = ccgn_change_vouchers_form_list_members( $current_user );
        //    }
    return $members;
}

// Why do it like this? To save download space rather than send thousands
// of options for each of several selects.
// Search terms:
// how do I dynamically populate a gravityforms select using javascript ?

//FIXME: break out into a function that takes $members and wrap for each form

function ccgn_set_vouchers_options ( $form ) {
    if( $form[ 'title' ] == CCGN_GF_CHOOSE_VOUCHERS
        || $form[ 'title' ] == CCGN_GF_ADMIN_CHANGE_VOUCHERS ) {
        if ( $form[ 'title' ] == CCGN_GF_CHOOSE_VOUCHERS ) {
            $exclude_user = get_current_user_id();
        } else {
            $exclude_user = rgpost (
                "input_" . CCGN_GF_ADMIN_CHANGE_VOUCHERS_APPLICANT_ID
            );
        }
        $members = ccgn_set_vouchers_options_members ( $exclude_user, $form );
        ?>
        <script type="text/javascript">
        var ccgn_members = <?php echo json_encode( $members ); ?>;
        jQuery(document).ready(function(){
            gform.addFilter('gform_chosen_options', function(options, element){
                var new_options = ccgn_members.forEach(function(member){
                    return jQuery("<option></option>")
                        .attr("value", member[0])
                        .text(member[1] + " (" + member[2] + ")");
                });
                var select = jQuery(element);
                //FIXME: We should remove everything except the first element
                select.empty();
                select.append(jQuery("<option disabled selected value>Select Voucher</option>"));
                for (var i = 0; i < ccgn_members.length; i++) {
                    var member = ccgn_members[i];
                    select.append(jQuery("<option></option>")
                                  .attr("value", member[0])
                                  .text(member[1] + " (" + member[2] + ")"));
                }
                return options;
            });
        });
        </script>
        <?php
    }
    return $form;
}

function ccgn_set_vouchers_changeable ( $form ) {
    if( $form[ 'title' ] == CCGN_GF_CHOOSE_VOUCHERS ) {
        $current_user = get_current_user_id();
        $voucher_form = ccgn_application_vouchers ( $current_user );
        if ($voucher_form) {
            $cannots = ccgn_application_vouches_cannots_voucher_ids(
                $current_user
            );
            $existing = [];
            foreach(CCGN_GF_VOUCH_VOUCHER_FIELDS as $field) {
                $voucher_id = $voucher_form[ $field ];
                $voucher_cannot = in_array( $voucher_id, $cannots );
                // Remove Vouchers who have said they Cannot vouch
                if($voucher_cannot) {
                    $voucher_id = '';
                }
                $existing[] = $voucher_id;
            }
        ?>
        <script type="text/javascript">
        var ccgn_existing_choices = <?php echo json_encode( $existing ) ?>;
        jQuery(document).ready(function(){
            // We have to run this code next time around the event loop
            setTimeout(function (){
                jQuery("select").each(function(i) {
                    var doNotUpdate = (ccgn_existing_choices[i] !== '');
                    var select = jQuery(this);
                    select.val(ccgn_existing_choices[i])
                          .prop('disabled', doNotUpdate)
                          .trigger('chosen:updated');
                    // Not used on server but here to keep form validation happy
                    if(doNotUpdate){
                        jQuery('<input type="hidden" name="'
                               + select.attr('name') + '" value="'
                               + ccgn_existing_choices[i] + '">')
                            .insertAfter(select);
                    }
                })
            }, 0);
        });
      </script>
   <?php }
    }
    return $form;
}

////////////////////////////////////////////////////////////////////////////////
// Validation
// These are here as they're shared between a couple of shortcodes
////////////////////////////////////////////////////////////////////////////////

function ccgn_choose_vouchers_validate ( $validation_result ) {
    if ( ! defined( 'CCGN_DEVELOPMENT' ) ) {
        $form = $validation_result['form'];
        if( $form['title'] == CCGN_GF_CHOOSE_VOUCHERS ) {
            $vouchers = [];
            // Check for duplicate vouchers, mark as invalid if found
            foreach( $form['fields'] as &$field ) {
                if ( in_array( $field->id, CCGN_GF_VOUCH_VOUCHER_FIELDS ) ) {
                    $voucher = rgpost( "input_{$field['id']}" );
                    if ( $voucher && in_array( $voucher, $vouchers ) ) {
                        $validation_result['is_valid'] = false;
                        $field->failed_validation = true;
                        $field->validation_message = 'The same member cannot vouch you more than once!';
                    }
                    $vouchers[] = $voucher;
                }
            }
            $validation_result['form'] = $form;
        }
    }
    return $validation_result;
}

function ccgn_agree_to_terms_validate ( $validation_result ) {
    $form = $validation_result[ 'form' ];
    if( $form['title'] == CCGN_GF_AGREE_TO_TERMS ) {
        foreach( $form['fields'] as &$field ) {
            // Find the privacy policy field by ID
            if ( $field->id == CCGN_GF_DETAILS_PRIVACY_POLICY ) {
                // Options the user must agree with to agree with all of them
                $count_check = count( $field->choices );
                $total = 0;
                // Count submitted fields
                for ( $i = 1; $i <= $count_check; $i++ ) {
                    $selected = rgpost( "input_{$field['id']}_{$i}" );
                    if ( $selected ) {
                        $total++;
                    }
                }
                // Check that the user agreed with all the options
                if ( $total != $count_check ) {
                    $validation_result['is_valid'] = false;
                    $field->failed_validation = true;
                    $field->validation_message = 'You must check all the checkboxes in this section to continue!';
                    $validation_result[ 'form' ] = $form;
                }
            }
        }
    }
    return $validation_result;
}


////////////////////////////////////////////////////////////////////////////////
// Pre Approval
////////////////////////////////////////////////////////////////////////////////

function ccgn_pre_approval_entries_for ( $applicant_id ) {
    return ccgn_entries_referring_to_user(
        $applicant_id,
        CCGN_GF_PRE_APPROVAL,
        CCGN_GF_PRE_APPROVAL_APPLICANT_ID
    );
}

function ccgn_pre_approval_entry_for ( $applicant_id ) {
    $entries = ccgn_pre_approval_entries_for( $applicant_id );
    return $entries ? $entries[0] : false;
}


////////////////////////////////////////////////////////////////////////////////
// Final Approval
////////////////////////////////////////////////////////////////////////////////

function ccgn_final_approval_entries_for ( $applicant_id ) {
    return ccgn_entries_referring_to_user(
        $applicant_id,
        CCGN_GF_FINAL_APPROVAL,
        CCGN_GF_FINAL_APPROVAL_APPLICANT_ID
    );
}

function ccgn_final_approval_entry_for ( $applicant_id ) {
    $entries = ccgn_final_approval_entries_for( $applicant_id );
    return $entries ? $entries[0] : false;
}


////////////////////////////////////////////////////////////////////////////////
// Legal Approval
////////////////////////////////////////////////////////////////////////////////

function ccgn_legal_approval_entry_for ( $applicant_id ) {
    $entries = ccgn_entries_referring_to_user(
        $applicant_id,
        CCGN_GF_LEGAL_APPROVAL,
        CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID
    );
    return $entries ? $entries[0] : false;
}


////////////////////////////////////////////////////////////////////////////////
// Entry deletion and cleanup
// WARNING: Be very careful using these.
////////////////////////////////////////////////////////////////////////////////

// This really does delete the user's form entries.

function _ccgn_application_delete_entries_created_by ( $applicant_id ) {
    $entries = ccgn_entries_created_by_user(
        $applicant_id,
        false
    );
    foreach( $entries as $entry ) {
        GFAPI::delete_entry( $entry[ 'id' ] );
    }
}

// This really does delete them

function _ccgn_application_delete_entries_applicant_id (
    $form_name,
    $field_id,
    $applicant_id
) {
    $entries = ccgn_entries_referring_to_user (
        $applicant_id,
        $form_name,
        $field_id
    );
    foreach( $entries as $entry ) {
        GFAPI::delete_entry( $entry[ 'id' ] );
    }
}

// This removes the field content, not the entire entry
// (No underscore as this is intended for production code.)

function ccgn_application_erase_field_applicant_id (
    $form_name,
    $field_id_to_match_to_applicant_id,
    $field_id_to_clear,
    $applicant_id
) {
    $entries = ccgn_entries_referring_to_user (
        $applicant_id,
        $form_name,
        $field_id_to_match_to_applicant_id
    );
    foreach( $entries as $entry ) {
        unset( $entry[ $field_id_to_clear ] );
        GFAPI::update_entry( $entry );
    }
}

// Remove the applicant's application avatar

function ccgn_application_remove_avatar ( $applicant_id ) {
    $entry = ccgn_details_individual_form_entry( $applicant_id );
    if ( isset( $entry[ CCGN_GF_DETAILS_AVATAR_FILE ] ) ) {
        $original = ccgn_application_details_avatar_filepath_o ( $avatar_url );
        if ( file_exists( $original ) ) {
            unlink( $original );
        }
        $thumb = ccgn_application_details_avatar_filepath_thumb ( $avatar_url );
        if ( file_exists( $thumb ) ) {
            unlink( $thumb );
        }
        GFAPI::update_entry_field(
            $entry[ 'id' ],
            CCGN_GF_DETAILS_AVATAR_FILE,
            null
        );
    }
}

////////////////////////////////////////////////////////////////////////////////
// Cron
////////////////////////////////////////////////////////////////////////////////

function ccgn_cleanup_approved_user_records ( $applicant_id ) {
    // Keep application details
    // Scrub Vouch *reasons*
}

function ccgn_cleanup_declined_user_records ( $applicant_id ) {
    // Delete user application form entries
    // Delete vouch entries for user
}

function ccgn_cleanup_old_records () {
    $search_criteria = array();
    $end_date = date( 'Y-m-d', strtotime( '-${CCGN_CLEANUP_DAYS} days' ) );
    $entries = GFAPI::get_entries( CCGN_GF_FINAL_APPROVAL, $search_criteria );
    foreach ( $entries as $entry ) {
        $applicant_id = $entry[ CCGN_GF_FINAL_APPROVAL_APPLICANT_ID ];
        $status = $entry[
            CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $status == CCGN_GF_FINAL_APPROVAL_APPROVED_YES ) {
            ccgn_cleanup_approved_user_records( $applicant_id );
        } else {
            ccgn_cleanup_declined_user_records( $applicant_id );
        }
    }
}

function ccgn_schedule_cleanup () {
    if (! wp_next_scheduled ( 'daily', 'ccgn_cleanup_old_records_event' ) ) {
        wp_schedule_event( time(), 'daily', 'ccgn_cleanup_old_records_event' );
    }
}

function ccgn_schedule_remove_cleanup () {
    wp_clear_scheduled_hook( 'ccgn_cleanup_old_records_event' );
}

////////////////////////////////////////////////////////////////////////////////
// Gravatar Checking
////////////////////////////////////////////////////////////////////////////////

function ccgn_user_gravatar_url ( $user_id, $size="80", $default="404" ) {
    $email = get_userdata( $user_id )->user_email;
    return "https://www.gravatar.com/avatar/"
        . md5( strtolower( trim( $email ) ) )
        . "?d=" . urlencode( $default )
        . "&s=" . $size;
}

// If the user does not have a gravatar, this will display the mystery person

function ccgn_user_gravatar_img ( $user_id, $size ) {
    return '<img src="' . ccgn_user_gravatar_url ( $user_id, $size, "mm" )
                        . '" width="' . $size . '" height="' . $size . '"/>';
}

function ccgn_user_gravatar_exists ( $user_id ) {
    $url = ccgn_user_gravatar_url ( $user_id, "1", "404" );
    list($status) = get_headers( $url );
    return strpos( $status, '404' ) === false;
}

function ccgn_applicant_gravatar_selected ( $applicant_id ) {
    $details = ccgn_details_individual_form_entry ( $applicant_id );
    $source = $details[ CCGN_GF_DETAILS_AVATAR_SOURCE ];
    return $source == CCGN_GF_DETAILS_AVATAR_SOURCE_GRAVATAR;
}

////////////////////////////////////////////////////////////////////////////////
// More Voucher handling
// It's here because it needs to be shared between individual and institutional
// application forms.
////////////////////////////////////////////////////////////////////////////////

// Handle vouch form updating (if one or more Vouchers said they Cannot vouch)

// Check whether the Voucher is eligible to be chosen by the Applicant when
// they are updating their Voucher requests.
// This does not check whether the Voucher is already in another field of the
// form, we rely on GravityForms to detect that.
// This is also used in admin/user-vouchers-change-page.php

function ccgn_voucher_can_be_chosen_by_applicant( $voucher_id,
                                                  $applicant_id ) {
    $result = false;
    // Make sure the Voucher exists
    $voucher = get_user_by('ID', $voucher_id);
    if ( ! empty( $voucher ) ) {
        // Make sure the Voucher can vouch
        if ( ccgn_member_is_individual ( $voucher_id ) ) {
            // Make sure the Voucher has not already vouched for the Applicant
            // This code is also in the "is open" function above but we may
            // need to change that, so it is inlined here
            $entries = ccgn_vouches_for_applicant_by_voucher(
                $applicant_id,
                $voucher_id
            );
            if ( $entries == [] ) {
                $result = true;
            }
        }
    }
    return $result;
}

// Update the Entry fields if they have been changed

function ccgn_choose_vouchers_maybe_update_voucher ( & $editentry, $num ) {
    $result = false;
    $field = 'input_' . $num;
    $voucher = $_POST[ $field ];
    $applicant = $editentry[ 'created_by' ];
    if ( ccgn_voucher_can_be_chosen_by_applicant (
        $voucher,
        $applicant
    ) ) {
        // Don't mark unchanged fields as updated
        if ( ! ( $editentry[ $num ] == $voucher ) ) {
            $editentry[ $num ] = $voucher;
            $result = true;
        }
    }
    return $result;
}

// Catch updated form submissions and process them

function ccgn_choose_vouchers_pre_submission ( $form ) {
    if ( $form[ 'title' ] == CCGN_GF_CHOOSE_VOUCHERS ) {
        $applicant_id = get_current_user_id();
        $stage = ccgn_registration_user_get_stage( $applicant_id );
        // Make sure the user isn't spoofing a Voucher update
        if ($stage == CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS ) {
            die();
        }
        // Check to see if the user is updating the form
        $editentry = ccgn_application_vouchers( $applicant_id );
        if ( $editentry ) {
            // The user should only be updating the form in the correct state.
            if ( $stage != CCGN_APPLICATION_STATE_UPDATE_VOUCHERS ) {
                echo "Something went badly wrong.";
            } else {
                $should_update = false;
                $new_vouchers = [];
                // Check if each field has updated, note which have
                foreach (CCGN_GF_VOUCH_VOUCHER_FIELDS as $vf) {
                    $voucher_changed
                        =  ccgn_choose_vouchers_maybe_update_voucher (
                            $editentry,
                            $vf
                        );
                    if ($voucher_changed) {
                        $new_vouchers[] = $editentry[ $vf ];
                    }
                    $should_update |= $voucher_changed;
                }
                if ($should_update) {
                    // Update the entry
                    $updateit = GFAPI::update_entry( $editentry );
                    if ( is_wp_error( $updateit ) ) {
                        echo "Something went badly wrong.";
                    } else {
                        // Email any new Vouchers a Vouching Request
                        foreach ($new_vouchers as $voucher_id) {
                            ccgn_registration_email_vouching_request(
                                $applicant_id,
                                $voucher_id
                            );
                        }
                        ccgn_application_to_vouching_if_no_current_cannots(
                            $applicant_id
                        );
                        //Success, so redirect
                        header( "Location: " . get_permalink() );
                    }
                } else {
                    // Try again
                    header( "Location: " . get_permalink() );
                }
            }
            //dont process and create new entry
            die();
        }
    }
}

// COMPUTATIONALLY EXPENSIVE

function ccgn_members_with_most_open_vouch_requests () {
    $open_requests = array();
    // Get applicants in the vouching state
    $applicants = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    // Get vouch requests for each applicant
    foreach ( $applicants as $applicant_id ) {
        $vouchers = ccgn_application_vouchers_users_ids ( $applicant_id );
        foreach ( $vouchers as $voucher_id ) {
            $vouches = ccgn_vouches_for_applicant_by_voucher (
                $applicant_id,
                $voucher_id
            );
            // If the voucher has not vouched
            if ( $vouches == [] ) {
                if ( ! isset( $open_requests[ $voucher_id ] ) ) {
                    $open_requests[ $voucher_id ] =  array();
                }
                $open_requests[ $voucher_id ][] = $applicant_id;
            }
        }
    }
    return $open_requests;
}

// BEGIN REMOVE AFTER UPDATE

// COMPUTATIONALLY EXPENSIVE
// Return an associative array of applicant user id =>
// UNSORTED KEYS AND VALUES: [unreplaced declined voucher => date declined]
// where the voucher request was created more than $days ago

function ccgn_vouch_is_old_cannot ( $vouch, $cutoff ) {
    // This is simple string comparison but it is OK with the date format
    return ( ccgn_entry_created_or_updated( $vouch ) < $cutoff )
        && (
            $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
            == CCGN_GF_VOUCH_DO_YOU_VOUCH_CANNOT
        );
}

function ccgn_applicant_append_old_cannots (
    & $applicants_old_requests,
    $applicant_id,
    $voucher_id,
    $vouch,
    $cutoff
)  {
    if ( ccgn_vouch_is_old_cannot( $vouch, $cutoff ) ) {
        $vouch_date = ccgn_entry_created_or_updated( $vouch );
        // Declined? Start or add to the id/date map
        if ( ! isset( $applicants_old_requests[ $applicant_id ] ) ) {
            $applicants_old_requests[ $applicant_id ] = array();
        }
        $applicants_old_requests[ $applicant_id ][$voucher_id] = $vouch_date;
    }
}

function ccgn_applicants_append_old_cannots (
    & $applicants_old_requests,
    $applicant_id,
    $cutoff
) {
    // NOTE: this means we will only have live Cannots, meaning Cannots
    // from currently selected vouchers. THIS IS WHAT WE WANT, and the code
    // should not be changed without analysing and changing code that calls
    // this but it does mean that historic Cannots are not included.
    $vouchers_ids = ccgn_application_vouchers_users_ids ( $applicant_id );
    // Get vouches by each requested voucher
    foreach ( $vouchers_ids as $voucher_id ) {
        $vouches = ccgn_vouches_for_applicant_by_voucher (
            $applicant_id,
            $voucher_id
        );
        // If there's a vouch
        if ( $vouches != [] ) {
            // There should only be one vouch for each request, but just in case
            $vouch = $vouches[ 0 ];
            // Append any old cannots
            ccgn_applicant_append_old_cannots (
                $applicants_old_requests,
                $applicant_id,
                $voucher_id,
                $vouch,
                $cutoff
            );
        }
    }
}

function ccgn_applicants_with_current_cannot_vouches_older_than ( $days ) {
    $cutoff = date('Y-m-d H:i:s', strtotime($days . ' days ago'));
    $applicants_old_requests = array();
    // Get applicants in the vouching state
    // This constraint is amazingly important, do not change this without
    // looking at all callers of this function.
    $applicants = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    // Get vouch requests for each applicant
    foreach ( $applicants as $applicant_id ) {
        ccgn_applicants_append_old_cannots(
            $applicants_old_requests,
            $applicant_id,
            $cutoff
        );
    }
    return $applicants_old_requests;
}

function _one_time_fix_for_cannot_vouch_state () {
    // Get all users with old cannots but in vouching state
    $applicants = ccgn_applicants_with_current_cannot_vouches_older_than ( 0 );
    // set to update vouching IF currently vouching
    foreach($applicants as $applicant_id => $vouches) {
        error_log( $applicant_id );
        $stage = ccgn_registration_user_get_stage( $applicant_id );
        if ( $stage == CCGN_APPLICATION_STATE_VOUCHING ) {
            ccgn_registration_user_set_stage(
                $applicant_id,
                CCGN_APPLICATION_STATE_UPDATE_VOUCHERS );
        }
    }
}

// END REMOVE AFTER UPDATE

////////////////////////////////////////////////////////////////////////////////
// Vouch presentation
////////////////////////////////////////////////////////////////////////////////

// Format the list of vouches the member has received from their vouchers

function ccgn_application_users_page_vouch_responses (
    $applicant_id,
    $full_date = false
) {
    $result = '';
    $vouches = ccgn_application_vouches ( $applicant_id );
    foreach ( $vouches as $vouch ) {
        $voucher = get_user_by( 'ID', $vouch[ 'created_by' ] );
        $vouch_date = ccgn_entry_created_or_updated( $vouch );
        if ( ! $full_date ) {
            $vouch_date = explode( ' ', $vouch_date )[ 0 ];
        }
        $result .=
                '<h4>From: '
                . $voucher->display_name
                . '</h4><p><strong>Date:</strong>'
                // Just the date, not the time
                . $vouch_date
                .'</p><p><strong>Vouched:</strong> '
                . $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
                . '</p><p><strong>Reason:</strong> '
                . $vouch[ CCGN_GF_VOUCH_REASON ]
                . '</p>';
    }
    return $result;
}
//The same function up here but we get the result on an array
//in order to get only the data
function ccgn_application_users_page_vouch_responses_data(
    $applicant_id,
    $full_date = false
) {
    $result = '';
    $vouches = ccgn_application_vouches($applicant_id);
    $vouches_list = array();
    foreach ($vouches as $vouch) {
        $voucher = get_user_by('ID', $vouch['created_by']);
        $vouch_date = ccgn_entry_created_or_updated($vouch);
        if (!$full_date) {
            $vouch_date = explode(' ', $vouch_date)[0];
        }
        $the_vouch = array();
        $the_vouch['id'] = $vouch['created_by'];
        $the_vouch['name'] = $voucher->display_name;
        $the_vouch['date'] = $vouch_date;
        $the_vouch['vouched'] = $vouch[CCGN_GF_VOUCH_DO_YOU_VOUCH];
        $the_vouch['reason'] = $vouch[CCGN_GF_VOUCH_REASON];
        $the_vouch['reason_no'] = $vouch[CCGN_GF_VOUCH_REASON_NO];

        $vouches_list[] = $the_vouch;
    }
    return $vouches_list;
}

// Format the count of vouches

function ccgn_application_users_page_vouch_counts ( $applicant_id ) {
    $counts = ccgn_application_vouches_counts( $applicant_id );
    return '<p><span class="dashicons dashicons-warning"></span> <strong>Cannot: </strong>'
        . $counts['cannot']
        . '<p><span class="dashicons dashicons-yes"></span> <strong>Yes: </strong>'
        . $counts['yes']
        . '<p><span class="dashicons dashicons-no"></span> <strong>No: </strong>'
        . $counts['no']
        . '</p>';
}

// Format the list of members the applicant has asked to vouch for them

function ccgn_application_users_page_vouchers ( $applicant_id ) {
    $result = '';
    $vouchers = ccgn_application_vouchers_users ( $applicant_id );
    $vouch_data = ccgn_application_users_page_vouch_responses_data(
        $applicant_id,
        true
    );
    $position = 1;
    foreach ( $vouchers as $voucher ) {
        $data = array();
        $other_voucher = 0; // get the other voucher ID in case if vouched is "yes". this means that we have to disable the name in the select box
        foreach ($vouch_data as $vouch_item) {
            if ($voucher->ID == $vouch_item['id']) {
                $data = $vouch_item;
            } else if ( ($voucher->ID != $vouch_item['id']) && ($vouch_item['vouched'] == 'Yes') ) {
                $other_voucher = $vouch_item['id'];
            }
        }
        $action_button = (ccgn_current_user_is_final_approver() && ($data['vouched'] != 'Yes') && ($data['vouched'] != 'No') ) ? ' <button class="button tiny action-change-voucher" onclick="$.changeVoucher('.$applicant_id.',\''.$voucher->display_name.'\','.$voucher->ID.','.$other_voucher.','.$position.')">Change</button>' : '';
        $result .= '<p> <span class="dashicons dashicons-admin-users"></span> ' . $voucher->display_name  . $action_button .'</p>';
        $position++;
    }
    return $result;
}

////////////////////////////////////////////////////////////////////////////////
// Reporting
////////////////////////////////////////////////////////////////////////////////

// Date must be yyyy-mm-dd

// Individual applications finish with final approval,
// Institutional applications finish with legal approval.

function _ccgn_new_final_approval_entries_since (
    $start_date,
    $end_date,
    $approval
) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_FINAL_APPROVAL );
    $search_criteria = array (
        'field_filters' => array (
            array(
                'key' => CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION,
                'value' => $approval
            ),
        )
    );
    if( $start_date ) {
        $search_criteria[ 'start_date' ] = $start_date;
    } else {
        $search_criteria[ 'start_date' ] = CCGN_SITE_EPOCH;
    }
    if( $end_date ) {
        $search_criteria[ 'end_date' ] = $end_date;
    } else {
        $search_criteria[ 'end_date' ] = date( 'Y-m-d', time() );
    }
    return ccgn_gf_get_paged_all (
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );
}

function ccgn_new_final_approvals_since ( $start_date, $end_date ) {
    return _ccgn_new_final_approval_entries_since (
        $start_date,
        $end_date,
        CCGN_GF_FINAL_APPROVAL_APPROVED_YES
    );
}

function ccgn_new_final_approvals_declined_since ( $start_date, $end_date ) {
    return _ccgn_new_final_approval_entries_since (
        $start_date,
        $end_date,
        CCGN_GF_FINAL_APPROVAL_APPROVED_NO
    );
}

function _ccgn_new_legal_approval_entries_since (
    $start_date,
    $end_date,
    $approval
) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_LEGAL_APPROVAL );
    $search_criteria = array (
        'field_filters' => array (
            array(
                'key' => CCGN_GF_LEGAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION,
                'value' => $approval
            ),
        )
    );
    if( $start_date ) {
        $search_criteria[ 'start_date' ] = $start_date;
    } else {
        $search_criteria[ 'start_date' ] = CCGN_SITE_EPOCH;
    }
    if( $end_date ) {
        $search_criteria[ 'end_date' ] = $end_date;
    } else {
        $search_criteria[ 'end_date' ] = date( 'Y-m-d', time() );
    }
    return ccgn_gf_get_paged_all (
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );
}

function ccgn_new_legal_approvals_since ( $start_date, $end_date ) {
    return _ccgn_new_legal_approval_entries_since (
        $start_date,
        $end_date,
        CCGN_GF_LEGAL_APPROVAL_APPROVED_YES
    );
}

function ccgn_new_legal_approvals_declined_since ( $start_date, $end_date ) {
    return _ccgn_new_legal_approval_entries_since (
        $start_date,
        $end_date,
        CCGN_GF_LEGAL_APPROVAL_APPROVED_NO
    );
}

/**
 * Limit How Many Checkboxes Can Be Checked
 * https://gravitywiz.com/2012/06/11/limiting-how-many-checkboxes-can-be-checked/
 */
class GFLimitCheckboxes
{
    private $form_id;
    private $field_limits;
    private $output_script;
    function __construct($form_id, $field_limits)
    {
        $this->form_id = $form_id;
        $this->field_limits = $this->set_field_limits($field_limits);
        add_filter("gform_pre_render_$form_id", array(&$this, 'pre_render'));
        add_filter("gform_validation_$form_id", array(&$this, 'validate'));
    }
    function pre_render($form)
    {
        $script = '';
        $output_script = false;
        foreach ($form['fields'] as $field) {
            $field_id = $field['id'];
            $field_limits = $this->get_field_limits($field['id']);
            if (!$field_limits                                          // if field limits not provided for this field
            || RGFormsModel::get_input_type($field) != 'checkbox'   // or if this field is not a checkbox
            || !isset($field_limits['max'])        // or if 'max' is not set for this field
            )
                continue;
            $output_script = true;
            $max = $field_limits['max'];
            $selectors = array();
            foreach ($field_limits['field'] as $checkbox_field) {
                $selectors[] = "#field_{$form['id']}_{$checkbox_field} .gfield_checkbox input:checkbox";
            }
            $script .= "jQuery(\"" . implode(', ', $selectors) . "\").checkboxLimit({$max});";
        }
        GFFormDisplay::add_init_script($form['id'], 'limit_checkboxes', GFFormDisplay::ON_PAGE_RENDER, $script);
        if ($output_script) :
        ?>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $.fn.checkboxLimit = function(n) {
                    var checkboxes = this;
                    this.toggleDisable = function() {
                        // if we have reached or exceeded the limit, disable all other checkboxes
                        if(this.filter(':checked').length >= n) {
                            var unchecked = this.not(':checked');
                            unchecked.prop('disabled', true);
                        }
                        // if we are below the limit, make sure all checkboxes are available
                        else {
                            this.prop('disabled', false);
                        }
                    }
                    // when form is rendered, toggle disable
                    checkboxes.bind('gform_post_render', checkboxes.toggleDisable());
                    // when checkbox is clicked, toggle disable
                    checkboxes.click(function(event) {
                        checkboxes.toggleDisable();
                        // if we are equal to or below the limit, the field should be checked
                        return checkboxes.filter(':checked').length <= n;
                    });
                }
            });
            </script>

            <?php
            endif;
            return $form;
        }
        function validate($validation_result)
        {
            $form = $validation_result['form'];
            $checkbox_counts = array();
        // loop through and get counts on all checkbox fields (just to keep things simple)
            foreach ($form['fields'] as $field) {
                if (RGFormsModel::get_input_type($field) != 'checkbox')
                    continue;
                $field_id = $field['id'];
                $count = 0;
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "input_{$field['id']}_") !== false)
                        $count++;
                }
                $checkbox_counts[$field_id] = $count;
            }
        // loop through again and actually validate
            foreach ($form['fields'] as &$field) {
                if (!$this->should_field_be_validated($form, $field))
                    continue;
                $field_id = $field['id'];
                $field_limits = $this->get_field_limits($field_id);
                $min = isset($field_limits['min']) ? $field_limits['min'] : false;
                $max = isset($field_limits['max']) ? $field_limits['max'] : false;
                $count = 0;
                foreach ($field_limits['field'] as $checkbox_field) {
                    $count += rgar($checkbox_counts, $checkbox_field);
                }
                if ($count < $min) {
                    $field['failed_validation'] = true;
                    $field['validation_message'] = sprintf(_n('You must select at least %s item.', 'You must select at least %s items.', $min), $min);
                    $validation_result['is_valid'] = false;
                } else if ($count > $max) {
                    $field['failed_validation'] = true;
                    $field['validation_message'] = sprintf(_n('You may only select %s item.', 'You may only select %s items.', $max), $max);
                    $validation_result['is_valid'] = false;
                }
            }
            $validation_result['form'] = $form;
            return $validation_result;
        }
        function should_field_be_validated($form, $field)
        {
            if ($field['pageNumber'] != GFFormDisplay::get_source_page($form['id']))
                return false;
        // if no limits provided for this field
            if (!$this->get_field_limits($field['id']))
                return false;
        // or if this field is not a checkbox
            if (RGFormsModel::get_input_type($field) != 'checkbox')
                return false;
        // or if this field is hidden
            if (RGFormsModel::is_field_hidden($form, $field, array()))
                return false;
            return true;
        }
        function get_field_limits($field_id)
        {
            foreach ($this->field_limits as $key => $options) {
                if (in_array($field_id, $options['field']))
                    return $options;
            }
            return false;
        }
        function set_field_limits($field_limits)
        {
            foreach ($field_limits as $key => &$options) {
                if (isset($options['field'])) {
                    $ids = is_array($options['field']) ? $options['field'] : array($options['field']);
                } else {
                    $ids = array($key);
                }
                $options['field'] = $ids;
            }
            return $field_limits;
        }
}
// new GFLimitCheckboxes(52, array(
//     3 => array(
//         'min' => 3,
//         'max' => 3
//     )
// ));