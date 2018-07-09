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

function ccgn_vouching_request_exists ( $applicant_id,
                                        $voucher_id ) {
    $result = false;
    $vouchers = ccgn_application_vouchers ( $applicant_id );
    foreach( CCGN_GF_VOUCH_VOUCHER_FIELDS as $field_id ) {
        if ( $vouchers[ $field_id ] == $voucher_id ) {
            $result = true;
        }
    }
    return $result;
}

// Find vouching requests for the current user to respond to

function ccgn_vouching_requests_for_me ( $voucher_id ) {
    $results = array();
    foreach( CCGN_GF_VOUCH_VOUCHER_FIELDS as $field_id ) {
        $requests = ccgn_entries_referring_to_user (
            $voucher_id,
            CCGN_GF_CHOOSE_VOUCHERS,
            $field_id
        );
        foreach( $requests as $request ) {
            $applicant_id = $request[ 'created_by' ];
            if ( ccgn_vouching_request_active ( $applicant_id )
                 && ccgn_vouching_request_open(
                     $applicant_id,
                     $voucher_id
                 ) ) {
                $results[] = $request;
            }
        }
    }
    return $results;
}

function ccgn_vouching_requests_render ( $voucher_id ) {
    $requests = ccgn_vouching_requests_for_me ( $voucher_id );
    if ( $requests !== [] ) {
        echo _( "<h2>Current Vouching Requests</h2><ul>" );
        foreach ( $requests as $request ) {
            $applicant_id = $request[ 'created_by' ];
            // Make sure the user has been Spam Checked, otherwise people
            // cannot vouch for them despite them being requested to do so.
            $stage = ccgn_registration_user_get_stage( $applicant_id);
            if ( $stage != CCGN_APPLICATION_STATE_VOUCHING ) {
                continue;
            }
            $applicant = get_user_by( 'ID', $applicant_id );
            $request_html = '<li><a href="'
                          . get_site_url()
                          . '/vouch/?applicant_id='
                          . $applicant_id
                          . '">'
                          . ccgn_applicant_display_name (
                              $applicant_id
                          );
            if ( ccgn_user_is_individual_applicant( $applicant_id ) ) {
                $request_html .= ' ('
                             . $applicant->user_nicename
                             . ')';
            }
            $request_html .= '</a></li>';
            echo $request_html;
        }
        echo "</ul>";
    } else {
        echo _( "<h3>No Requests</h3><p>There are currently no Vouching requests waiting for you.</p>" );
    }
}

// Render the form for User to vouch for Applicant.
// Firstly we check that the user is logged in and that we should display the
// form to this user.
// Then we render the Vouch For Applicant form, with the correct values filled
// out there (we will validate the userid server-side on submission).

