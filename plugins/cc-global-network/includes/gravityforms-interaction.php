<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Constants and functions for interacting with GravityForms.
// We handle forms, fields and entries here.
// Note that we don't handle multiple applications by the same user yet,
// but code that sorts by date before returning the zeroth item is boilerplate
// to handle that feature.
////////////////////////////////////////////////////////////////////////////////

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

define( 'CCGN_GF_INSTITUTION_DETAILS_NAME', '1' );
define( 'CCGN_GF_INSTITUTION_DETAILS_WEB_SITE', '2' );
define( 'CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_NAME', '3' );
define( 'CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_EMAIL', '4' );
define( 'CCGN_GF_INSTITUTION_DETAILS_EXISTING_MEMBERS', '5' );
define( 'CCGN_GF_INSTITUTION_DETAILS_STATEMENT', '6' );
define( 'CCGN_GF_INSTITUTION_DETAILS_IS_AFFILIATE', '7' );
define( 'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS', '9' );
define( 'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_DOMAIN_NAME', '8' );
define( 'CCGN_GF_INSTITUTION_DETAILS_ADDITIONAL_INFO', '10' );
define( 'CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_TRADEMARK', '11' );

define( 'CCGN_GF_VOUCH_APPLICANT_ID', 'applicant_id' );
define( 'CCGN_GF_VOUCH_APPLICANT_ID_FIELD', '7' );
define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH', '3' );
define( 'CCGN_GF_VOUCH_REASON', '4' );

define( 'CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_ID', '4' );

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
define( 'CCGN_GF_PRE_APPROVAL_APPROVED_YES', 'Yes' );
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

// Fields to display in applicant/voucher profiles

