<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// This is the admin page for managing the user's application, on their profile
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
    return '<p><strong>Yes: </strong>'
        . $counts['yes']
        . '<p><strong>No: </strong>'
        . $counts['no']
        . '</p>';
}

// Format the list of members the applicant has asked to vouch for them

function ccgn_application_users_page_vouchers ( $applicant_id ) {
    $result = '<ol>';
    $vouchers = ccgn_application_vouchers_users ( $applicant_id );
    foreach ( $vouchers as $voucher ) {
        $result .= '<li>' . $voucher->display_name  . '</li>';
    }
    return $result . '</ol>';
}

function ccgn_registration_email_vouching_requests ( $applicant_id ) {
    $vouchers_ids = ccgn_application_vouchers_users_ids ( $applicant_id );
    foreach ( $vouchers_ids as $voucher_id ) {
        // TODO: Check for active user etc.
        ccgn_registration_email_vouching_request(
            $applicant_id,
            $voucher_id
        );
    }
}

// Handle pre form results

function ccgn_application_users_page_pre_form_submit_handler ( $entry,
                                                               $form ) {
    if (! current_user_can( 'administrator' ) ) {
        echo 'Must be admin';
        exit;
    }
    if ( $form[ 'title' ] == CCGN_GF_PRE_APPROVAL ) {
        $applicant_id = $entry[ CCGN_GF_PRE_APPROVAL_APPLICANT_ID ];
        $stage = ccgn_registration_user_get_stage( $applicant_id);
        if ( $stage != CCGN_APPLICATION_STATE_RECEIVED ) {
            echo 'User already pre-approved';
            return;
        }
        $application_status = $entry[
            CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $application_status == CCGN_GF_PRE_APPROVAL_APPROVED_YES ) {
            ccgn_user_level_set_pre_approved( $applicant_id );
            ccgn_registration_email_vouching_requests( $applicant_id );
        } else {
            ccgn_user_level_set_rejected( $applicant_id );
            ccgn_registration_email_application_rejected( $applicant_id );
        }
    }
}

// Format the pre approval form

function ccgn_application_users_page_pre_form ( $applicant_id ) {
    gravity_form(
            CCGN_GF_PRE_APPROVAL,
            false,
            false,
            false,
            array(
                CCGN_GF_PRE_APPROVAL_APPLICANT_ID_PARAMETER
                    => $applicant_id
            )
        );
}

// Handle final form results

function ccgn_application_users_page_final_form_submit_handler ( $entry,
                                                                      $form ) {
    if (! current_user_can( 'administrator' ) ) {
        echo 'Must be admin';
        return;
    }
    if ( $form[ 'title' ] == CCGN_GF_FINAL_APPROVAL ) {
        $applicant_id = $entry[ CCGN_GF_FINAL_APPROVAL_APPLICANT_ID ];
        $stage = ccgn_registration_user_get_stage( $applicant_id);
        if ( $stage != CCGN_APPLICATION_STATE_VOUCHING ) {
            echo 'User already post-approved';
            return;
        }
        $application_status = $entry[
            CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $application_status == CCGN_GF_FINAL_APPROVAL_APPROVED_YES ) {
            ccgn_user_level_set_approved( $applicant_id );
            ccgn_create_profile( $applicant_id );
            ccgn_registration_email_application_approved( $applicant_id );
        } else {
            ccgn_user_level_set_rejected( $applicant_id );
            ccgn_registration_email_application_rejected( $applicant_id );
        }
    }
}

// Format the approval form with any required pre-sets

