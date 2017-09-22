<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function commoners_list_applications_for_pre_approval () {
    $user_entries = commoners_applicants_with_state(
        COMMONERS_APPLICATION_STATE_RECEIVED
    );
    foreach ($user_entries as $user_entry) {
        $user_id = $user_entry->ID;
        $user = get_user_by('ID', $user_id);
        // The last form the user filled out, so the time to use
        $vouchers_entry = commoners_application_vouchers($user_id);
        // The user entered a name here
        $details_entry = commoners_application_details($user_id);
        echo '<tr><td><a href="'
            . commoners_application_user_application_page_url( $user_id )
            . '">'
            . $user->user_nicename
            . '</a></td><td>'
            . rgar( $vouchers_entry, 'date_created' )
            .'</td></tr>';
    }
}

function commoners_application_pre_approval_page () {
    ?>
<h1>Applicants for Pre Approval</h1>
<table class="commoners-approval-table">
  <thead>
    <tr><th>User</th>
      <th>Application date</th>
    </tr>
  </thead>
  <tbody>
    <?php commoners_list_applications_for_pre_approval(); ?>
  </tbody>
</table>
<p>This is the list of new applicants. They have not yet been sent to the
Vouching stage.</p>
<p>If you are part of the application review team, please review
their profile pages by clicking on the link to their username.</p>
<!-- move to stylesheet and queue -->
<style>
.commoners-approval-table {
    border-collapse: collapse;
    border-spacing: 10px 20px;
    text-align: left;
}
.commoners-approval-table tr {
  border: solid;
  border-width: 1px 0;
}
.commoners-approval-table td, th {
    padding: 8px 16px;
}
</style>
    <?php
}

function commoners_application_pre_approval_menu () {
    add_users_page(
        'Global Network Pre-Approval',
        'Global Network Pre-Approval',
        'edit_users',
        'commoners-global-network-pre-approval',
        'commoners_application_pre_approval_page'
    );
}
