<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Constants and functions for interacting with GravityForms.
// We handle forms, fields and entries here.
// Note that we don't handle multiple applications by the same user yet,
// but code that sorts by date before returning the zeroth item is boilerplate
// to handle that feature.
////////////////////////////////////////////////////////////////////////////////

// Applicant initial application fields

define( 'COMMONERS_GF_AGREE_TO_TERMS', 'Agree To Terms' );
define( 'COMMONERS_GF_APPLICANT_DETAILS', 'Applicant Details' );
define( 'COMMONERS_GF_CHOOSE_VOUCHERS', 'Choose Vouchers' );

// Member vouching for applicant

define( 'COMMONERS_GF_VOUCH', 'Vouch For Applicant' );

// Admin approval of applicant

define( 'COMMONERS_GF_PRE_APPROVAL', 'Pre Approval' );
define( 'COMMONERS_GF_FINAL_APPROVAL', 'Final Approval' );

// Individual fields in forms

define( 'COMMONERS_GF_DETAILS_NAME', '1' );
define( 'COMMONERS_GF_DETAILS_BIO', '2' );
define( 'COMMONERS_GF_DETAILS_AREAS_OF_INTEREST', '5' );
define( 'COMMONERS_GF_DETAILS_LANGUAGES', '6' );
define( 'COMMONERS_GF_DETAILS_LOCATION', '7' );
define( 'COMMONERS_GF_DETAILS_NATIONALITY', '8' );
define( 'COMMONERS_GF_DETAILS_SOCIAL_MEDIA_URLS', '9' );
define( 'COMMONERS_GF_DETAILS_AVATAR_FILE', '10' );

define( 'COMMONERS_GF_VOUCH_APPLICANT_ID', 'applicant_id' );
define( 'COMMONERS_GF_VOUCH_DO_YOU_VOUCH', '3' );
define( 'COMMONERS_GF_VOUCH_REASON', '4' );

define( 'COMMONERS_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '2' );
define( 'COMMONERS_GF_FINAL_APPROVAL_APPLICANT_ID', '4' );

// Field values that we need to check

define( 'COMMONERS_GF_VOUCH_DO_YOU_VOUCH_YES', 'Yes' );
define( 'COMMONERS_GF_FINAL_APPROVAL_APPROVED_YES', 'Yes' );

// The field IDs containing user names in the "Choose Vouchers" form

define(
    'COMMONERS_GF_VOUCH_VOUCHER_FIELDS',
    [ '1', '2', '3', '4', '5' ]
);


////////////////////////////////////////////////////////////////////////////////
// Vouching form
////////////////////////////////////////////////////////////////////////////////

// Find the applicant's choices of vouchers in Gravity Forms

function commoners_vouching_request_entry ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( COMMONERS_GF_CHOOSE_VOUCHERS );
    $entries = GFAPI::get_entries(
        $form_id,
        array(
            'created_by' => $applicant_id,
        ),
        array(
            array(
                'key' => 'date',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );

    return $entries[ 0 ];
}

// Has the voucher already responded to applicant's vouching request?

function commoners_vouching_request_open ( $applicant_id, $voucher_id ) {
    $form_id = RGFormsModel::get_form_id( COMMONERS_GF_VOUCH );
    $entries = GFAPI::get_entries(
        $form_id,
        array(
            'created_by' => $voucher_id,
            'applicant_id' => $applicant_id
        )
    );
    return $entries == [];
}

////////////////////////////////////////////////////////////////////////////////
// Getting an applicant's various details
////////////////////////////////////////////////////////////////////////////////

// Get the applicant details for the id

function commoners_application_details ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( COMMONERS_GF_APPLICANT_DETAILS );
    $entries = GFAPI::get_entries(
        $form_id,
        array(
            'created_by' => $applicant_id,
        ),
        array(
            array(
                'key' => 'date',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );

    return $entries[0];
}

// Get the list of vouchers for the id

function commoners_application_vouchers ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( COMMONERS_GF_CHOOSE_VOUCHERS );
    $entries = GFAPI::get_entries(
        $form_id,
        array(
            'created_by' => $applicant_id,
        ),
        array(
            array(
                'key' => 'date',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );

    return $entries[0];
}

// Get the list of submitted vouches for the user

function commoners_applicantion_vouches ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( COMMONERS_GF_VOUCH );
    $entries = GFAPI::get_entries(
        $form_id,
        array(
            'created_by' => $applicant_id,
        ),
        array(
            array(
                'key' => 'date',
                'direction' => 'ASC',
                'is_numeric' => false
            )
        )
    );

    return $entries;
}

// Count the number of vouches received

function commoners_applicantion_vouches_counts ( $applicant_id ) {
    $yes = 0;
    $no = 0;
    $vouches = commoners_applicantion_vouches( $applicant_id );
    foreach ($vouches as $vouch) {
        $did_they = rgar( $vouch, COMMONERS_GF_VOUCH_DO_YOU_VOUCH );
        if ( $did_they == COMMONERS_GF_VOUCH_DO_YOU_VOUCH_YES ) {
            $yes += 1;
        } else  {
            $no += 1;
        }
    }
    return array(
        'yes' => $yes,
        'no' => $no
    );
}

////////////////////////////////////////////////////////////////////////////////
// User profile creation based on GravityForms information
////////////////////////////////////////////////////////////////////////////////

function commoners_create_profile( $applicant ) {
    if ( $applicant == 0 ) {
        echo 'Could not get user to create BuddyPress profile for.';
    }
    $details = commoners_application_details ( $applicant_id );
    xprofile_set_field_data(
        'Short Bio',
        $applicant->id,
        $details[ COMMONERS_GF_DETAILS_BIO ]
    );
    xprofile_set_field_data(
        'Location',
        $applicant->id,
        $details[ COMMONERS_GF_DETAILS_LOCATION ]
    );
    xprofile_set_field_data(
        'Short Bio',
        $applicant->id,
        $details[ COMMONERS_GF_DETAILS_LANGUAGES ]
    );
    // FINISH ME
}