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
define( 'CCGN_GF_FINAL_APPROVAL', 'Final Approval' );

// Individual fields in forms

define( 'CCGN_GF_DETAILS_NAME', '1' );
define( 'CCGN_GF_DETAILS_BIO', '2' );
define( 'CCGN_GF_DETAILS_STATEMENT', '3' );
define( 'CCGN_GF_DETAILS_AREAS_OF_INTEREST', '5' );
define( 'CCGN_GF_DETAILS_LANGUAGES', '6' );
define( 'CCGN_GF_DETAILS_LOCATION', '7' );
define( 'CCGN_GF_DETAILS_NATIONALITY', '8' );
define( 'CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS', '9' );
define( 'CCGN_GF_DETAILS_AVATAR_FILE', '10' );

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

define( 'CCGN_GF_VOUCH_APPLICANT_ID', 'applicant_id' );
define( 'CCGN_GF_VOUCH_APPLICANT_ID_FIELD', '7' );
define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH', '3' );
define( 'CCGN_GF_VOUCH_REASON', '4' );

define( 'CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '1' );
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_PRE_APPROVAL_APPLICANT_ID', '4' );

define( 'CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION', '2' );
define( 'CCGN_GF_FINAL_APPROVAL_APPLICANT_ID_PARAMETER', 'applicant_id' );
define( 'CCGN_GF_FINAL_APPROVAL_APPLICANT_ID', '4' );

// Field values that we need to check

define( 'CCGN_GF_VOUCH_DO_YOU_VOUCH_YES', 'Yes' );
define( 'CCGN_GF_PRE_APPROVAL_APPROVED_YES', 'Yes' );
define( 'CCGN_GF_FINAL_APPROVAL_APPROVED_YES', 'Yes' );

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
        [ 'Brief Biography', CCGN_GF_DETAILS_BIO ],
        [ 'Membership Statement', CCGN_GF_DETAILS_STATEMENT ],
        [ 'Areas of Interest', CCGN_GF_DETAILS_AREAS_OF_INTEREST ],
        [ 'Languages', CCGN_GF_DETAILS_LANGUAGES ],
        [ 'Location', CCGN_GF_DETAILS_LOCATION ],
        [ 'Nationality', CCGN_GF_DETAILS_NATIONALITY ],
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
        [
            'Representative Email',
            CCGN_GF_INSTITUTION_DETAILS_REPRESENTATIVE_EMAIL
        ],
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
            'Existing Global Networm Members',
            CCGN_GF_INSTITUTION_DETAILS_EXISTING_MEMBERS
        ],
        [ 'Membership Statement', CCGN_GF_INSTITUTION_DETAILS_STATEMENT ],
        [ 'Is An Affiliate', CCGN_GF_INSTITUTION_DETAILS_IS_AFFILIATE ],
        [
            'Affiliate Assets',
            CCGN_GF_INSTITUTION_DETAILS_AFFILIATE_ASSETS
        ],
        [
            'Affiliate Domain Name',
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
// Vouching form
////////////////////////////////////////////////////////////////////////////////

// Find the applicant's choices of vouchers in Gravity Forms

function ccgn_vouching_request_entry ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_CHOOSE_VOUCHERS );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>  'created_by',
            'value' => $applicant_id
        );
    $entries = GFAPI::get_entries(
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

    return $entries[ 0 ];
}

// Has the voucher already responded to applicant's vouching request?

function ccgn_vouching_request_open ( $applicant_id, $voucher_id ) {
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
    $entries = GFAPI::get_entries(
        $form_id,
        $search_criteria
    );
    return $entries == [];
}

////////////////////////////////////////////////////////////////////////////////
// Individual / Institution details forms
////////////////////////////////////////////////////////////////////////////////

function ccgn_details_individual_form_entry ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_INDIVIDUAL_DETAILS );
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

function ccgn_details_institution_form_entry ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_INSTITUTION_DETAILS );
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

////////////////////////////////////////////////////////////////////////////////
// Getting an applicant's various details
////////////////////////////////////////////////////////////////////////////////

// Get the applicant details for the id

function ccgn_application_details ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_APPLICANT_DETAILS );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>  'created_by',
            'value' => $applicant_id
        );
    $entries = GFAPI::get_entries(
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

    return $entries[0];
}

// Get the list of vouchers for the id.
// This is the applicant's voucher choices from the form.

function ccgn_application_vouchers ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_CHOOSE_VOUCHERS );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>  'created_by',
            'value' => $applicant_id
        );
    $entries = GFAPI::get_entries(
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

// Get the list of submitted vouches for the user

function ccgn_application_vouches ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( CCGN_GF_VOUCH );
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>  CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
            'value' => $applicant_id
        );
    $entries = GFAPI::get_entries(
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
    return $entries;
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

////////////////////////////////////////////////////////////////////////////////
// User profile creation based on GravityForms information
////////////////////////////////////////////////////////////////////////////////

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
    //FIXME: avatar - CCGN_GF_DETALS_AVATAR_FILE
    //FIXME: user name?
}

function ccgn_create_profile_institutional( $applicant_id ) {
    //IMPLEMENTME
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
    $query = new BP_User_Query(
        array(
            'member_type' => 'individual-member'
        )
    );
    $users = $query->results;
    return $users;
}

// This excludes the initial admin user (1) and the applicant from the list

function ccgn_registration_form_list_members ( $current_user_id ) {
    $individuals = ccgn_get_individual_ids();
    $members = array();
    foreach ( $individuals as $individual ){
        if ( ( $individual->ID != 1 ) // Exclude admin
             && ( $individual->ID != $current_user_id ) ) { // Exclude applicant
            $members[] = array(
                $individual->ID,
                $individual->display_name
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
                    console.log(jQuery(element));
                    return jQuery("<option></option>")
                        .attr("value", member[0])
                        .text(member[1]);
                });
                var select = jQuery(element);
                //FIXME: We should remove everything except the first element
                select.empty();
                select.append(jQuery("<option disabled selected value>Select Voucher</option>"));
                for (var i = 0; i < ccgn_members.length; i++) {
                    select.append(jQuery("<option></option>")
                                  .attr("value", ccgn_members[i][0])
                                  .text(ccgn_members[i][1]));
                }
                return options;
            });
        });
        </script>
        <?php
    }
    return $form;
}

################################################################################
# Voucher choice validation
# This is here as it's shared between a couple of shortcodes
################################################################################

function ccgn_choose_vouchers_validate ( $validation_result ) {
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
    return $validation_result;
}


////////////////////////////////////////////////////////////////////////////////
// Entry deletion
////////////////////////////////////////////////////////////////////////////////

// This really does delete the user's form entries.

function _ccgn_application_delete_entries ( $applicant_id ) {
    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
            'key' =>  'created_by',
            'value' => $applicant_id
        );
    $entries = GFAPI::get_entries(
        0,
        $search_criteria
    );
    foreach( $entries as $entry ) {
        GFAPI::delete_entry( $entry->id );
    }
}
