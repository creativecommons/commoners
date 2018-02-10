<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Map choice fields to fields to store old values in
// ASSUMES SAME NUMBER/POSITION OF CHOICE FIELDS IN INITIAL AND UPDATE FORMS
define(
    'CCGN_ADMIN_CHANGE_VOUCHES_MAP',
    [
        1 => 3,
        2 => 4
    ]
);

// Make sure the Admin doesn't try to change the Voucher to a duplicate or to
// a Member that has already completed a Vouching request for the user.
//FIXME: Too long! Decompose!

function ccgn_application_change_vouchers_validate ( $validation_result ) {
    // We list users who have already vouched or been selected as Vouchers
    // and we rely on the user to do the right thing.
    // If they have not, we catch it here.
    //FIXME: Better UI that makes this unnecessary.
    $form = $validation_result['form'];
    if( $form['title'] == CCGN_GF_ADMIN_CHANGE_VOUCHERS ) {
        $applicant_id = rgpost (
            "input_" . CCGN_GF_ADMIN_CHANGE_VOUCHERS_APPLICANT_ID
        );
        //FIXME: Check for ! $applicant_id
        if ( $applicant_id ) {
            $editentry = ccgn_application_vouchers( $applicant_id );
            $existing_vouchers = ccgn_application_vouchers_users_ids (
                $applicant_id
            );
            $vouchers = [];
            foreach( $form['fields'] as &$field ) {
                //FIXME: copypasta from ccgn_choose_vouchers_validate()
                // Check for duplicate vouchers, mark as invalid if found
                if ( in_array( $field->id, CCGN_GF_VOUCH_VOUCHER_FIELDS ) ) {
                    $voucher = rgpost( "input_{$field['id']}" );
                    if ( $voucher && in_array( $voucher, $vouchers ) ) {
                        $validation_result['is_valid'] = false;
                        $field->failed_validation = true;
                        $field->validation_message = 'The same member cannot vouch an applicant more than once!';
                    } else {
                        // If Voucher has been changed to user who has vouched
                        if ( ($editentry [ $field['id']  ] != $voucher )
                             && in_array( $voucher, $existing_vouchers ) ) {
                            $validation_result['is_valid'] = false;
                            $field->failed_validation = true;
                            $field->validation_message = 'That Member has already completed a Vouching request for this Applicant!';
                        }
                    }
                    $vouchers[] = $voucher;
                }
            }
            $validation_result['form'] = $form;
        }
    }
    return $validation_result;
}

// We don't need to worry about invalid choices at this point, we caught those
// during validation.
//FIXME: too long, copypasta from ccgn_choose_vouchers_pre_submission

function ccgn_application_change_vouchers_after_submission ( $entry, $form ) {
    if ( $form[ 'title' ] == CCGN_GF_ADMIN_CHANGE_VOUCHERS ) {
        $applicant_id = $entry[ CCGN_GF_ADMIN_CHANGE_VOUCHERS_APPLICANT_ID ];

        $editentry = ccgn_application_vouchers( $applicant_id );
        $should_update = false;
        $new_vouchers = [];
        // Check if each field has updated, note which have
        foreach (CCGN_GF_VOUCH_VOUCHER_FIELDS as $vf) {
            // Always store the original value
            $entry[ CCGN_ADMIN_CHANGE_VOUCHES_MAP[ $vf ]  ] = $editentry[ $vf ];
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
            $updateit = GFAPI::update_entry( $entry );
            if ( is_wp_error( $updateit ) ) {
                echo "Something went badly wrong updating the voucher change entry.";
            }
            // Update the Voucher request entry
            $updateit = GFAPI::update_entry( $editentry );
            if ( is_wp_error( $updateit ) ) {
                echo "Something went badly wrong updating the vouchers.";
            } else {
                // Email any new Vouchers a Vouching Request
                foreach ($new_vouchers as $voucher_id) {
                    ccgn_registration_email_vouching_request(
                        $applicant_id,
                        $voucher_id
                    );
                }
            }
        }
    }
}

function ccgn_application_change_vouchers_page () {
    $applicant_id = ccgn_request_applicant_id ();
    if ( $applicant_id === false ) {
        return;
    }
    $state = ccgn_registration_user_get_stage( $applicant_id );
    if ( $state != CCGN_APPLICATION_STATE_VOUCHING ) {
        echo _('<br /><h2>Application is not in the Vouching state.</h2>');
    }
    $applicant = get_user_by('ID', $applicant_id);
    echo '<h2>' . $applicant->display_name . '</h2>';
    echo _('</p>Note carefully which Members have already completed a Vouching request, do not choose those as replacement Vouchers.</p>');
    echo _('</p>If you choose a new Voucher here, the Applicant\'s Vouching Request form will be updated and the new Vouchers will be emailed a notification to Vouch for the Applicant..</p>');
    echo _('<h2>Vouches Received</h2>');
    echo ccgn_application_users_page_vouch_counts ( $applicant_id );
    echo _('<h2>Vouchers Requested</h2>');
    echo ccgn_application_users_page_vouchers ( $applicant_id );
    echo _('<h2>Vouches</h2>');
    echo ccgn_application_users_page_vouch_responses ( $applicant_id );
    echo _('<h2>Update Vouches</h2>');
    gravity_form(
        CCGN_GF_ADMIN_CHANGE_VOUCHERS,
        false,
        false,
        false,
        array(
            CCGN_GF_ADMIN_CHANGE_VOUCHERS_APPLICANT_ID_PARAMETER
            => $applicant_id
        )
    );
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

function ccgn_application_change_vouchers_menu () {
    add_submenu_page(
        null,
        'Change Vouchers',
        'Change Vouchers',
        //FIXME: Make this its own capability or tie to final approval
        'ccgn_pre_approve',
        'global-network-application-change-vouchers',
        'ccgn_application_change_vouchers_page'
    );
}

function ccgn_application_change_vouchers_page_url( $user_id ) {
    return admin_url(
        'admin.php?page=global-network-application-change-vouchers&user_id='
        . $user_id
    );
}