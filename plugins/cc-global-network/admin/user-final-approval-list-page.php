<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Self-consistency check utilities
// COPYPASTA AND SINGLE LETTER VARIABLE NAMES WITH FOREACH LOOPS AHEAD.
////////////////////////////////////////////////////////////////////////////////

function _ccgn_all_subscriber_ids () {
    $u = get_users(['role' => 'subscriber']);
    $v = array();
    foreach ($u as $w) {
        $v[] = $w->ID;
    }
    sort($v);
    return $v;
}

function _ccgn_all_no_role_ids () {
    $u = get_users();
    $v = array();
    foreach ($u as $w) {
        if($w->roles == []) {
            $v[] = $w->ID;
        }
    }
    sort($v);
    return $v;
}

function _ccgn_all_final_approval_form_approved_applicant_ids () {
    $a = ccgn_new_final_approvals_since (false, false);
    $d = array();
    foreach ($a as $b) {
        $d[] = intval($b[CCGN_GF_FINAL_APPROVAL_APPLICANT_ID]);
    }
    sort($d);
    return $d;
}

function _ccgn_all_final_approval_form_declined_applicant_ids () {
    $a = ccgn_new_final_approvals_declined_since (false, false);
    $d = array();
    foreach ($a as $b) {
        $d[] = intval($b[CCGN_GF_FINAL_APPROVAL_APPLICANT_ID]);
    }
    sort($d);
    return $d;
}

function _ccgn_all_legal_approval_form_approved_applicant_ids () {
    $a = ccgn_new_legal_approvals_since (false, false);
    $d = array();
    foreach ($a as $b) {
        $d[] = intval($b[CCGN_GF_FINAL_APPROVAL_APPLICANT_ID]);
    }
    sort($d);
    return $d;
}

function _ccgn_all_legal_approval_form_declined_applicant_ids () {
    $a = ccgn_new_legal_approvals_declined_since (false, false);
    $d = array();
    foreach ($a as $b) {
        $d[] = intval($b[CCGN_GF_FINAL_APPROVAL_APPLICANT_ID]);
    }
    sort($d);
    return $d;
}

function _ccgn_approval_process_consistent () {
    $subscribers = _ccgn_all_subscriber_ids ();
    $no_role = _ccgn_all_no_role_ids ();
    $approved = _ccgn_all_final_approval_form_approved_applicant_ids ();
    $declined = _ccgn_all_final_approval_form_declined_applicant_ids ();
    $legal_approved = _ccgn_all_legal_approval_form_approved_applicant_ids ();
    $legal_declined = _ccgn_all_legal_approval_form_declined_applicant_ids ();
    $individuals = ccgn_members_individual_ids ();
    $institutions = ccgn_members_institutional_ids ();
    $individual_apps = ccgn_applicant_ids_of_type (
        CCGN_APPLICATION_INDIVIDUAL
    );
    $institutional_apps = ccgn_applicant_ids_of_type (
        CCGN_APPLICATION_INSTITUTIONAL
    );
    echo "All of the following should be empty lists.\n";
    echo "If not, something is inconsistent in the application process.\n";
    echo "Approved applicants with no role: "
        . implode( ', ', array_intersect ( $approved, $no_role ) )
        . "\n";
    echo "Approved applicants who are not subscribers: "
        . implode( ', ', array_diff ( $approved, $subscribers ) )
        . "\n";
    echo "Declined applicants who are subscribers: "
        . implode( ', ', array_intersect ( $declined, $subscribers ) )
        . "\n";
    echo "Declined applicants who do not have no role: "
        . implode( ', ', array_diff ( $declined, $no_role ) )
        . "\n";
    echo "Applicants who are both approved and declined: "
        . implode( ', ', array_intersect ( $approved, $declined ) )
        . "\n";
    echo "Approved individual applicants who are not subscribers: "
        . implode(
            ', ',
            array_diff(
                array_intersect (
                    $individual_apps,
                    $approved
                ),
                $subscribers
            )
        )
        . "\n";
    echo "Approved individual applicants who are not individual members: "
        . implode(
            ', ',
            array_diff(
                $individuals,
                array_intersect (
                    $individual_apps,
                    $approved
                )
            )
        )
        . "\n";
    echo "Legal approved institutions with no role: "
        . implode( ', ', array_intersect ( $legal_approved, $no_role ) )
        . "\n";
    echo "Legal approved institutions who are not subscribers: "
        . implode( ', ', array_diff ( $legal_approved, $subscribers ) )
        . "\n";
    echo "Legal declined institutions who are subscribers: "
        . implode( ', ', array_intersect ( $legal_declined, $subscribers ) )
        . "\n";
    echo "Legal declined institutions who do not have no role: "
        . implode( ', ', array_diff ( $legal_declined, $no_role ) )
        . "\n";
    echo "Legal approved institutions that were not final approved: "
        . implode( ', ', array_diff ( $legal_approved, $approved ) )
        . "\n";
    echo "Institutions who are both approved and declined: "
        . implode( ', ', array_intersect ( $legal_approved, $legal_declined ) )
        . "\n";
    echo "Legal approved institutions who are not institutional members: "
        . implode( ', ', array_diff ( $legal_approved, $institutions ) )
        . "\n";
    echo "Legal approved institutional applicants who are not institutional members: "
        . implode(
            ', ',
            array_diff(
                $institutions,
                array_intersect (
                    $institutional_apps,
                    $approved
                )
            )
        )
        . "\n";
    echo "Legal declined institutions who are institutional members: "
        . implode( ', ', array_intersect ( $legal_declined, $institutions ) )
        . "\n";
    echo "Members who are both individual and institutional: "
        . implode( ', ', array_intersect ( $individuals, $institutions ) )
        . "\n";
}

