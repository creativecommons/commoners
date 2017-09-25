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
    switch( $form[ 'title' ] ) {
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

function commoners_registration_list_members () {
    global $wpdb;

    // Format match string as SQL LIKE string
    $to_match = "%$to_match%";

    // Query the database for username matches. Note exclusion of admin.

    $table_name = $wpdb->prefix . 'users';
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "
                 SELECT           ID, display_name
                 FROM             $table_name
                 WHERE            ID > 1
                 ORDER BY         display_name
                 DESC
                ",
            $to_match
        )
    );

    //FIXME: filter inactive users !!!

    $members = array();
    foreach ( $rows as $row ){
        $members[] = array(
            $row->ID,
            $row->display_name
        );
    }
    return $members;
}

// Why do it like this? To save download space rather than send thousands
// of options for each of several selects.

function commoners_registration_populate_vouchers () {
    $members = commoners_registration_list_members();
    ?>
    <script>
    var commoners_members = <?php echo json_encode( $members ); ?>;
    jQuery(document).ready(function () {
      jQuery('select').each(function () {
      var select = jQuery(this);
      select.empty();
      select.append(jQuery('<option disabled selected value>Select Voucher</option>'));
      for (var i = 0; i < commoners_members.length; i++) {
        select.append(jQuery("<option></option>")
              .attr("value", commoners_members[i][0])
              .text(commoners_members[i][1]));
        }
      });
    });
    </script>
    <?php
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
    case COMMONERS_APPLICATION_STATE_DETAILS:
        gravity_form( COMMONERS_GF_APPLICANT_DETAILS, false, false );
        break;
    case COMMONERS_APPLICATION_STATE_VOUCHERS:
        gravity_form( COMMONERS_GF_CHOOSE_VOUCHERS, false, false );
        commoners_registration_populate_vouchers();
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
