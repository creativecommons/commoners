<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Registration form shortcode
// Displays a form or other information depending on the user's application
// stage/state.
////////////////////////////////////////////////////////////////////////////////

// Called by the last form.
// This is its own function so we can move it if we change which form is last.

function commoners_registration_institution_post_last_form () {
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

function commoners_registration_institution_form_submit_handler ( $entry,
                                                                 $form ) {
    if ( ! commoners_user_is_institutional_applicant(
        $entry[ 'created_by' ]
    ) ) {
        return;
    }
    /*    switch( $form[ 'title' ] ) {
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
        }*/
}

function commoners_registration_institution_shortcode_render ( $atts ) {
    if ( ! is_user_logged_in() ) {
        wp_redirect( 'https://login.creativecommons.org/login?service='
                     . get_site_url()
                     . '/sign-up/member/' );
        exit;
    }
    $user = wp_get_current_user();
    if ( commoners_user_is_individual_applicant ( $user->ID ) ) {
        echo _( '<p>You are already applying for membership as an Individual.</p>' );
        echo _( '<p>If this is an error, <a href="/contact/">contact us.</a></p>' );
        return;
    }
    //FIXME: Model update code in the view
    if ( ! commoners_user_is_institutional_applicant ( $user->ID ) ) {
        commoners_user_set_institutional_applicant ( $user->ID );
    }
    $state = $user->get( COMMONERS_APPLICATION_STATE );
    switch ( $state ) {
    case '':
        gravity_form( COMMONERS_GF_AGREE_TO_TERMS, false, false );
        break;
    case COMMONERS_APPLICATION_STATE_CHARTER:
        gravity_form( COMMONERS_GF_SIGN_CHARTER, false, false );
        break;
    case COMMONERS_APPLICATION_STATE_DETAILS:
        gravity_form( COMMONERS_GF_INSTITUTION_DETAILS, false, false );
        break;
    case COMMONERS_APPLICATION_STATE_VOUCHERS:
        gravity_form( COMMONERS_GF_CHOOSE_VOUCHERS, false, false );
        break;
    case COMMONERS_APPLICATION_STATE_RECEIVED:
        echo _( '<h2>Thank you for applying to join the Creative Commons Global Network</h2></p><p>Your application has been received.</p><p>It will take several days to be reviewed.</p><p>If you have any questions you can <a href="/contact/">contact us.</a></p>' );
        break;
    case COMMONERS_APPLICATION_STATE_REJECTED:
        echo _( '<p>Your application has been declined.</p><p>You may be able to re-apply after the public launch of the Gobal Network in April 2018.</p><p>If you have any questions you can <a href="/contact/">contact us</a>, but please note we cannot comment on the details of individual applications.</p>' );
        break;
    case COMMONERS_APPLICATION_STATE_ACCEPTED:
        echo _( '<p>Your application has been accepted.</p>' );
        break;
    default:
        echo _( '<p>Unrecognised application state.</p>' );
    }
}
