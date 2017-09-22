<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// This is the admin page for managing the user's application, on their profile
////////////////////////////////////////////////////////////////////////////////

// Format the list of vouches the member has received from their vouchers

function commoners_application_users_page_vouch_responses ( $applicant_id ) {
    $result = '';
    $vouches = commoners_applicantion_vouches ( $applicant_id );
    foreach ($vouches as $vouch) {
        $voucher = get_user_by('ID', $vouch['created_by']);
        $result .=
                '<h4>From: '
                . $voucher->user_nicename
                . '</h4><p><strong>Vouched:</strong> '
                . rgar( $vouch, COMMONERS_GF_VOUCH_DO_YOU_VOUCH)
                . '</p><p><strong>Reason:</strong> '
                . rgar( $vouch, COMMONERS_GF_VOUCH_REASON)
                . '</p>';
    }
    return $result;
}

// Format the count of vouches

function commoners_application_users_page_vouch_counts ( $applicant_id ) {
    $counts = commoners_applicantion_vouches_counts( $applicant_id );
    return '<p><strong>Yes: </strong>'
        . $counts['yes']
        . '<p><strong>No: </strong>'
        . $counts['no']
        . '</p>';
}

// Format the list of members the applicant has asked to vouch for them

function commoners_application_users_page_vouchers ( $applicant_id ) {
    $result = '<ol>';
    $vouchers = commoners_application_vouchers_users ( $applicant_id );
    foreach ( $vouchers as $voucher ) {
        $result .= '<li>' . $voucher->display_name  . '</li>';
    }
    return $result . '</ol>';
}

function commoners_registration_email_vouching_requests ( $applicant ) {
    $vouchers_ids = commoners_application_vouchers_users_ids ( $applicant_id );
    foreach ( $vouchers_ids as $voucher_id ) {
        // TODO: Check for active user etc.
        commoners_registration_email_vouching_request(
            $applicant,
            $voucher_id
        );
    }
}

// Handle pre form results

function commoners_application_users_page_pre_form_submit_handler ( $entry,
                                                                    $form ) {
    if (! current_user_can( 'administrator' ) ) {
        echo 'Must be admin';
        exit;
    }
    if ( $form[ 'name' ] == COMMONERS_GF_PRE_APPROVAL ) {
        $applicant_id = $entry[ COMMONERS_GF_PRE_APPROVAL_APPLICANT_ID ];
        $applicant = new WP_User( $applicant_id );
        $stage = commoners_registration_user_get_stage( $applicant_id);
        if ( $stage != COMMONERS_APPLICATION_STATE_RECEIVED ) {
            echo 'User already pre-approved';
            return;
        }
        $application_status = $entry[
            COMMONERS_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $application_status == COMMONERS_GF_PRE_APPROVAL_APPROVED_YES ) {
            commoners_user_level_set_pre_approved( $applicant );
            commoners_registration_email_vouching_requests( $applicant );
        } else {
            commoners_user_level_set_rejected( $applicant );
            commoners_registration_email_application_rejected( $applicant );
        }
    }
}

// Format the pre approval form

function commoners_application_users_page_pre_form ( $applicant_id ) {
    gravity_form(
            COMMONERS_GF_PRE_APPROVAL,
            false,
            false,
            false,
            array(
                COMMONERS_GF_PRE_APPROVAL_APPLICANT_ID => applicant_id
            )
        );
}

// Handle final form results

function commoners_application_users_page_final_form_submit_handler ( $entry,
                                                                $form ) {
    if (! current_user_can( 'administrator' ) ) {
        echo 'Must be admin';
        return;
    }
    if ( $form[ 'name' ] == COMMONERS_GF_FINAL_APPROVAL ) {
        $applicant_id = $entry[ COMMONERS_GF_FINAL_APPROVAL_APPLICANT_ID ];
        $applicant = new WP_User( $applicant_id );
        $stage = commoners_registration_user_get_stage( $applicant_id);
        if ( $stage != COMMONERS_APPLICATION_STATE_VOUCHING ) {
            echo 'User already post-approved';
            return;
        }
        $application_status = $entry[
            COMMONERS_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $application_status == COMMONERS_GF_FINAL_APPROVAL_APPROVED_YES ) {
            commoners_user_level_set_approved( $applicant );
            commoners_create_profile( $applicant );
            commoners_registration_email_application_approved( $applicant );
        } else {
            commoners_user_level_set_rejected( $applicant );
            commoners_registration_email_application_rejected( $applicant );
        }
    }
}

