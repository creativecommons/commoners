<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ccgn_pre_applications_cmp ($a, $b) {
    //FIXME: This is very inefficient
    return strtotime( ccgn_application_vouchers($a)[ 'date_created' ] )
        > strtotime( ccgn_application_vouchers($b)[ 'date_created' ] );
}

function ccgn_list_applications_for_pre_approval () {
    $user_entries = array_merge(
        ccgn_applicant_ids_with_state(
            CCGN_APPLICATION_STATE_RECEIVED
        ),
        ccgn_applicant_ids_with_state(
            CCGN_APPLICATION_STATE_UPDATE_DETAILS
        )
    );
    usort($user_entries, "ccgn_pre_applications_cmp");
    foreach ($user_entries as $user_id) {
        $user = get_user_by('ID', $user_id);
        $applicant_state = ccgn_registration_user_get_stage_and_date(
            $user_id
        );
        $status = get_user_meta($user_id, 'ccgn_applicant_update_details_state', true);
        $reminders = 'none';
        if (!empty($status)) {
            $reminders = $status['state'].' (<small>'.$status['date'].'</small>)';
        }
        // The last form the user filled out, so the time to use
        $vouchers_entry = ccgn_application_vouchers( $user_id );
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
            . '</td><td>'
            . $vouchers_entry[ 'date_created' ]
            . '</td><td>'
            . $applicant_state[ 'stage' ]
            . '</td><td>'
            . $applicant_state[ 'date' ]
            . '</td><td>'
            . $reminders
            .'</td></tr>';
    }
}

function ccgn_application_spam_check_page () {
    ?>
<h1>Applications Spam Check</h1>
<div class="ccgn-table-container">
    <table class="ccgn-approval-table" id="ccgn-pre-approval-table">
    <thead>
        <tr>
        <th>Applicant</th>
        <th>Type</th>
        <th>Application date</th>
        <th>Application state</th>
        <th>Application state date</th>
        <th>Reminder</th>
        </tr>
    </thead>
    <tbody>
        <?php //ccgn_list_applications_for_pre_approval(); ?>
    </tbody>
    </table>
</div>
<p>This is the list of new applicants. They have not yet been sent to the
Vouching/Approval stage.</p>
<p>Please review their profile pages to ensure that the application is not spam before approving the application to move on to the Vouching/Approval stage by clicking on the link to the applicant&apos;s username.</p>
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

function ccgn_application_pre_approval_menu () {
    add_submenu_page(
        'global-network-application-approval',
        'Spam Check',
        'Spam Check',
        'ccgn_pre_approve',
        'global-network-application-spam-check',
        'ccgn_application_spam_check_page'
    );
}

/**
 * Register endpoints to use data
*/
register_commoners_endpoints('/application-approval/pre-approve','ccgn_rest_return_application_pre_approval_list', 'POST');

function ccgn_rest_return_application_pre_approval_list() {
    $current_user = ( isset( $_POST['current_user'] ) ) ? esc_attr($_POST['current_user']) : 0;
    $the_user = new WP_USER($current_user);
    $user_voting = ccgn_application_votes_by_user($the_user->data->ID);
    $voted_users = array();
    foreach ($user_voting as $vote) {
        $voted_users[] = $vote[4];
    }
    if ( rest_cookie_check_errors() && $the_user->has_cap('ccgn_list_applications') ) {
        $user_entries = array_merge(
            ccgn_applicant_ids_with_state(
                CCGN_APPLICATION_STATE_RECEIVED
            ),
            ccgn_applicant_ids_with_state(
                CCGN_APPLICATION_STATE_UPDATE_DETAILS
            )
        );
        usort($user_entries, "ccgn_pre_applications_cmp");
        foreach ($user_entries as $user_id) {
            $user = get_user_by('ID', $user_id);
            $applicant_state = ccgn_registration_user_get_stage_and_date(
                $user_id
            );
            $status = get_user_meta($user_id, 'ccgn_applicant_update_details_state', true);
            $reminders = 'none';
            if (!empty($status)) {
                $reminders = $status['state'].' (<small>'.$status['date'].'</small>)';
            }
            // The last form the user filled out, so the time to use
            $vouchers_entry = ccgn_application_vouchers( $user_id );
            $user_data['applicant_id'] = $user_id;
            $user_data['applicant_name'] = ccgn_applicant_display_name ( $user_id );
            $user_data['applicant_url'] = ccgn_application_user_application_page_url( $user_id ); 
            $user_data['applicant_type'] = ccgn_applicant_type_desc( $user_id );
            $user_data['applicant_stage'] = $applicant_state[ 'stage' ];
            $user_data['applicant_stage_updated'] = $applicant_state[ 'updated' ];
            $user_data['applicant_stage_date'] = $applicant_state[ 'date' ];
            $user_data['application_date'] = $vouchers_entry[ 'date_created' ];
            $user_data['applicant_reminders'] = $reminders;

            $return_data['data'][] = $user_data;
        }
        return $return_data;
    } else {
        return new WP_Error('Forbidden', "You don't have access to request this data" , array('status' => 403));
    }
}