<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// This is the admin page for managing the user's application, on their profile
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Application evaluation and finalization
////////////////////////////////////////////////////////////////////////////////

function ccgn_membership_council_voting_disabled_for ( $applicant_id ) {
    $vote_counts = ccgn_application_votes_counts( $applicant_id );
    return $vote_counts[ 'no' ] > CCGN_NUMBER_OF_VOTES_AGAINST_ALLOWED;
}

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
        $result .= '<div class="ccgn-box applicant">';
        $result .= '<h4>'. $voter->display_name . '</h4>'
                .'<p><strong>Voted:</strong>'
                .  $vote[
                    CCGN_GF_VOTE_APPROVE_MEMBERSHIP_APPLICATION
                ]
                . '</p>';
                $result .= '</div>';
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

//NOTE: WE DON'T OVERWRITE THESE FORM ENTRIES, WE ADD NEW ONES EACH TIME.
//      EVERYTHING SHOULD BE LIKE THIS BUT THAT WAS A BAD DESIGN DECISION.
//      Also note that this means we only have to check the creation date.
//      - RobM.

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
        if ( ! in_array( $stage, CCGN_APPLICATION_STATE_CAN_BE_PRE_APPROVED )) {
            echo 'User already pre-approved';
            return;
        }
        $application_status = $entry[
            CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
        ];
        if ( $application_status == CCGN_GF_PRE_APPROVAL_APPROVED_YES ) {
            ccgn_user_level_set_pre_approved( $applicant_id );
            ccgn_registration_email_vouching_requests( $applicant_id );
        } elseif (
            $application_status
            == CCGN_GF_PRE_APPROVAL_APPROVED_UPDATE_DETAILS
        ) {
            // Set state
            ccgn_registration_user_set_stage(
                $applicant_id,
                CCGN_APPLICATION_STATE_UPDATE_DETAILS
            );
            // Notify user
            $update_message = $entry[
                CCGN_GF_PRE_APPROVAL_APPLICANT_MUST_UPDATE_DETAILS
            ];
            ccgn_registration_email_to_applicant (
                $applicant_id,
                'ccgn-email-update-details',
                $update_message
            );
        } else {
            ccgn_user_level_set_rejected( $applicant_id );
            ccgn_registration_email_application_rejected( $applicant_id );
        }
    }
}

// Format the pre approval form

