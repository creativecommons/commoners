<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Registration form shortcode
// Displays a form or other information depending on the user's application
// stage/state.
////////////////////////////////////////////////////////////////////////////////

// Called by the last form.
// This is its own function so we can move it if we change which form is last.

function ccgn_registration_individual_post_last_form () {
    $applicant_id = get_current_user_id();
    // This shouldn't happen, but just in case
    if ( $applicant_id == 0 ) {
        echo 'User submitting registration form not logged in';
        return;
    }
    ccgn_registration_email_application_received (
        $applicant_id
    );
}

// Perform post-submit actions for each form

function ccgn_registration_individual_form_submit_handler ( $entry,
                                                            $form ) {
    if ( ! ccgn_user_is_individual_applicant( $entry[ 'created_by' ] ) ) {
        return;
    }
    switch( $form[ 'title' ] ) {
    case CCGN_GF_AGREE_TO_TERMS:
        ccgn_registration_current_user_set_stage (
            CCGN_APPLICATION_STATE_CHARTER
        );
        break;
    case CCGN_GF_SIGN_CHARTER:
        ccgn_registration_current_user_set_stage (
            CCGN_APPLICATION_STATE_VOUCHERS
        );
        break;
    case CCGN_GF_CHOOSE_VOUCHERS:
        ccgn_registration_current_user_set_stage (
            CCGN_APPLICATION_STATE_DETAILS
        );
        break;
        // Note that this is called both for initial creation and subsequent
        // updates. This works well because in both cases we want to move the
        // user to the RECEIVED state.
    case CCGN_GF_INDIVIDUAL_DETAILS:
        $user_state = ccgn_registration_user_get_stage( $entry['created_by'] );
        if ( $user_state == CCGN_APPLICATION_STATE_UPDATE_DETAILS ) {
            $status = get_user_meta( $entry['created_by'], 'ccgn_applicant_update_details_state', true );
            $state['state'] = 'updated';
            $state['updated'] = 1;
            $state['date'] = date('Y-m-d H:i:s', strtotime('now'));
            update_user_meta( $entry['created_by'], 'ccgn_applicant_update_details_state', $status );
        }
        ccgn_registration_user_set_stage ( $entry['created_by'], CCGN_APPLICATION_STATE_RECEIVED );

        // Move if this is no longer the last form the applicant completes in
        // the initial data entry stage!
        ccgn_registration_individual_post_last_form ();
        break;
    }
}

function ccgn_registration_individual_shortcode_render_view ( $user ) {
    $state = $user->get( CCGN_APPLICATION_STATE );
    switch ( $state ) {
    case '':
        gravity_form( CCGN_GF_AGREE_TO_TERMS, false, false );
        break;
    case CCGN_APPLICATION_STATE_CHARTER:
        gravity_form( CCGN_GF_SIGN_CHARTER, false, false );
        break;
    case CCGN_APPLICATION_STATE_DETAILS:
    case CCGN_APPLICATION_STATE_UPDATE_DETAILS:

        ccgn_registration_individual_shortcode_render_details( $user );
        break;
    case CCGN_APPLICATION_STATE_VOUCHERS:
        gravity_form( CCGN_GF_CHOOSE_VOUCHERS, false, false );
        break;
    case CCGN_APPLICATION_STATE_UPDATE_VOUCHERS:
        gravity_form( CCGN_GF_CHOOSE_VOUCHERS, false, false );
        break;
    case CCGN_APPLICATION_STATE_RECEIVED:
    case CCGN_APPLICATION_STATE_VOUCHING:
        echo _( '<h2>Thank you for applying to join the Creative Commons Global Network</h2></p><p>Your application has been received.</p><p>It will take several days to be reviewed.</p><p>If you have any questions you can <a href="/contact/">contact us.</a></p>' );
        break;
    case CCGN_APPLICATION_STATE_REJECTED:
        echo _( '<p>Your application has been declined.</p><p>If you have any questions you can <a href="/contact/">contact us</a>, but please note we cannot comment on the details of individual applications.</p>' );
        break;
    case CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS:
        echo _( '<p>Your application closed automatically because you did not update your Voucher choices.</p><p>If you have any questions you can <a href="/contact/">contact us</a>, but please note we cannot comment on the details of individual applications.</p>' );
        break;
    case CCGN_APPLICATION_STATE_ACCEPTED:
        echo _( '<p>Your application has been accepted.</p>' );
        break;
    default:
        error_log( 'Unrecognised application state: ' . $state );
        echo _( '<p>Unrecognised application state.</p>' );
    }
}