////////////////////////////////////////////////////////////////////////////////
// Actual operation
////////////////////////////////////////////////////////////////////////////////

function ccgn_final_approval_status_for_vouch_counts( $counts ) {
    $yes = $counts['yes'];
    $no = $counts['no'];
    if ( ( $no == 0 )
         && ($yes >= CCGN_NUMBER_OF_VOUCHES_NEEDED ) ) {
        $status = 'Vouched';
    } elseif ( $no > 0 ) {
        $status = 'Declined';
    } else {
        $status = 'Vouching';
    }
    return $status;
}

function ccgn_final_approval_status_for_vote_counts(
    $user_id,
    $vote_counts,
    $vouch_counts
) {
    if ( ccgn_application_on_hold ( $user_id ) ) {
        $status = 'On Hold';
    } else {
        $yes = $vote_counts['yes'];
        $no = $vote_counts['no'];
        if ( ( $no == 0 )
             && ($yes >= CCGN_NUMBER_OF_VOTES_NEEDED ) ) {
            $status = 'Approved';
        } elseif ( $no > CCGN_NUMBER_OF_VOTES_AGAINST_ALLOWED ) {
            $status = '<b><i>Voted Against</i></b>';
        } elseif ( $vouch_counts[ 'yes' ] >= CCGN_NUMBER_OF_VOUCHES_NEEDED ) {
            $status = 'Voting';
        } else {
            $status = 'Unvouched';
        }
    }
    return $status;
}

function ccgn_final_applications_cmp ($a, $b) {
    //FIXME: This is very inefficient
    return strtotime( ccgn_application_vouchers($a)[ 'date_created' ] )
        > strtotime( ccgn_application_vouchers($b)[ 'date_created' ] );
}