function ccgn_application_users_page_pre_form ( $applicant_id ) {
    // Slightly naughty - [][0] works fine here when there is no previous entry
    $existing_entry = ccgn_entries_referring_to_user (
        $applicant_id,
        CCGN_GF_PRE_APPROVAL,
        CCGN_GF_PRE_APPROVAL_APPLICANT_ID
    )[0];
    gravity_form(
        CCGN_GF_PRE_APPROVAL,
        false,
        false,
        false,
        array(
            CCGN_GF_PRE_APPROVAL_APPLICANT_ID_PARAMETER
            => $applicant_id,
            // If the user is in the update details state, we show the
            // information for this.
            CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION_PARAMETER
            => $existing_entry[
                CCGN_GF_PRE_APPROVAL_APPROVE_MEMBERSHIP_APPLICATION
            ],
            CCGN_GF_PRE_APPROVAL_APPLICANT_MUST_UPDATE_DETAILS_PARAMETER
            => $existing_entry[
                CCGN_GF_PRE_APPROVAL_APPLICANT_MUST_UPDATE_DETAILS
            ]
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
            echo 'User already post-approved (or updating vouchers)';
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
                // Notify the applicant that they are at the legal stage
                ccgn_registration_email_institution_legal ( $applicant_id );
                ccgn_registration_user_set_stage (
                    $applicant_id,
                    CCGN_APPLICATION_STATE_LEGAL
                );
                // Notify legal that the applicant is at their stage
                ccgn_registration_email_notify_legal_insititution_approved (
                    $applicant_id
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
        if ( $state == CCGN_APPLICATION_STATE_VOUCHING ) {
            echo '<p><a href="'
                . ccgn_application_change_vouchers_page_url( $applicant_id )
                . '" class="button">';
            echo _('Change vouch requests for applicant.');
            echo '</a></p>';
        } else {
            echo _('<p>Applicant is not currently being Vouched, cannot change Vouch Requests.</p>');
        }
    }
}

function ccgn_application_users_page_render_state ( $applicant_id, $state ) {
    if ( in_array( $state, CCGN_APPLICATION_STATE_CAN_BE_PRE_APPROVED ) ) {
        echo _('<h3>Pre-Approve</h3>');
        ccgn_application_users_page_pre_form ( $applicant_id );
    } elseif ( $state == CCGN_APPLICATION_STATE_VOUCHING ) {
        if (ccgn_current_user_is_final_approver() || ccgn_current_user_is_membership_council()) {
            if ( ccgn_application_can_be_voted( $applicant_id ) ) {
                echo _('<h3>Vote</h3>');
                ccgn_application_users_page_vote_form ( $applicant_id );
            }
            if (ccgn_current_user_is_final_approver()) {
                echo _('<h3>Final Approval</h3>');
                ccgn_application_users_page_final_approval_form( $applicant_id );
            }
        }
    } elseif ( ccgn_membership_council_voting_disabled_for ( $applicant_id ) ) {
        echo _('<h3>Voted Against</h3>');
        echo _('<p>Membership Council Members voted against this application, so it cannot proceed.</p>');
    } elseif ( $state == CCGN_APPLICATION_STATE_ON_HOLD ) {
        echo _('<h3>On Hold</h3>');
        echo _('<p>The application has been put on hold.</p>');
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
    echo '<div id="alert-messages"></div>';
    echo _('<h2>Details Provided By Applicant</h2>');
    echo ccgn_user_page_applicant_profile_text( $applicant_id );
    echo '<br /><h1 class="section-title">Vouchers information</h1>';
    echo '<div class="applicant-columns">';
        echo '<div class="ccgn-box">';
            echo _('<h2>Vouchers Requested</h2>');
            $voucher_choices = ccgn_application_vouchers ( $applicant_id );
            
            echo '<p> <span class="dashicons dashicons-calendar-alt"></span> <b>Original request date:</b> '
                . date('Y-m-d', strtotime($voucher_choices['date_created']))
                . '</p>';
            if (! is_null ( $voucher_choices[ 'date_updated' ]  ) ) {
                echo '<p> <span class="dashicons dashicons-calendar-alt"></span> <b>Updated request date:</b> '
                    . date('Y-m-d', strtotime($voucher_choices['date_updated']))
                    . '</p>';
            }
            echo ccgn_application_users_page_vouchers( $applicant_id );
    echo '</div>';
    
    if ( $state != CCGN_APPLICATION_STATE_RECEIVED ) {
            echo '<div class="ccgn-box">';
            echo _('<h2>Vouches Received</h2>');
                echo ccgn_application_users_page_vouch_counts ( $applicant_id );
                ccgn_application_user_page_render_change_vouchers(
                    $applicant_id,
                    $state
                );
            echo '</div>';
        
        $clarification_mode = get_user_meta(get_current_user_id(), 'ccgn_need_to_clarify_vouch_reason', true);
        if ( isset($_GET['clarification']) && $clarification_mode ) {
            echo '</div>';
            echo '<div class="applicant-columns">';
            echo '<div id="voucher-clarification-container">';
                echo '<a name="voucher-clarification"></a>';
                echo '<h3>Clarification of your voucher</h3>';
                echo '<div id="change-voucher-messages"></div>';
                $form_id = RGFormsModel::get_form_id(CCGN_GF_VOUCH);
                
                $search_criteria = array();
                $search_criteria['field_filters'][]
                    = array(
                    'key' => 'created_by',
                    'value' => get_current_user_id(),
                );
                $search_criteria['field_filters'][]
                    = array(
                    'key' => CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
                    'value' => $applicant_id,
                );
                $get_the_entries =  GFAPI::get_entries(
                    $form_id,
                    $search_criteria,
                    array(
                        array(
                            'key' => 'date_created',
                            'direction' => 'ASC',
                            'is_numeric' => false
                        )
                    )
                );
                //echo '<pre>'; print_r($get_the_entries); echo '</pre>';
                $entry_id = $get_the_entries[0]['id'];
                
                echo wp_nonce_field('clarification_voucher', 'clarification_voucher_nonce', true, false);
                echo '<p><textarea name="clarification_voucher" id="clarification_voucher" cols="50" rows="10">'. $get_the_entries[0]['4'] .'</textarea></p>';
                echo '<button class="button button-primary" id="set-new-vouch-reason" data-entry-id="'.$entry_id.'">Set new reason</button>';
            echo '</div>';
        }
        echo '</div><br>';
        echo _('<h1 class="section-title">Vouchers list</h1>');
        echo '<div class="applicant-columns">';
        $vouchers = ccgn_application_users_page_vouch_responses_data(
            $applicant_id,
            true
        );
        foreach ($vouchers as $voucher) {
            $asked = get_user_meta($voucher['id'], 'ccgn_need_to_clarify_vouch_reason', true);
            $asked_info = get_user_meta($voucher['id'], 'ccgn_need_to_clarify_vouch_reason_applicant_status', true);
            $user_is_asked_for_clarification = 0;
            $applicant_votes = ccgn_application_votes_counts($applicant_id);
            $asked_class = '';
            $who_asked = '';
            //print_r($voucher);
            if (empty($asked_info['user_id'])) {
                $log_user = ccgn_ask_clarification_log_get_id($applicant_id);
                foreach ($log_user as $entry) {
                    $asked_meta = get_user_meta($entry['voucher_id'], 'ccgn_need_to_clarify_vouch_reason_applicant_status', true);
                    if (($asked_meta['status'] == 1) && ($entry['voucher_id'] == $voucher['id'])) {
                        $user_is_asked_for_clarification = 1;
                        $asked_class = 'asked-box';
                        $who_asked = (!empty($asked_info['ask_user'])) ? get_user_by('ID',$asked_info['ask_user'])->display_name : $entry['ask_user_name'];
                    } else if (($asked_meta['status'] == 0) && ($entry['voucher_id'] == $voucher['id'])) {
                        $user_is_asked_for_clarification = 2;
                        $asked_class = 'asked-box-answered';
                        $who_asked = (!empty($asked_info['ask_user'])) ? get_user_by('ID',$asked_info['ask_user'])->display_name : $entry['ask_user_name'];
                    }
                }

            } else {
                $log_user = ccgn_ask_clarification_log_get_id($asked_info['applicant_id']);
                $who_asked = (!empty($asked_info['ask_user'])) ? get_user_by('ID',$asked_info['ask_user'])->display_name : $log_user[count($log_user)-1]['ask_user_name'];
                $user_is_asked_for_clarification = $asked_info['status'];
            }
            echo '<div class="ccgn-box applicant '.$asked_class.'">';
                //echo '<div class="icon"><span class="dashicons dashicons-admin-users"></span></div>';
                echo '<h3 class="applicant-name">'.$voucher['name'].'</h3>';
                echo '<span class="date">'.$voucher['date'].'</span>';
                if ($user_is_asked_for_clarification) {
                    echo '<br><small><em>Asked for clarification</em></small>';
                    if (!empty($who_asked)) {
                        echo '<br><small><em><strong>Asked by:</strong> '.$who_asked.'</em></small>';
                    }
                }
                if ($voucher['vouched'] == 'Yes') {
                    echo '<p class="applicant-reason">' . $voucher['reason'] . '</p>';
                } else {
                    if ($applicant_votes['yes'] < 5) {
                        echo '<p class="applicant-reason">' . $voucher['reason_no'] . '</p>';
                    }
                }
                echo '<p class="state"><strong>Vouched:</strong> '.$voucher['vouched'].'</p>';
                if (($voucher['vouched'] == 'Yes') && (ccgn_current_user_is_final_approver($applicant_id) || ccgn_current_user_is_membership_council($applicant_id)) ) {
                    echo '<a href="#" onClick="$.askVoucher('.$voucher['id'].',\''.$voucher['name'].'\','.$applicant_id.')" class="button">Ask for clarification</a>';
                }
            
            echo '</div>';
        }
        echo '</div>';
        // echo  cgn_application_users_page_vouch_responses (
        //     $applicant_id,
        //     true
        // );
        add_thickbox();
        echo '<div id="ask-clarification-modal" style="display:none;">';
            echo '<h2>You are about to ask for clarification to the voucher: <span class="name-display"></span></h2>';
            echo '<p>That means you think the text supporting this application is not enough, is not clear or is not helpful for you to approve this application. If that is the case, you can ask the voucher to clarify.</p>';
            
            
            echo '<div class="log-content" id="log-content-ask-voucher">';
                echo '<p>This already was requested by: </p>';
                echo '<div class="inner-scroll medium">';
                    echo '<ol class="log-entries" id="log-entry-ask-voucher">';

                    //foreach ($log as $entry) {
                      //  echo '<li><div class="log-entry"><strong>'.$entry['ask_user_name'].'</strong> asked on <span class="date">'.$entry['date'].'</span></div></li>';
                    //}
                    echo '</ol>';
                echo '</div>';
                echo '<p>There is no need to send this email again to the voucher. In case you consider that necessary, you can do it again.</p>';
            echo '</div>';
            
            echo '<p>Are you sure you want to do this? </p>';
            echo '<br>';
            echo wp_nonce_field('ask_voucher', 'ask_voucher_nonce', true, false);
            echo '<div class="buttons">';
                echo '<button id="close-ask-voucher" class="button close-window">Close</button> ';
                echo "<button id=\"ask-voucher-for-sure\"  class=\"button button-primary ask-voucher-for-sure\">Yes, I'm sure</button>";
            echo '</div>';
            //echo '</p>';
        echo '</div>';
        echo '<div id="change-voucher-modal" style="display:none;">';
            echo '<h2>You are about to change the current voucher: <span class="name-display"></span></h2>';
            echo '<div class="gform_wrapper">';
            gravity_form_enqueue_scripts( 41, false );
            $choices = array();
            $members = ccgn_registration_form_list_members(get_current_user_id());
            foreach ($members as $member) {
                $choices[] = array(
                    'text' => $member[1].' ('.$member[2].')',
                    'value' => $member[0],
                    'is_selected' => false
                );
            }
            $field_properties = array(
                'type' => 'select',
                'enableEnhancedUI' => true,
                'id' => 'changeVoucher',
                'cssClass' => 'custom-select-changer',
                'choices' => $choices
            );
            $field = GF_Fields::create($field_properties);
            echo $field->get_field_input();
            echo '</div>';
            echo '<br>';
            echo wp_nonce_field('change_voucher', 'change_voucher_nonce', true, false);
            echo '<div class="buttons">';
                echo '<button id="close-change-voucher" class="button close-window">Close</button> ';
                echo "<button id=\"change-voucher-for-sure\"  class=\"button button-primary change-voucher-for-sure\">Change</button>";
            echo '</div>';
            //echo '</p>';
        echo '</div>';

    } else {
        echo '</div><br>';
    }
    echo _('<br><h1 class="section-title">Global Council Approval</h2>');
    echo '<div class="applicant-columns">';
        echo '<div class="ccgn-box">';
            echo _('<h3>Votes Received</h3>');
            echo ccgn_application_users_page_vote_counts ( $applicant_id );
        echo '</div>';
    echo '</div>';
    echo _('<h3>Votes</h3>');
    echo '<div class="applicant-columns">';
        echo ccgn_application_users_page_vote_responses ( $applicant_id );
    echo '</div>';
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

function ccgn_user_get_vouch_entry($applicant_id) {
    $form_id = RGFormsModel::get_form_id(CCGN_GF_VOUCH);

    $search_criteria = array();
    $search_criteria['field_filters'][]
        = array(
        'key' => 'created_by',
        'value' => get_current_user_id(),
    );
    $search_criteria['field_filters'][]
        = array(
        'key' => CCGN_GF_VOUCH_APPLICANT_ID_FIELD,
        'value' => $applicant_id,
    );
    $get_the_entries = GFAPI::get_entries(
        $form_id,
        $search_criteria,
        array(
            array(
                'key' => 'date_created',
                'direction' => 'ASC',
                'is_numeric' => false
            )
        )
    );
    $return = array(
        'entry_id' => $get_the_entries[0]['id'],
        'entry_text' => $get_the_entries[0]['4']
    );
    return $return;
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

// Ajax function
// Executed in the UI when a MC member ask to a voucher for clarification
// @user_id : user to be notified
function ccgn_ajax_ask_voucher()
{
    $user_id = $_POST['user_id'];
    $applicant_id = $_POST['applicant_id'];
    $current_user_ask = get_current_user_id();
    if (check_ajax_referer('ask_voucher', 'sec') && (!empty($user_id))) {
        ccgn_ask_email_vouching_request($applicant_id,$user_id);
        //set user state to clarification of the reason to vouch applicant
        $update_ask_status = array('status' => 1, 'applicant_id' => $applicant_id, 'user_id' => $user_id, 'ask_user' => $current_user_ask, 'time' => time());
        update_user_meta($user_id, 'ccgn_need_to_clarify_vouch_reason_applicant_status', $update_ask_status );
        update_user_meta($user_id,'ccgn_need_to_clarify_vouch_reason',1);
        ccgn_ask_clarification_log_append($applicant_id,$user_id);
        echo 'ok';
    }
    exit(0);
}
add_action('wp_ajax_nopriv_ask_voucher', 'ccgn_ajax_ask_voucher');
add_action('wp_ajax_ask_voucher', 'ccgn_ajax_ask_voucher');

function ccgn_ajax_change_voucher()
{
    $voucher_id = esc_attr($_POST['voucher_id']);
    $applicant_id = esc_attr($_POST['applicant_id']);
    $position = esc_attr($_POST['position']);
    $new_voucher = esc_attr( $_POST['new_voucher'] );

    if (check_ajax_referer('change_voucher', 'sec') && (!empty($new_voucher))) {

        $form_id = RGFormsModel::get_form_id(CCGN_GF_CHOOSE_VOUCHERS);

        $search_criteria = array();
        $search_criteria['field_filters'][]
            = array(
            'key' => 'created_by',
            'value' => $applicant_id,
        );
        
        $get_the_entries = GFAPI::get_entries(
            $form_id,
            $search_criteria,
            array(
                array(
                    'key' => 'date_created',
                    'direction' => 'ASC',
                    'is_numeric' => false
                )
            )
        );
        $entry_id = $get_the_entries[0]['id'];
        $update_date = GFAPI::update_entry_field($entry_id, 'date_updated', date('Y-m-d H:m:s'));
        $change_voucher_result = GFAPI::update_entry_field($entry_id, $position, $new_voucher);
        if ($change_voucher_result) {
            //send email to the new voucher
            $send_mail = ccgn_registration_email_vouching_request(
                $applicant_id,
                $new_voucher
            );
            echo 'ok';
        } else {
            echo 'error';
        } 
    } else {
        echo 'error';
    }
    exit(0);
}
add_action('wp_ajax_nopriv_change_voucher', 'ccgn_ajax_change_voucher');
add_action('wp_ajax_change_voucher', 'ccgn_ajax_change_voucher');

// Save new reason to vouch in order to clarify the reason of the vouched user
function ccgn_ajax_modify_reason_voucher()
{
    $user_id = get_current_user_id();
    $applicant_id = esc_attr($_POST['applicant_id']);
    $new_reason = esc_attr($_POST['new_reason']);
    $entry_id = esc_attr($_POST['entry_id']);
    if (check_ajax_referer('clarification_voucher', 'sec') && (!empty($user_id)) && (!empty($entry_id)) && (!empty($new_reason)) ) {
        //ccgn_ask_email_vouching_request($applicant_id, $user_id);
        $update_date = GFAPI::update_entry_field($entry_id, 'date_updated', date('Y-m-d H:m:s'));
        $reasonchange_result = GFAPI::update_entry_field($entry_id, CCGN_GF_VOUCH_REASON,$new_reason);
        if ($reasonchange_result) {
            echo 'ok';
            $update_ask_status = array('status' => 0, 'applicant_id' => $applicant_id);
            update_user_meta($user_id, 'ccgn_need_to_clarify_vouch_reason_applicant_status', $update_ask_status);
            update_user_meta($user_id, 'ccgn_need_to_clarify_vouch_reason', 0);
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
    exit(0);
}
add_action('wp_ajax_nopriv_reason_voucher', 'ccgn_ajax_modify_reason_voucher');
add_action('wp_ajax_reason_voucher', 'ccgn_ajax_modify_reason_voucher');