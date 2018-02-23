<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// This is the admin page for managing the user's application, on their profile
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Application evaluation and finalization
////////////////////////////////////////////////////////////////////////////////

function ccgn_must_decline_membership ( $applicant_id ) {
    $vouch_counts = ccgn_application_vouches_counts( $applicant_id );
    $vote_counts = ccgn_application_votes_counts( $applicant_id );
    return ( $vouch_counts[ 'no' ] > CCGN_NUMBER_OF_VOUCHES_AGAINST_ALLOWED )
        || ( $vote_counts[ 'no' ] > CCGN_NUMBER_OF_VOTES_AGAINST_ALLOWED );
}

function ccgn_can_activate_membership ( $applicant_id ) {
    $vouch_counts = ccgn_application_vouches_counts( $applicant_id );
    $vote_counts = ccgn_application_votes_counts( $applicant_id );
    return ( $vouch_counts[ 'yes' ] >= CCGN_NUMBER_OF_VOUCHES_NEEDED )
        && ( $vote_counts[ 'yes' ] >= CCGN_NUMBER_OF_VOTES_NEEDED );
}

function ccgn_activate_and_notify_member ( $applicant_id ) {
    ccgn_user_level_set_approved( $applicant_id );
    ccgn_create_profile( $applicant_id );
    ccgn_registration_email_application_approved( $applicant_id );
    //ccgn_application_remove_avatar ( $applicant_id );
}

function ccgn_decline_and_notify_applicant ( $applicant_id ) {
    ccgn_user_level_set_rejected( $applicant_id );
    ccgn_registration_email_application_rejected( $applicant_id );
    //ccgn_application_remove_avatar ( $applicant_id );
}

////////////////////////////////////////////////////////////////////////////////
// State formatting
////////////////////////////////////////////////////////////////////////////////

// Format the list of votes the member has received from their voters

function ccgn_application_users_page_vote_responses ( $applicant_id ) {
    $result = '';
    $votes = ccgn_application_votes ( $applicant_id );
    foreach ($votes as $vote) {
        $voter = get_user_by('ID', $vote['created_by']);
        $result .=
                '<h4>From: '
                . $voter->display_name
                . '</h4><p><strong>Voted:</strong> '
                .  $vote[
                    CCGN_GF_VOTE_APPROVE_MEMBERSHIP_APPLICATION
                ]
                . '</p>';
    }
    return $result;
}

// Format the count of votes

