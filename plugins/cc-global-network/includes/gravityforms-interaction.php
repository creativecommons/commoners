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

// Individual fields in forms

define( 'CCGN_GF_DETAILS_NAME', '1' );
define( 'CCGN_GF_DETAILS_BIO', '2' );
define( 'CCGN_GF_DETAILS_STATEMENT', '3' );
define( 'CCGN_GF_DETAILS_AREAS_OF_INTEREST', '5' );
define( 'CCGN_GF_DETAILS_LANGUAGES', '6' );
define( 'CCGN_GF_DETAILS_LOCATION', '7' );
define( 'CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS', '9' );
define( 'CCGN_GF_DETAILS_AVATAR_FILE', '11' );
define( 'CCGN_GF_DETAILS_AVATAR_SOURCE', '12' );
define( 'CCGN_GF_DETAILS_AVATAR_GRAVATAR', '13' );
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
define( 'CCGN_GF_VOTE_REASON', '3' );
define( 'CCGN_GF_VOTE_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_VOTE_APPLICANT_ID', '4' );

define( 'CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
define( 'CCGN_GF_FINAL_APPROVAL_REASON', '2' );
define( 'CCGN_GF_FINAL_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_FINAL_APPROVAL_APPLICANT_ID', '3' );

define( 'CCGN_GF_LEGAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
//define( 'CCGN_GF_LEGAL_APPROVAL_REASON', '2' );
define( 'CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID', '3' );

// Field values that we need to check

define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH_YES', 'Yes' );
define( 'CCGN_GF_PRE_APPROVAL_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_VOTE_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_FINAL_APPROVAL_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_LEGAL_APPROVAL_APPROVED_YES', 'Yes' );

define( 'CCGN_GF_DETAILS_AVATAR_SOURCE_GRAVATAR', 'gravatar' );
define( 'CCGN_GF_DETAILS_AVATAR_SOURCE_UPLOAD', 'image' );

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
    [ '1', '2', '3', '4', '5' ]
);

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
    $vouches = ccgn_application_vouches( $applicant_id );
    foreach ($vouches as $vouch) {
        $did_they = $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ];
        if ( $did_they == CCGN_GF_VOUCH_DO_YOU_VOUCH_YES ) {
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
    foreach ($votes as $vote) {
        $did_they = $vote[
            CCGN_GF_VOTE_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $did_they == CCGN_GF_VOTE_APPROVED_YES ) {
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
        'Languages',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_LANGUAGES ]
    );
    xprofile_set_field_data(
        'Links',
        $applicant_id,
        $details[ CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS ]
    );
    if ( ! ccgn_applicant_gravatar_selected ( $applicant_id ) ) {
        ccgn_set_avatar( $details, $applicant_id );
    }
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
    $details = ccgn_details_institution_form_entry ( $applicant_id );
    $institution_name = $details[ CCGN_GF_INSTITUTION_DETAILS_NAME ];
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

function ccgn_get_individual_ids () {
    // FIXME: Filter admin, council members, inactive members
    return bp_core_get_users(
        array(
            'member_type' => 'individual-member'
        )
    )["users"];
}

// This excludes the initial admin user (1) and the applicant from the list

function ccgn_registration_form_list_members ( $current_user_id ) {
    // For testing
    $include_admin = defined( 'CCGN_DEVELOPMENT' )
                   || defined( 'CCGN_TESTING' );
    $individuals = ccgn_get_individual_ids();
    $members = array();
    foreach ( $individuals as $individual ){
        if (
            ( $individual->ID != $current_user_id ) // Exclude applicant
            && ( $include_admin // Include admin if this is set
                 || ( $individual->ID !== 1 ) ) // Otherwise exclude them
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

// Why do it like this? To save download space rather than send thousands
// of options for each of several selects.
// Search terms:
// how do I dynamically populate a gravityforms select using javascript ?

function ccgn_set_vouchers_options ( $form ) {
    if( $form[ 'title' ] == CCGN_GF_CHOOSE_VOUCHERS ) {
        $current_member = get_current_user_id();
        $members = ccgn_registration_form_list_members( $current_member );
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

////////////////////////////////////////////////////////////////////////////////
// Voucher choice validation
// This is here as it's shared between a couple of shortcodes
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

// Clear "reason" text on appliction resolution

function ccgn_erase_application_reasons ( $applicant_id ) {
    ccgn_application_erase_field_applicant_id (
        CCGN_GF_VOUCH,
        CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
        CCGN_GF_VOUCH_REASON,
        $applicant_id
    );
    ccgn_application_erase_field_applicant_id (
        CCGN_GF_VOTE,
        CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
        CCGN_GF_VOUCH_REASON,
        $applicant_id
    );
    ccgn_application_erase_field_applicant_id (
        CCGN_GF_PRE_APPROVAL,
        CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
        CCGN_GF_VOUCH_REASON,
        $applicant_id
    );
    ccgn_application_erase_field_applicant_id (
        CCGN_GF_FINAL_APPROVAL,
        CCGN_GF_FINAL_APPROVAL_APPLICANT_ID,
        CCGN_GF_FINAL_APPROVAL_REASON,
        $applicant_id
    );
    /*    ccgn_application_erase_field_applicant_id (
        CCGN_GF_LEGAL_APPROVAL,
        CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID,
        CCGN_GF_LEGAL_APPROVAL_REASON,
        $applicant_id
        );*/
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