function ccgn_list_applications_for_final_approval () {
    $user_entries = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    usort($user_entries, "ccgn_final_applications_cmp");
    foreach ($user_entries as $user_id) {
        $user = get_user_by('ID', $user_id);
        // The last form the user filled out, so the time to use
        $vouchers_entry = ccgn_application_vouchers($user_id);
        // The actual count of vouches
        $vouch_counts = ccgn_application_vouches_counts( $user_id );
        // If the user is not a Final Approver,
        // and the applicant does not have enough positive votes,
        // or they have enough Vouches against that they must be rejected
        // do not show.
        // The Final Approver needs to see applicants who have been Vouched
        // against, or whose applications have stalled, in order to handle
        // those cases.
        if( ( ( $vouch_counts['no'] > CCGN_NUMBER_OF_VOUCHES_AGAINST_ALLOWED )
              || ( $vouch_counts['yes'] < CCGN_NUMBER_OF_VOUCHES_NEEDED) )
            && ( ! ccgn_current_user_is_final_approver() ) ) {
            continue;
        }
        // If the user has been asked to Vouch for the applicant and they
        // are not the Final Approver, they should not see the entry as they
        // cannot Vote for them.
        // Final Approvers cannot vote either, but they must be able to see
        // the user.
        if ( ccgn_vouching_request_exists( $user_id, get_current_user_id() )
             && ( ! ccgn_current_user_is_final_approver() ) ) {
            continue;
        }
        $vouch_no_style = '';
        if ( $vouch_counts[ 'no' ] > 0 ) {
            $vouch_no_style = ' style="font-weight: bold"';
        }
        $vote_counts = ccgn_application_votes_counts( $user_id, $vouch_counts );
        if ($vote_counts[ 'no' ] > 0) {
            $vote_no_style = ' style="font-weight: bold"';
        }
        echo '<tr';
        if ( ccgn_user_is_institutional_applicant ( $user_id ) ) {
            echo ' style="background: #FFCCE5;"';
        }
        echo '><td><a href="'
            . ccgn_application_user_application_page_url( $user_id )
            . '">'
            . ccgn_applicant_display_name ( $user_id )
            . '</a></td><td>'
            . ccgn_applicant_type_desc( $user_id )
            . '</td><td>';
        if ( ccgn_current_user_is_final_approver() ) {
            echo '<a href="mailto:'
                . $user->user_email . '">' . $user->user_email
                . '</a></td><td>'
                . ccgn_final_approval_status_for_vouch_counts( $vouch_counts )
                . '</td><td>'
                . $vouch_counts[ 'cannot' ]
                . '</td><td>'
                . $vouch_counts[ 'yes' ]
                . '</td><td' . $vouch_no_style . '>'
                . $vouch_counts[ 'no' ]
                . '</td><td>';
        }
        echo ccgn_final_approval_status_for_vote_counts(
            $user_id,
            $vote_counts,
            $vouch_counts
        )
            . '</td><td>'
            . $vote_counts[ 'yes']
            . '</td><td' . $vote_no_style . '>'
            . $vote_counts[ 'no' ]
            . '</td><td>'
            . $vouchers_entry[ 'date_created' ]
            .'</td></tr>';
    }
}

function ccgn_application_approval_page () {
    ?>
<h1>Applications for Approval</h1>
<?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'applications-approval';
?>
<h2 class="nav-tab-wrapper">
    <a href="?page=global-network-application-approval&tab=applications-approval" class="nav-tab <?php echo $active_tab == 'applications-approval' ? 'nav-tab-active' : ''; ?>">Applications for Approval</a>
    <a href="?page=global-network-application-approval&tab=mc-voting-count" class="nav-tab <?php echo $active_tab == 'mc-voting-count' ? 'nav-tab-active' : ''; ?>">MC Voting count</a>
</h2>
<br>
<?php if ($active_tab == 'applications-approval') : ?>
<div class="custom-filters">
    <div class="member-type">
        <h4 class="filter-title">Filters</h4>
        <label for="member_type">
            Member type
            <select name="member_type" id="member_type">
                <option value="">Both</option>
                <option value="Individual">Individual</option>
                <option value="Institution">Institution</option>
            </select>
        </label>   
    </div>
</div>
<div class="color-guide">
    <ul class="colors">
        <li><span class="color green"></span> Already voted</li>
        <li><span class="color orange"></span> Asked for clarification</li>
        <li><span class="color blue"></span> Statement updated</li>
    </ul>
</div>
<div class="ccgn-table-container">
    <table class="ccgn-approval-table" id="ccgn-table-applications-approval">
    <thead>
        <tr>
            <th></th>  
        <th>Applicant</th>
        <th>Type</th>
    <?php// if ( ccgn_current_user_is_final_approver() ) { ?>
        <th>Votes</th>
        <th>Vouching Status</th>
        <!-- <th>Vouches Declined</th>
        <th>Vouches For</th>
        <th>Vouches Against</th> -->
    <?php // } ?>
        <th>Voting Status</th>
        <!-- <th>Votes For</th>
        <th>Votes Against</th> -->
        <th>Application date</th>
        </tr>
    </thead>
    
        <?php //ccgn_list_applications_for_final_approval(); ?>
    
    </table>
</div>
<p>This is the list of applicants currently being Vouched by existing
members and voted on by the Membership Council.</p>
<p>Applicants need <b><?php echo CCGN_NUMBER_OF_VOUCHES_NEEDED; ?></b>
vouches for them and <b>zero</b> against them.</p>
<p>You can review the guidelines for reviewing applications here: <a href="https://github.com/creativecommons/global-network-strategy/blob/master/docs/Guide_for_approve_new_members.md">https://github.com/creativecommons/global-network-strategy/blob/master/docs/Guide_for_approve_new_members.md</a>.</p>
<!-- move to stylesheet and queue -->
<?php endif; ?>
<?php if ($active_tab == 'mc-voting-count') : ?>
<h2>MC voting stats</h2>
<div class="ccgn-table-container">
    <table id="ccgn-list-mc-voting" class="tablesorter">
    <thead align="left">
        <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Voting Yes</th>
        <th>Voting No</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
</div>
<?php endif; ?>
<style>
.ccgn-approval-table {
    border-collapse: collapse;
    border-spacing: 10px 20px;
    text-align: left;
}
.ccgn-approval-table tr {
  border: solid;
  border-width: 1px 0;
}
.ccgn-approval-table td, th {
    padding: 8px 16px;
}
</style>
    <?php
}

