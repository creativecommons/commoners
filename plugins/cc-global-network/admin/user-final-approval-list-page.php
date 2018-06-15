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
    $a = ccgn_new_legal_approvals_since (false, false);
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
    echo "All of the following should be empty lists.\n";
    echo "If not, something is inconsistent in the application process.\n";
    echo "Approved members with no role: "
        . implode( ', ', array_intersect ( $approved, $no_role ) )
        . "\n";
    echo "Approved members who are not subscribers: "
        . implode( ', ', array_diff ( $approved, $subscribers ) )
        . "\n";
    echo "Declined members who are subscribers: "
        . implode( ', ', array_intersect ( $declined, $subscribers ) )
        . "\n";
    echo "Declined members who do not have no role: "
        . implode( ', ', array_diff ( $declined, $no_role ) )
        . "\n";
    echo "Members who are both approved and declined: "
        . implode( ', ', array_intersect ( $approved, $declined ) )
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
    echo "Legal approved institutions that were not previously approved: "
        . implode( ', ', array_diff ( $legal_approved, $approved ) )
        . "\n";
    echo "Institutions who are both approved and declined: "
        . implode( ', ', array_intersect ( $legal_approved, $legal_declined ) )
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
        } elseif ( $no > 0 ) {
            $status = 'Declined';
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
    return strtotime( ccgn_application_vouchers($a->ID)[ 'date_created' ] )
        > strtotime( ccgn_application_vouchers($b->ID)[ 'date_created' ] );
}

function ccgn_list_applications_for_final_approval () {
    $user_entries = ccgn_applicants_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    usort($user_entries, "ccgn_final_applications_cmp");
    foreach ($user_entries as $user_entry) {
        $user_id = $user_entry->ID;
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
<table class="ccgn-approval-table">
  <thead>
    <tr>
      <th>Applicant</th>
      <th>Type</th>
<?php if ( ccgn_current_user_is_final_approver() ) { ?>
      <th>Email</th>
      <th>Vouching Status</th>
      <th>Vouches Declined</th>
      <th>Vouches For</th>
      <th>Vouches Against</th>
<?php } ?>
      <th>Voting Status</th>
      <th>Votes For</th>
      <th>Votes Against</th>
      <th>Application date</th>
    </tr>
  </thead>
  <tbody>
    <?php ccgn_list_applications_for_final_approval(); ?>
  </tbody>
</table>
<p>This is the list of applicants currently being Vouched by existing
members and voted on by the Membership Council.</p>
<p>Applicants need <b><?php echo CCGN_NUMBER_OF_VOUCHES_NEEDED; ?></b>
vouches for them and <b>zero</b> against them.</p>
<p>You can review the guidelines for reviewing applications here: <a href="https://github.com/creativecommons/global-network-strategy/blob/master/docs/Guide_for_approve_new_members.md">https://github.com/creativecommons/global-network-strategy/blob/master/docs/Guide_for_approve_new_members.md</a>.</p>
<!-- move to stylesheet and queue -->
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