define(
    'CCGN_GF_DETAILS_VOUCH_MAP',
    [
        [ 'Applicant Name', CCGN_GF_DETAILS_NAME ],
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
        [ 'Applicant Name', CCGN_GF_DETAILS_NAME ],
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
        [ 'Institution Name', CCGN_GF_INSTITUTION_DETAILS_NAME ],
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
        [ 'Institution Name', CCGN_GF_INSTITUTION_DETAILS_NAME ],
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
// Form cleanup
////////////////////////////////////////////////////////////////////////////////

// The number of days to wait before cleaning up application records
define( 'CCGN_CLEANUP_DAYS', 21 );

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
                'key' => 'date',
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
                'key' => 'date',
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
    $vouchers = [];
    foreach ($vouches as $vouch) {
        $voucher = $vouch[ 'created_by' ];
        if ( ! in_array( $voucher, $vouchers ) ) {
            $did_they = $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ];
            if ( $did_they == CCGN_GF_VOUCH_DO_YOU_VOUCH_YES ) {
                $yes += 1;
            } elseif ( $did_they == CCGN_GF_VOUCH_DO_YOU_VOUCH_YES ) {
                $no += 1;
            } else {
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

// Does the user have any Vouchers who have declined to vouch?

function ccgn_application_vouches_has_cannots( $applicant_id ) {
    return ccgn_application_vouches_counts ( $applicant_id )[ 'cannot' ] > 0;
}

// Does the current state of the Vouching request form contain any Vouchers
// who have declined to vouch?

function ccgn_application_choose_vouchers_form_has_cannots( $applicant_id ) {
    $vouchers = ccgn_application_vouchers_users_ids ( $applicant_id );
    $cannots = ccgn_application_vouches_cannots_voucher_ids ( $applicant_id );
    return array_intersect($vouchers, $cannots) != [];
}

// A predicate function for sorting. Is this Vouching result a 'Cannot' rather
// than a 'Yes' or a 'No'?

function ccgn_is_application_vouch_cannot ( $vouch ) {
    return $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
        == CCGN_GF_VOUCH_DO_YOU_VOUCH_CANNOT;
}

// The list of Vouches that selected the 'Cannot' state and that must therefore
// be replaced.
// Note that Vouchers cannot change their mind at this point.
// The user id for each vouch is vouch[ 'created_by' ].

function ccgn_application_vouches_cannots( $applicant_id ) {
    return array_filter(
        ccgn_application_vouches ( $applicant_id ),
        'ccgn_is_application_vouch_cannot'
    );
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
                'key' => 'date',
                'direction' => 'ASC',
                'is_numeric' => false
            )
        )
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
    xprofile_set_field_data(
        'Location',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_LOCATION ]
    );
    xprofile_set_field_data(
        'Preferred Country Chapter',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_CHAPTER_INTEREST ]
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
    $institution_name = $details[ CCGN_GF_INSTITUTION_DETAILS_NAME ];
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
    $institution_name = ccgn_institutional_applicant_name ( $applicant_id )
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

function ccgn_get_individuals () {
    // FIXME: Filter admin, council members, inactive members
    // INITIAL PHASE: Council members can be asked to Vouch
    return bp_core_get_users(
        array(
            'type' => 'alphabetical',
            'per_page' => '9999999',
            'member_type' => 'individual-member'
        )
    )["users"];
}

// This excludes the initial admin user (1) and the applicant from the list

function ccgn_registration_form_list_members ( $current_user_id ) {
    // For testing
    $include_admin = defined( 'CCGN_DEVELOPMENT' )
                   || defined( 'CCGN_TESTING' );
    $individuals = ccgn_get_individuals();
    // Remove users who have already declined to vouch for the application
    //FIXME: Remove "No" votes as well when we allow re-voting
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
    wp_schedule_event( time(), 'daily', 'ccgn_cleanup_old_records' );
}

function ccgn_schedule_remove_cleanup () {
    $timestamp = wp_next_scheduled( 'ccgn_cleanup_old_records' );
    $original_args = array();
    wp_unschedule_event(
        $timestamp,
        'ccgn_cleanup_old_records',
        $original_args
    );
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
        // Check to see if the user is updating the form
        $editentry = ccgn_application_vouchers( $applicant_id );
        if ( $editentry ) {
            // The user should only be updating the form if a Voucher(s)
            // has said that they cannot vouch for this application.
            if ( ! ccgn_application_choose_vouchers_form_has_cannots(
                $applicant_id
            ) ) {
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

////////////////////////////////////////////////////////////////////////////////
// Vouch presentation
////////////////////////////////////////////////////////////////////////////////

// Format the list of vouches the member has received from their vouchers

function ccgn_application_users_page_vouch_responses ( $applicant_id ) {
    $result = '';
    $vouches = ccgn_application_vouches ( $applicant_id );
    foreach ($vouches as $vouch) {
        $voucher = get_user_by('ID', $vouch['created_by']);
        $result .=
                '<h4>From: '
                . $voucher->display_name
                . '</h4><p><strong>Vouched:</strong> '
                . $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
                . '</p><p><strong>Reason:</strong> '
                . $vouch[ CCGN_GF_VOUCH_REASON ]
                . '</p>';
    }
    return $result;
}

// Format the count of vouches

function ccgn_application_users_page_vouch_counts ( $applicant_id ) {
    $counts = ccgn_application_vouches_counts( $applicant_id );
    return '<p><strong>Cannot: </strong>'
        . $counts['cannot']
        . '<p><strong>Yes: </strong>'
        . $counts['yes']
        . '<p><strong>No: </strong>'
        . $counts['no']
        . '</p>';
}

// Format the list of members the applicant has asked to vouch for them

function ccgn_application_users_page_vouchers ( $applicant_id ) {
    $result = '';
    $vouchers = ccgn_application_vouchers_users ( $applicant_id );
    foreach ( $vouchers as $voucher ) {
        $result .= '<p>' . $voucher->display_name  . '</p>';
    }
    return $result;
}

////////////////////////////////////////////////////////////////////////////////
// Reporting
////////////////////////////////////////////////////////////////////////////////

// Date must be yyyy-mm-dd

function ccgn_new_individual_members_since ( $start_date, $end_date ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_FINAL_APPROVAL );
    $search_criteria = array (
        'start_date' => $start_date,
        'end_date' => $end_date,
        'field_filters' => array (
            array(
                'key' => CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION,
                'value' => CCGN_GF_FINAL_APPROVAL_APPROVED_YES
            ),
        )
    );
    return GFAPI::get_entries(
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        ),
        array( 'offset' => 0, 'page_size' => 1000000 )
    );
}

function ccgn_new_individual_members_since_emails ( $start_date, $end_date ) {
    $emails = [];
    $members = ccgn_new_individual_members_since( $start_date, $end_date );
    foreach ( $members as $member ) {
        $member_id = $member [ CCGN_GF_FINAL_APPROVAL_APPLICANT_ID ];
        $user = get_user_by ( 'ID', $member_id );
        $emails[] = $user->user_email;
    }
    return $emails;
}