function ccgn_application_final_approval_menu () {
    add_menu_page(
        'Global Network',
        'Global Network',
        'ccgn_list_applications',
        'global-network-application-approval',
        'ccgn_application_approval_page',
        'dashicons-id-alt'
    );
    // And as the first submenu item, with a more descriptive name
    add_submenu_page(
        'global-network-application-approval',
        'Application Approval',
        'Application Approval',
        'ccgn_list_applications',
        'global-network-application-approval',
        'ccgn_application_approval_page'
    );
}

/**
 * Register endpoints to use data
*/
register_commoners_endpoints('/application-approval/list','ccgn_rest_return_application_approval_list', 'POST');

function ccgn_rest_return_application_approval_list() {
    $current_user = ( isset( $_POST['current_user'] ) ) ? esc_attr($_POST['current_user']) : 0;
    $the_user = new WP_USER($current_user);
    $user_voting = ccgn_application_votes_by_user($the_user->data->ID);
    $voted_users = array();
    foreach ($user_voting as $vote) {
        $voted_users[] = $vote[4];
    }
    if ( rest_cookie_check_errors() && $the_user->has_cap('ccgn_list_applications') ) {
        $user_entries = ccgn_applicant_ids_with_state(
            CCGN_APPLICATION_STATE_VOUCHING
        );
        $return_data = array();
        usort($user_entries, "ccgn_final_applications_cmp");
        foreach ($user_entries as $user_id) {
            $user_data = array();
            $user = get_user_by('ID', $user_id);
            $user_application_status = get_user_meta($user_id, 'ccgn-application-state', true);
            $log_user = ccgn_ask_clarification_log_get_id($user_id);
            $user_is_asked_for_clarification = 0;
            $voucher_asked = null;
            foreach ($log_user as $entry) {
                $asked_meta = get_user_meta($entry['voucher_id'], 'ccgn_need_to_clarify_vouch_reason_applicant_status', true);
                if ( ($asked_meta['status'] == 1) && ($asked_meta['applicant_id'] == $user_id) ) {
                    $user_is_asked_for_clarification = 1;
                    $asked_voucher_user = get_user_by('ID', $entry['voucher_id'])->display_name;
                } else if (($asked_meta['status'] == 0) && ($asked_meta['applicant_id'] == $user_id)) {
                    $user_is_asked_for_clarification = 2;
                    $asked_voucher_user = get_user_by('ID', $entry['voucher_id'])->display_name;
                }
            }
            // The last form the user filled out, so the time to use
            $vouchers_entry = ccgn_application_vouchers($user_id);
            // The actual count of vouches
            $vouch_counts = ccgn_application_vouches_counts( $user_id );
            // If the user is not a Final Approver,
            // and the applicant does not have enough positive votes,
            // or they have enough Vouches against that they must be rejected
            // do not show.
            // The Final Approver needs to see applicants who have been Vouched
            // against, or whose applications have stalled, in order to handle
            // those cases.

            if( ( ( $vouch_counts['no'] > CCGN_NUMBER_OF_VOUCHES_AGAINST_ALLOWED )
                || ( $vouch_counts['yes'] < CCGN_NUMBER_OF_VOUCHES_NEEDED) )
                && ( ! ccgn_current_user_is_final_approver() ) ) {
                continue;
            }
            // If the user has been asked to Vouch for the applicant and they
            // are not the Final Approver, they should not see the entry as they
            // cannot Vote for them.
            // Final Approvers cannot vote either, but they must be able to see
            // the user.
            if ( ccgn_vouching_request_exists( $user_id, get_current_user_id() )
                && ( ! ccgn_current_user_is_final_approver() ) ) {
                continue;
            }
            $vote_counts = ccgn_application_votes_counts( $user_id, $vouch_counts );
            $user_data['applicant_id'] = $user_id;
            $user_data['applicant'] = ccgn_applicant_display_name ( $user_id );
            $user_data['applicant_status'] = $user_application_status;
            $user_data['applicant_url'] = ccgn_application_user_application_page_url( $user_id );
            $user_data['applicant_type'] = ccgn_applicant_type_desc( $user_id );
            $user_data['user_mail'] = $user->user_email;
            $user_data['already_voted_by_me'] = (in_array($user_id,$voted_users)) ? 'yes' : 'no';
            $user_data['vouching_status'] = ccgn_final_approval_status_for_vouch_counts( $vouch_counts );
            $user_data['vouches_declined'] = $vouch_counts[ 'cannot' ];
            $user_data['vouches_for'] = $vouch_counts[ 'yes' ];
            $user_data['vouches_against'] = $vouch_counts[ 'no' ];
            $user_data['voting_status'] = ccgn_final_approval_status_for_vote_counts( $user_id, $vote_counts, $vouch_counts );
            $user_data['votes_for'] = $vote_counts[ 'yes'];
            $user_data['votes_against'] = $vote_counts[ 'no'];
            $user_data['application_date'] = date('Y-m-d', strtotime($vouchers_entry[ 'date_created' ]));
            $user_data['is_asked'] = $user_is_asked_for_clarification;
            $user_data['who_is_asked'] = $asked_voucher_user;

            $return_data['data'][] = $user_data;
        }

        return $return_data;
    } else {
        return new WP_Error('Forbidden', "You don't have access to request this data" , array('status' => 403));
    }
}
register_commoners_endpoints('/mc-voting/list', 'ccgn_rest_return_mc_voting_list', 'POST');