function ccgn_application_users_page_final_form ( $applicant_id ) {
    $counts = ccgn_application_vouches_counts( $applicant_id );
    $yes = $counts['yes'];
    $no = $counts['no'];
    // Note that $approve and $decline indicate *disbling* these options
    if ( $no > 0 ) {
        echo 'Applicant received negative vouch, must be declined.';
        $approve = 'true';
        $decline = 'false';
    } elseif ( $yes >= CCGN_NUMBER_OF_VOUCHES_NEEDED ) {
        echo 'Applicant has enough vouches to approve.';
        $approve = 'false';
        $decline = 'false';
    } else {
        echo 'Applicant does not yet have enough vouches to approve but can be declined if needed.';
        $approve = 'true';
        $decline = 'false';
    }
    gravity_form(
            CCGN_GF_FINAL_APPROVAL,
            false,
            false,
            false,
            array(
                CCGN_GF_FINAL_APPROVAL_APPLICANT_ID_PARAMETER
                    => $applicant_id
            )
        );
    ?>
    <script>
    jQuery('document').ready(function () {
        jQuery('input[value="Yes"]').attr('disabled',
                                          <?php echo $approve; ?>);
        jQuery('input[value="No"]').attr('disabled',
                                          <?php echo $decline; ?>);
            });
    </script>
    <?php
}

function ccgn_application_users_page () {
    echo _('<h1>Membership Application Details</h1>');
    if ( ! isset( $_GET[ 'user_id' ] ) ) {
        echo _( '<br />No user id specified.' );
        return;
    }
    $applicant_id = filter_input( INPUT_GET, 'user_id', FILTER_VALIDATE_INT );
    if ($applicant_id === false) {
        echo _( '<br />Invalid user id.' );
        return;
    }
    if ( $applicant_id == get_current_user_id() ) {
        echo _( '<br />You cannot edit your own application status' );
        return;
    }
    $applicant = get_user_by( 'ID', $applicant_id );
    if( $applicant === false ) {
        echo _( '<br />Invalid user specified.' );
        return;
    }
    //FIXME: Check to see if really autovouched, check if not and should be
    if ( ccgn_user_level_should_autovouch( $applicant->user_email ) ) {
        echo '<br><h4><i>User was autovouched, no application details.</i></h4>';
        echo '<p>Autovouching is by CCID user email domain.</p>';
        return;
    }
    $state = ccgn_registration_user_get_stage ( $applicant_id );
    echo _('<h2>Details Provided By Applicant</h2>');
    echo ccgn_user_page_applicant_profile_text( $applicant_id );
    echo _('<h2>Vouchers Requested</h2>');
    echo ccgn_application_users_page_vouchers( $applicant_id );
    if ( $state != CCGN_APPLICATION_STATE_RECEIVED ) {
        echo _('<h2>Vouches Received</h2>');
        echo ccgn_application_users_page_vouch_counts ( $applicant_id );
        echo _('<h2>Vouches</h2>');
        echo ccgn_application_users_page_vouch_responses ( $applicant_id );
    }
    echo _('<h2>Global Council Approval</h2>');
    if ( $state == CCGN_APPLICATION_STATE_RECEIVED ) {
        ccgn_application_users_page_pre_form ( $applicant_id );
    } elseif ( $state == CCGN_APPLICATION_STATE_VOUCHING ) {
        ccgn_application_users_page_final_form ( $applicant_id );
    } else {
        echo 'Application resolved. Status: ' . $state;
        // TODO: show relevant pre/post form here for user rather than making
        // them search for it manually.
    }
}

function ccgn_application_user_application_page_url( $user_id ) {
    return admin_url(
        'users.php?page=global-network-membership&user_id='
        . $user_id
    );
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

function ccgn_application_users_menu () {
    add_users_page(
        'Global Network Membership',
        // No menu title, as we don't want to show up in the sidebar
        '',
        edit_users,
        'global-network-membership',
        'ccgn_application_users_page'
    );
}

function ccgn_hide_application_users_menu () {
    remove_submenu_page( 'users.php', 'ccgn-global-network-membership' );
}

// If the user is at the vouching / final approval stage, link to this page
// from the user's entry in the User list page.

function ccgn_application_user_link( $actions, $user_object ) {
    // Only show this if the user is at the pre-approval or vouching stages
    if ( ccgn_vouching_request_active( $user_object->ID ) ) {
        $actions['ccgn_application']
            = '<a href="'
            . ccgn_application_user_application_page_url(
                $user_object->ID
            )
            . '">Approval</a>';
    }
    return $actions;
}