function ccgn_vouching_shortcode_render ( $atts ) {
    if( ! is_user_logged_in() ) {
        echo '<h3>You must be logged in to Vouch</h3>';
        echo '<p>You can log in with your CCID here:</p>';
        echo '<a class="cc-btn" href="'
            . wp_login_url( get_permalink() )
            . '">Log in</a>';
        return;
    }

    // Get applicant and voucher identifiers
    //FIXME: Get voucher id and use that once the form contains it
    // We need an applicant to vouch for
    $applicant_id = filter_input(
        INPUT_GET,
        'applicant_id',
        FILTER_VALIDATE_INT
    );
    if ( $applicant_id === false ) {
        echo _( '<br />Invalid user id.' );
        return;
    }

    $voucher_id = get_current_user_id();

    // REMOVE IF/WHEN DEFAULT PAGE TEMPLATE SHOWS TITLE.
    echo "<br/><h1>Vouching</h1>";

    // If no applicant_id, show the list of vouch requests.
    if ( $applicant_id === null ) {
        ccgn_vouching_requests_render ( $voucher_id );
    } elseif ( ! ccgn_user_is_vouched( $voucher_id ) ) {
        echo _( "<p>You must be vouched before you can vouch for others.</p>" );
    } elseif ( ! ccgn_vouching_request_exists( $applicant_id,
                                              $voucher_id ) ) {
        echo _( "<p>Request couldn't be found.</p>" );
    } elseif ( ! ccgn_vouching_request_active ( $applicant_id ) ) {
        echo _( "<h2>Thank you!</h2><p>That person's application to become a Member of the Creative Commons Global Network has already been resolved.<p>" );
    } elseif( ! ccgn_vouching_request_open( $applicant_id,
                                                 $voucher_id ) ) {
        // This is a bit of a hack. It will be displayed when the page
        // refreshes after intially submitting the form AND if the user
        // re-visits it subsequently.
        // So we make sure it will read well in both cases.
        echo _( "<h2>Thank you!</h2><p>Thank you for responding to this request!<p>" );
    } else {
        if ( ccgn_user_is_institutional_applicant ( $applicant_id ) ) {
            echo _( "<i>Note that this is an institution applying to join the Global Network. We still need you to vouch for this institution as you would for an individual that you know.</i>" );
        }
        // We were going to pass this as the content of an HTML field in the
        // gravity form but this is easier
        echo ccgn_vouching_form_applicant_profile_text( $applicant_id );
        gravity_form(
            CCGN_GF_VOUCH,
            false,
            false,
            false,
            array(
                CCGN_GF_VOUCH_APPLICANT_ID => applicant_id
            )
        );
    }
}

// Make sure no-one tries to vouch for someone they haven't been asked to,
// or to double-vouch them.

function ccgn_vouching_form_post_validate ( $validation_result ) {
    $form = $validation_result['form'];
    if ( $form[ 'name' ] == CCGN_GF_VOUCH ) {
        $applicant_id = rgpost( CCGN_GF_VOUCH_APPLICANT_ID );
        $voucher_id = form[ 'created_by' ];
        // Don't check ccgn_vouching_request_active, as the user may be
        // responding after that is no longer true and we don't want to annoy
        // them.
        $ok = ccgn_vouching_request_exists (
            $applicant_id,
            $voucher_id
        ) && ccgn_vouching_request_open (
            $applicant_id,
            $voucher_id
        ) && ccgn_user_is_vouched( $voucher_id );

        if ( ! $ok ) {
            // set the form validation to false
            $validation_result['is_valid'] = false;
        }

        //Assign modified $form object back to the validation result
        $validation_result['form'] = $form;
    }
    return $validation_result;
}

function ccgn_application_vouching_form_submit_handler ( $entry,
                                                         $form ) {
    if ( $form[ 'title' ] == CCGN_GF_VOUCH ) {
        //FIXME: This has to have been working but doesn't seem to be now,
        //       so do this.
        if (isset($entry[ CCGN_GF_VOUCH_APPLICANT_ID ])) {
            $applicant_id = $entry[ CCGN_GF_VOUCH_APPLICANT_ID ];
        } else {
            $applicant_id = $entry[ CCGN_GF_VOUCH_APPLICANT_ID_FIELD ];
        }
        $voucher_id = $entry[ 'created_by' ];
        $stage = ccgn_registration_user_get_stage( $applicant_id);
        // At this point in form processing we shouldn't disturb the submitter,
        // but should log this and not do anything else based on it.
        if ( $stage != CCGN_APPLICATION_STATE_VOUCHING ) {
            $error_message = "Vouch by " . $voucher_id . " for applicant "
                           . $applicant_id . " while at non-Vouching stage: "
                           . $stage;
            error_log($error_message);
            echo $error_message;
            return;
        }
        if (
            $entry[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
            == CCGN_GF_VOUCH_DO_YOU_VOUCH_CANNOT
        ) {
            ccgn_registration_email_voucher_cannot (
                $applicant_id,
                $voucher_id
            );
        } else {

        }
    }
}