function ccgn_application_users_page_vote_counts ( $applicant_id ) {
    $counts = ccgn_application_votes_counts( $applicant_id );
    return '<p><strong>Yes: </strong>'
        . $counts['yes']
        . '<p><strong>No: </strong>'
        . $counts['no']
        . '</p>';
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

////////////////////////////////////////////////////////////////////////////////
// Embedded form setup and handling
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Handle pre form results

function ccgn_application_users_page_pre_form_submit_handler ( $entry,
                                                               $form ) {
    if ( $form[ 'title' ] == CCGN_GF_PRE_APPROVAL ) {
        if ( ! ( ccgn_current_user_is_membership_council()
                 || ccgn_current_user_is_final_approver() ) ) {
            echo 'Must be Membership Council member.';
            exit;
        }
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

////////////////////////////////////////////////////////////////////////////////
// Handle vote form results

function ccgn_application_users_page_vote_form_submit_handler ( $entry,
                                                                 $form ) {
    if ( $form[ 'title' ] == CCGN_GF_VOTE ) {
        if (! ( ccgn_current_user_is_membership_council()
             || ccgn_current_user_is_final_approver() ) ) {
            echo 'Must be Membership Council member.';
            exit;
        } elseif ( ccgn_vouching_request_exists( $applicant_id,
                                                 get_current_user_id() ) ) {
            // The user is Membership Council or Final Approver, but is also
            // a Voucher for the application and therefore should not also
            // Vote on it.
            echo 'Cannot Vote on Application you are a Voucher for.';
            exit;
        }
        $applicant_id = $entry[ CCGN_GF_VOTE_APPLICANT_ID ];
        $stage = ccgn_registration_user_get_stage( $applicant_id);
        if ( $stage != CCGN_APPLICATION_STATE_VOUCHING ) {
            echo 'User already post-approved';
            return;
        }
    }
}

function ccgn_application_users_page_vote_form ( $applicant_id ) {
    if ( ccgn_vouching_request_exists( $applicant_id,
                                       get_current_user_id() ) ) {
        echo _('<i>You have been asked to Vouch for this application, you therefore cannot Vote on it as well.</i>');
    } else {
        $entry = ccgn_application_vote_by_current_user ( $applicant_id );
        if ( $entry === false ) {
            gravity_form(
                CCGN_GF_VOTE,
                false,
                false,
                false,
                array(
                    CCGN_GF_VOTE_APPLICANT_ID_PARAMETER
                    => $applicant_id
                )
            );
        } else {
            echo _('<i>You have voted on this membership application</i>');
            $status = $entry[
                CCGN_GF_VOTE_APPROVE_MEMBERSHIP_APPLICATION
            ];
            if ( $status == CCGN_GF_VOTE_APPROVED_YES ) {
                echo _('<p>You voted yes.</p>');
            } else {
                echo _( '<p>You voted no.</p>' );
            }
        }
    }
}

////////////////////////////////////////////////////////////////////////////////
// Handle final approval form results

function ccgn_application_users_page_final_form_submit_handler( $entry,
                                                                $form ) {
    if ( $form[ 'title' ] == CCGN_GF_FINAL_APPROVAL ) {
        if (! ccgn_current_user_is_final_approver() ) {
            echo 'Must be Final Approver.';
            exit;
        }
        $applicant_id = $entry[ CCGN_GF_FINAL_APPROVAL_APPLICANT_ID ];
        $stage = ccgn_registration_user_get_stage( $applicant_id);
        if ( $stage != CCGN_APPLICATION_STATE_VOUCHING ) {
            echo 'User state is bad';
            return;
        }
        //FIXME: check vouch/vote counts
        $result = $entry[
            CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $result == CCGN_GF_FINAL_APPROVAL_APPROVED_YES ) {
            if ( ccgn_user_is_individual_applicant( $applicant_id ) ) {
                ccgn_activate_and_notify_member( $applicant_id );
            } elseif ( ccgn_user_is_institutional_applicant( $applicant_id ) ) {
                ccgn_registration_user_set_stage (
                    $applicant_id,
                    CCGN_APPLICATION_STATE_LEGAL
                );
            } else {
                echo 'User is not individual or institution???';
                return;
            }
        } else {
            ccgn_decline_and_notify_applicant ( $applicant_id );
        }
    }
}

// Format the final approval form with any required pre-sets

function ccgn_application_users_page_final_approval_form ( $applicant_id ) {
    $entry = ccgn_final_approval_entry_for ( $applicant_id );
    if ( $entry === false ) {
        gravity_form(
            CCGN_GF_FINAL_APPROVAL,
            false,
            false,
            false,
            array(
                CCGN_GF_FINAL_APPROVAL_APPLICANT_ID_PARAMETER
                => $applicant_id
            ),
            false,
            99 // Avoid tabindex clash if this is showing alongside Vote form
        );
        $approve = ccgn_can_activate_membership ( $applicant_id )
                 && ( ! ccgn_must_decline_membership ( $applicant_id ) );
        $decline = true;
        ?>
        <script>
             jQuery('document').ready(function () {
        <?php
        if ( ! $approve ) {
        ?>
            jQuery('.activate_membership input[value="Yes"]').attr('disabled',
                                                                   true);
        <?php
        }
        if ( ! $decline ) {
        ?>
            jQuery('.activate_membership input[value="No"]').attr('disabled',
                                                                  true);
        <?php
        }
        ?>
            });
        </script>
        <?php
    } else {
        echo _('<i>This application has been finalised</i>');
        $status = $entry[
            CCGN_GF_FINAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $status == CCGN_GF_FINAL_APPROVAL_APPROVED_YES ) {
            echo _('<p>It was approved.</p>');
        } else {
            echo _('<p>It was rejected.</p>');
        }
    }
}

////////////////////////////////////////////////////////////////////////////////
// Institutional legal approval form

function ccgn_application_users_page_legal_approval_form_submit_handler (
    $entry,
    $form
) {
    if ( $form[ 'title' ] == CCGN_GF_LEGAL_APPROVAL ) {
        if (! ccgn_current_user_is_legal_team() ) {
            echo 'Must be Legal Team Member.';
            exit;
        }
        $applicant_id = $entry[ CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID ];
        $stage = ccgn_registration_user_get_stage( $applicant_id);
        if ( $stage != CCGN_APPLICATION_STATE_LEGAL ) {
            echo 'User state is bad';
            return;
        }
        $result = $entry[
            CCGN_GF_LEGAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $result == CCGN_GF_LEGAL_APPROVAL_APPROVED_YES ) {
            if ( ccgn_user_is_institutional_applicant( $applicant_id ) ) {
                ccgn_activate_and_notify_member ( $applicant_id );
            } else {
                echo 'User is not institution???';
                return;
            }
        } else {
            ccgn_decline_and_notify_applicant ( $applicant_id );
        }
    }
}

function ccgn_application_users_page_legal_approval_form ( $applicant_id ) {
    $entry = ccgn_legal_approval_entry_for ( $applicant_id );
    if ( $entry === false ) {
        gravity_form(
            CCGN_GF_LEGAL_APPROVAL,
            false,
            false,
            false,
            array(
                CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID_PARAMETER
                => $applicant_id
            )
        );
    }
}

function ccgn_application_format_legal_approval ( $applicant_id, $state ) {
    if(
        ! in_array(
            $state,
            CCGN_APPLICATION_STATE_LEGAL_APPROVAL_STATE_AVAILABLE
        )
    ) {
        return;
    }
    echo _('<h2>Legal Final Approval Of Institutional Application</h2>');
    if ( $state === CCGN_APPLICATION_STATE_LEGAL) {
        if ( ccgn_current_user_is_legal_team() ) {
            ccgn_application_users_page_legal_approval_form ( $applicant_id );
        } else {
            echo _('<p>Legal final approval pending.</p>');
        }
    }
    elseif ( in_array( $state, CCGN_APPLICATION_STATE_PAST_APPROVAL ) ) {
        echo _('<i>Legal has resolved this membership application</i>');
        $entry = ccgn_legal_approval_entry_for ( $applicant_id );
        $status = $entry[
            CCGN_GF_LEGAL_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $status == CCGN_GF_LEGAL_APPROVAL_APPROVED_YES ) {
            echo _('<p>The Institution\'s application was resolved successfully.</p>');
        } else {
            echo _('<p>The Institution\'s application was <i>not</i> resolved successfully.</p>');
        }
    }
}

////////////////////////////////////////////////////////////////////////////////
// Render the page
////////////////////////////////////////////////////////////////////////////////

function ccgn_application_user_page_render_change_vouchers ( $applicant_id,
                                                             $state ) {
    if ( current_user_can( 'ccgn_pre_approve' ) ) {
        echo _('<h3>Change Vouch Requests</h3>');
        if ( $state == CCGN_APPLICATION_STATE_VOUCHING ) {
            echo '<p><a href="'
                . ccgn_application_change_vouchers_page_url( $applicant_id )
                . '">';
            echo _('Change vouch requests for applicant.');
            echo '</a></p>';
        } else {
            echo _('<p>Applicant is not currently being Vouched, cannot change Vouch Requests.</p>');
        }
    }
}

function ccgn_application_users_page_render_state ( $applicant_id, $state ) {
    if ( $state == CCGN_APPLICATION_STATE_RECEIVED ) {
        echo _('<h3>Pre-Approve</h3>');
        ccgn_application_users_page_pre_form ( $applicant_id );
    } elseif ( $state == CCGN_APPLICATION_STATE_VOUCHING ) {
        echo _('<h3>Vote</h3>');
        ccgn_application_users_page_vote_form ( $applicant_id );
        if ( ccgn_current_user_is_final_approver() ) {
            echo _('<h3>Final Approval</h3>');
            ccgn_application_users_page_final_approval_form( $applicant_id );
        }
    } elseif ( $state == '' ) {
        echo _('<h2>New User.</h2>');
        echo _("<p>They haven't completed an application yet.</p>");
    } else {
        echo _('<h2>Application resolved.</h2>');
        echo '<p>Status: ' . $state . '</p>';
        // TODO: show relevant pre/post form here for admin rather than making
        // them search for it manually.
    }
}

function ccgn_application_users_page_render_details ( $applicant_id, $state ) {
    echo _('<h1>Membership Application Details</h1>');
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
    echo _('<h3>Votes Received</h3>');
    echo ccgn_application_users_page_vote_counts ( $applicant_id );
    echo _('<h3>Votes</h3>');
    echo ccgn_application_users_page_vote_responses ( $applicant_id );
}

function ccgn_application_users_page () {
    if ( ! ccgn_current_user_can_see_user_application_page () ) {
        echo _( '<br />Sorry, you are not allowed to access this page.' );
    }
    $applicant_id = ccgn_request_applicant_id ();
    if ( $applicant_id === false ) {
        return;
    }
    $state = ccgn_registration_user_get_stage( $applicant_id );
    ccgn_application_users_page_render_details( $applicant_id, $state );
    ccgn_application_users_page_render_state( $applicant_id, $state );
    if ( ccgn_user_is_institutional_applicant( $applicant_id ) ) {
        ccgn_application_format_legal_approval( $applicant_id, $state );
    }
    ccgn_application_user_page_render_change_vouchers ( $applicant_id, $state );
}

function ccgn_application_user_application_page_url( $user_id ) {
    return admin_url(
        'admin.php?page=global-network-application&user_id='
        . $user_id
    );
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

function ccgn_application_users_menu () {
    add_submenu_page(
        null,
        'Global Network Membership',
        'Global Network Membership',
        'ccgn_view_applications',
        'global-network-application',
        'ccgn_application_users_page'
    );
}

function ccgn_hide_application_users_menu () {
    remove_submenu_page(
        'global-network-application-approval',
        'ccgn-global-network-membership'
    );
}

// If the user is at the vouching / approval stage, link to this page
// from the user's entry in the User list page.

function ccgn_application_user_link( $actions, $user_object ) {
    // Only show this if the user is at the pre-approval or vouching stages
    if ( ccgn_current_user_can_see_user_application_page()
         && ccgn_user_is_new( $user_object->ID ) ) {
        $actions['ccgn_application']
            = '<a href="'
            . ccgn_application_user_application_page_url(
                $user_object->ID
            )
            . '">Approval</a>';
    }
    return $actions;
}