function ccgn_registration_individual_shortcode_render_gravatar ( $user ) {
    ?>
    <script>
    <?php
    if ( ccgn_user_gravatar_exists ( $user->ID ) ) {
    ?>
      jQuery('.ccgn_applicant_details_gravatar')
          .html('<?php echo ccgn_user_gravatar_img( $user->ID, 80 ); ?><div class="gfield_description">Your current Gravatar.</div>');
    <?php
    } else {
    ?>
      jQuery('.ccgn_avatar_source input[value="gravatar"]').attr("disabled",
                                                                 true);
      jQuery('.ccgn_avatar_source input[value="gravatar"]').hide();
      jQuery('.ccgn_avatar_source input[value="gravatar"] + label').hide();
      jQuery('.ccgn_avatar_source input[value="image"]').prop("checked",
                                                              true);
    <?php

    }
    ?>
    </script>
    <?php
}

function ccgn_registration_individual_shortcode_render_details ( $user ) {
    // Slightly naughty - [][0] works fine here when there is no previous entry
    $existing_entry = ccgn_entries_created_by_user (
        $user->ID,
        CCGN_GF_INDIVIDUAL_DETAILS
    )[0];
    gravity_form(
        CCGN_GF_INDIVIDUAL_DETAILS,
        false,
        false,
        false,
        array(
            CCGN_GF_DETAILS_NAME_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_NAME ],
            CCGN_GF_DETAILS_BIO_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_BIO ],
            CCGN_GF_DETAILS_STATEMENT_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_STATEMENT ],
            CCGN_GF_DETAILS_AREAS_OF_INTEREST_PARAMETER
            => json_decode($existing_entry[
                CCGN_GF_DETAILS_AREAS_OF_INTEREST
            ]),
            CCGN_GF_DETAILS_LANGUAGES_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_LANGUAGES ],
            CCGN_GF_DETAILS_LOCATION_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_LOCATION ],
            CCGN_GF_DETAILS_CHAPTER_INTEREST_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_CHAPTER_INTEREST ],
            CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_SOCIAL_MEDIA_URLS ],
            CCGN_GF_DETAILS_WAS_AFFILIATE_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_WAS_AFFILIATE ],
            CCGN_GF_DETAILS_WAS_AFFILIATE_NAME_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_WAS_AFFILIATE_NAME ],
            CCGN_GF_DETAILS_RECEIVE_EMAILS_PARAMETER
            => $existing_entry[ CCGN_GF_DETAILS_RECEIVE_EMAILS ]
        )
    );
    //ccgn_registration_individual_shortcode_render_gravatar( $user );
}

function ccgn_registration_individual_shortcode_render ( $atts ) {
    if( ! is_user_logged_in() ) {
        echo '<h3>OK! Let&apos;s get started</h3>';
        echo '<p>First you need to log in with your CCID.</p>';
        echo '<a class="cc-btn" href="'
            . wp_login_url( get_permalink() )
            . '">Log in</a>';
        return;
    }
    $user = wp_get_current_user();
    if ( ccgn_user_is_institutional_applicant ( $user->ID ) ) {
        echo _( '<p>You are already applying for membership on behalf of an Instituion.</p>' );
        echo _( '<p>If this is an error, <a href="/contact/">contact us.</a></p>' );
        return;
    }
    //FIXME: Model update code in the view
    if ( ! ccgn_user_is_individual_applicant ( $user->ID ) ) {
        ccgn_user_set_individual_applicant ( $user->ID );
    }
    ccgn_registration_individual_shortcode_render_view( $user );
}