// Format the approval form with any required pre-sets

function commoners_application_users_page_final_form ( $applicant_id ) {
    $counts = commoners_applicantion_vouches_counts( $applicant_id );
    $yes = $counts['yes'];
    $no = $counts['no'];
    // Note that $approve and $decline indicate *disbling* these options
    if ( $no > 0 ) {
        echo 'Applicant received negative vouch, must be declined.';
        $approve = 'true';
        $decline = 'false';
    } elseif ( $yes >= COMMONERS_NUMBER_OF_VOUCHES_NEEDED ) {
        echo 'Applicant has enough vouches to approve.';
        $approve = 'false';
        $decline = 'false';
    } else {
        echo 'Applicant does not yet have enough vouches to approve but can be declined if needed.';
        $approve = 'true';
        $decline = 'false';
    }
    gravity_form(
            COMMONERS_GF_FINAL_APPROVAL,
            false,
            false,
            false,
            array(
                COMMONERS_GF_FINAL_APPROVAL_APPLICANT_ID => applicant_id
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

function commoners_application_users_page () {
    echo _('<h1>Membership Application Details</h1>');
    if ( ! isset( $_GET[ 'user_id' ] ) ) {
        echo _( 'No user id specified.' );
        return;
    }
    $applicant_id = filter_input( INPUT_GET, 'user_id', FILTER_VALIDATE_INT );
    if ($applicant_id === false) {
        echo _( 'Invalid user id.' );
        return;
    }
    if ( $applicant_id == get_current_user_id() ) {
        echo _( 'You cannot edit your own application status' );
        return;
    }
    if ( ! commoners_vouching_request_active ( $applicant_id ) ) {
        echo _( 'User is not currently applying to become a member.' );
        return;
    }
    $state = commoners_registration_user_get_stage ( $applicant_id );
    echo _('<h2>Details Provided By Applicant</h2>');
    echo commoners_vouching_form_profile_text( $applicant_id );
    echo _('<h2>Vouchers Requested</h2>');
    echo commoners_application_users_page_vouchers( $applicant_id );
    if ( $state != COMMONERS_APPLICATION_STATE_RECEIVED ) {
        echo _('<h2>Vouches Received</h2>');
        echo commoners_application_users_page_vouch_counts ( $applicant_id );
        echo _('<h2>Vouches</h2>');
        echo commoners_application_users_page_vouch_responses ( $applicant_id );
    }
    echo _('<h2>Global Council Approval</h2>');
    if ( $state == COMMONERS_APPLICATION_STATE_RECEIVED ) {
        commoners_application_users_page_pre_form ( $applicant_id );
    } elseif ( $state == COMMONERS_APPLICATION_STATE_VOUCHING ) {
        commoners_application_users_page_final_form ( $applicant_id );
    } else {
        echo 'Application resolved. Status: ' . $state;
        // TODO: show relevant pre/post form here for user rather than making
        // them search for it manually.
    }
}

function commoners_application_user_application_page_url( $user_id ) {
    return admin_url(
        'users.php?page=commoners-global-network-membership&user_id='
        . $user_id
    );
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

function commoners_application_users_menu () {
    add_users_page(
        // No menu title, as we don't want to show up in the sidebar
        NULL,
        'Global Network Membership',
        edit_users,
        'commoners-global-network-membership',
        'commoners_application_users_page'
    );
}

// If the user is at the vouching / final approval stage, link to this page
// from the user's entry in the User list page.

function commoners_application_user_link( $actions, $user_object ) {
    // Only show this if the user is at the pre-approval or vouching stages
    if ( commoners_vouching_request_active( $user_object->ID ) ) {
        $actions['commoners_application']
            = '<a href="'
            . commoners_application_user_application_page_url(
                $user_object->ID
            )
            . '">Approval</a>';
    }
    return $actions;
}
