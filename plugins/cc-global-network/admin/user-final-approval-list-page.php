<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ccgn_final_approval_status_for_vouch_counts( $counts ) {
    $yes = $counts['yes'];
    $no = $counts['no'];
    if ( ( $no == 0 )
         && ($yes >= CCGN_NUMBER_OF_VOUCHES_NEEDED ) ) {
        $status = 'Approved';
    } elseif ( $no > 0 ) {
        $status = 'Declined';
    } else {
        $status = 'Vouching';
    }
    return $status;
}

function ccgn_list_applications_for_final_approval () {
    $user_entries = ccgn_applicants_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    foreach ($user_entries as $user_entry) {
        $user_id = $user_entry->ID;
        $user = get_user_by('ID', $user_id);
        // The last form the user filled out, so the time to use
        $vouchers_entry = ccgn_application_vouchers($user_id);
        // The user entered a name here
        $details_entry = ccgn_application_details($user_id);
        // The actual count of vouches
        $vouch_counts = ccgn_application_vouches_counts( $user_id );
        if ($vouch_counts['no'] > 0) {
            $no_style = 'font-weight: bold';
        }
        echo '<tr><td><a href="'
            . ccgn_application_user_application_page_url( $user_id )
            . '">'
            . $user->user_nicename
            . '</a></td><td>'
            . ccgn_applicant_type_desc( $user_id )
            . '</td><td>'
            . ccgn_final_approval_status_for_vouch_counts( $counts )
            . '</td><td>'
            . $vouch_counts['yes']
            . '</td><td style="' . $no_style . '">'
            . $vouch_counts['no']
            . '</td><td>'
            . $vouchers_entry[ 'date_created' ]
            .'</td></tr>';
    }
}

function ccgn_application_final_approval_page () {
    ?>
<h1>Applicants for Final Approval</h1>
<table class="ccgn-approval-table">
  <thead>
    <tr>
      <th>User</th>
      <th>Type</th>
      <th>Vouching Status</th>
      <th>Vouches For</th>
      <th>Vouches Against</th>
      <th>Application date</th>
    </tr>
  </thead>
  <tbody>
    <?php ccgn_list_applications_for_final_approval(); ?>
  </tbody>
</table>
<p>This is the list of applicants currently being Vouched by existing
members.</p>
<p>Applicants need <b><?php echo CCGN_NUMBER_OF_VOUCHES_NEEDED; ?></b>
vouches for them and <b>zero</b> against them in order for you to approve
them.</p>
<p>If you are part of the application review team, once they have enough
vouches (or if their application should be refused for some reason), you should
review their profile page by clicking on the link to their username.</p>
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
    add_users_page(
        'Global Network Final Approval',
        'Global Network Final Approval',
        'edit_users',
        'global-network-final-approval',
        'ccgn_application_final_approval_page'
    );
}
