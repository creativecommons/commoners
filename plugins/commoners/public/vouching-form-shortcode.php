<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// FIXME:
// We should use the voucher's user id, not their username
// We can do this once we cache the user id on the "Choose Vouchers" form
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Vouching form shortcode
// This uses constants from includes/gravityforms-interaction.php
////////////////////////////////////////////////////////////////////////////////

// Has applicant requested that voucher vouch for them?
// FIXME: The form contains the voucher usernames, not ids so look up by
// username until we cache the ids on save.

function commoners_vouching_request_exists ( $applicant_id,
                                             $voucher_id ) {
    $result = false;
    $vouchers = commoners_vouching_request_entry ( $applicant_id );
    foreach( COMMONERS_GF_VOUCH_VOUCHER_FIELDS as $field_id ) {
        if ( $vouchers[ $field_id ] == $voucher_id ) {
            $result = true;
        }
    }
    return $result;
}

// Render the form for User to vouch for Applicant.
// Firstly we check that the user is logged in and that we should display the
// form to this user.
// Then we render the Vouch For Applicant form, with the correct values filled
// out there (we will validate the userid server-side on submission).

function commoners_vouching_shortcode_render ( $atts ) {
    // Only logged-in users can vouch.
    if ( ! is_user_logged_in() ) {
        wp_redirect( 'https://login.creativecommons.org/login?service='
                     . get_site_url()
                     . '/vouch/' );
        exit;
    }

    // We need an applicant to vouch for
    if ( ! isset( $_GET[ 'applicant_id' ] ) ) {
        echo _( '<p>No applicant specified to vouch for.</p>' );
        exit;
    }

    // Get applicant and voucher identifiers
    //FIXME: Get voucher id and use that once the form contains it
    $applicant_id = $_GET[ 'applicant_id' ];
    $voucher = wp_get_current_user();
    $voucher_id = get_current_user_id();

    // Render correct UI for state of vouching
    if ( ! commoners_vouching_request_exists( $applicant_id,
                                              $voucher_id ) ) {
        echo _( "<p>Request couldn't be found.<p>" );
    } elseif ( ! commoners_vouching_request_active ( $applicant_id ) ) {
        echo _( "<p>That person's application to become a Member of the Creative Commons Global Network has already been resolved.<p></p>Thank you!</p>" );
    } elseif( ! commoners_vouching_request_open( $applicant_id,
                                                 $voucher_id ) ) {
        // This is a bit of a hack. It will be displayed when the page
        // refreshes after intially submitting the form AND if the user
        // re-visits it subsequently.
        // So we make sure it will read well in both cases.
        echo _( "<p>Thank you for responding to this request!<p>" );
    } else {
        // We were going to pass this as the content of an HTML field in the
        // gravity form but this is easier
        echo commoners_vouching_form_profile_text( $applicant_id );
        gravity_form(
            COMMONERS_GF_VOUCH,
            false,
            false,
            false,
            array(
                COMMONERS_GF_VOUCH_APPLICANT_ID => applicant_id
            )
        );
    }
}

// Make sure no-one tries to vouch for someone they haven't been asked to,
// or to double-vouch them.

function commoners_vouching_form_post_validate ( $validation_result ) {
    $form = $validation_result['form'];
    if ( $form[ 'name' ] == COMMONERS_GF_VOUCH ) {
        $applicant_id = rgpost( COMMONERS_GF_VOUCH_APPLICANT_ID );
        $voucher_id = form[ 'created_by' ];
        // Don't check commoners_vouching_request_active, as the user may be
        // responding after that is no longer true and we don't want to annoy
        // them.
        $ok = commoners_vouching_request_exists (
            $applicant_id,
            $voucher_id
        ) && commoners_vouching_request_open (
            $applicant_id,
            $voucher_id
        );

        if ( ! $ok ) {
            // set the form validation to false
            $validation_result['is_valid'] = false;
        }

        //Assign modified $form object back to the validation result
        $validation_result['form'] = $form;
    }
    return $validation_result;
}
