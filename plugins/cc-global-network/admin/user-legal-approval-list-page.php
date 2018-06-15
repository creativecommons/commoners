<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ccgn_legal_applications_cmp ($a, $b) {
    //FIXME: This is very inefficient
    return strtotime( ccgn_application_vouchers($a->ID)[ 'date_created' ] )
        > strtotime( ccgn_application_vouchers($b->ID)[ 'date_created' ] );
}

function ccgn_list_applications_for_legal_approval () {
    $user_entries = ccgn_applicant_ids_with_state(
        CCGN_APPLICATION_STATE_LEGAL
    );
    usort($user_entries, "ccgn_legal_applications_cmp");
    foreach ($user_entries as $user_id) {
        $user = get_user_by('ID', $user_id);
        // The last form the user filled out, so the time to use
        $vouchers_entry = ccgn_application_vouchers($user_id);
        echo '<tr style="background: #FFCCE5;"><td><a href="'
            . ccgn_application_user_application_page_url( $user_id )
            . '">'
            . ccgn_applicant_display_name ( $user_id )
            . '</a></td><td>'
            . ccgn_applicant_type_desc( $user_id )
            . '</td><td>'
            . $vouchers_entry[ 'date_created' ]
            .'</td></tr>';
    }
}

function ccgn_application_legal_approval_page () {
    ?>
<h1>Applicantions for Legal Approval</h1>
<table class="ccgn-approval-table">
  <thead>
    <tr>
      <th>Applicant</th>
      <th>Type</th>
      <th>Application date</th>
    </tr>
  </thead>
  <tbody>
    <?php ccgn_list_applications_for_legal_approval(); ?>
  </tbody>
</table>
    <p>This is the list of institutional applicants who have been Vouched, Voted on and given Final Approval, ready for legal to contact and finalize.</p>
<p>If you are part of the CC legal team, please review
their profile pages by clicking on the link to their username.</p>
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

function ccgn_application_legal_approval_menu () {
    add_submenu_page(
        'global-network-application-approval',
        'Legal Approval',
        'Legal Approval',
        'ccgn_list_applications_legal',
        'global-network-legal-approval',
        'ccgn_application_legal_approval_page'
    );
}
