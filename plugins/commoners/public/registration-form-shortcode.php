<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Registration form shortcode
// Displays a form or other information depending on the user's application
// stage/state.
////////////////////////////////////////////////////////////////////////////////

// Called by the last form.
// This is its own function so we can move it if we change which form is last.

function commoners_registration_post_last_form () {
    $applicant_id = get_current_user_id();
    // This shouldn't happen, but just in case
    if ( $applicant_id == 0 ) {
        echo 'User submitting registration form not logged in';
        return;
    }
    commoners_registration_email_application_received (
        $applicant_id
    );
}

// Perform post-submit actions for each form

function commoners_registration_form_submit_handler ( $entry, $form ) {
    $form = $validation_result['form'];
    switch( $form[ 'name' ] ) {
    case COMMONERS_GF_AGREE_TO_TERMS:
        commoners_registration_current_user_set_stage (
            COMMONERS_APPLICATION_STATE_DETAILS
        );
        break;
    case COMMONERS_GF_APPLICANT_DETAILS:
        commoners_registration_current_user_set_stage (
            COMMONERS_APPLICATION_STATE_VOUCHERS
        );
        break;
    case COMMONERS_GF_CHOOSE_VOUCHERS:
        commoners_registration_current_user_set_stage (
            COMMONERS_APPLICATION_STATE_RECEIVED
        );
        // Move if this is no longer the last form the applicant completes in
        // the initial data entry stage!
        commoners_registration_post_last_form ();
        break;
    }
}

function commoners_registration_shortcode_render ( $atts ) {
    if ( ! is_user_logged_in() ) {
        wp_redirect( 'https://login.creativecommons.org/login?service='
                     . get_site_url()
                     . '/sign-up/member/' );
        exit;
    }

    $user = wp_get_current_user();
    $state = $user->get( COMMONERS_APPLICATION_STATE );

    switch ( $state ) {
    case '':
        gravity_form( COMMONERS_GF_AGREE_TO_TERMS, false, false );
        break;
    case 'details':
        gravity_form( COMMONERS_GF_APPLICANT_DETAILS, false, false );
        break;
    case 'vouchers':
        gravity_form( COMMONERS_GF_CHOOSE_VOUCHERS, false, false );
        break;
    case 'received':
        echo _( '<h2>Thank you for applying to join the Creative Commons Global Network</h2></p><p>Your application has been received.</p><p>It will take several days to be reviewed.</p><p>If you have any questions you can <a href="/contact/">contact us.</a></p>' );
        break;
    case 'rejected':
        echo _( '<p>Your application has been declined.</p><p>You may be able to re-apply after the public launch of the Gobal Network in April 2018.</p><p>If you have any questions you can <a href="/contact/">contact us</a>, but please note we cannot comment on the details of individual applications.</p>' );
        break;
    case 'accepted':
        echo _( '<p>Your application has been accepted.</p>' );
        break;
    default:
        echo _( '<p>Unrecognised application state.</p>' );
    }
}