function ccgn_rest_return_mc_voting_list()
{
    $current_user = (isset($_POST['current_user'])) ? esc_attr($_POST['current_user']) : 0;
    $the_user = new WP_USER($current_user);
    $start_date = (isset($_POST['start_date'])) ? $start_date : '';
    $end_date = (isset($_POST['end_date'])) ? $end_date : '';
    $return_data = array();
    if (rest_cookie_check_errors() && $the_user->has_cap('ccgn_list_applications')) {
        $users_mc = get_users(array('role' => 'membership-council-member'));
        foreach ($users_mc as $user) {
            $user_data = array();
            $report = ccgn_application_votes_by_user($user->data->ID);
            $user_data['user_id'] = $user->data->ID;
            $user_data['user_name'] = $user->data->display_name;
            $user_data['user_email'] = $user->data->user_email;
            $user_data['voting_yes'] = 0;
            $user_data['voting_no'] = 0;
            foreach ($report as $voting) {
                if ($voting[2] == 'Yes') {
                    $user_data['voting_yes']++;
                }
                if ($voting[2] == 'No') {
                    $user_data['voting_no']++;
                }
            }
            $return_data['data'][] = $user_data;
        }
        return $return_data;

    } else {
        return new WP_Error('Forbidden', "You don't have access to request this data", array('status' => 403));
    }
}