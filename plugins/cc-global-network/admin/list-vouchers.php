<?php

// COMPUTATIONALLY EXPENSIVE

function ccgn_members_with_most_open_vouch_requests () {
    $open_requests = array();
    // Get applicants in the vouching state
    $applicants = ccgn_applicants_with_state(
        CCGN_APPLICATION_STATE_VOUCHING
    );
    // Get vouch requests for each
    foreach ( $applicants as $applicant ) {
        $applicant_id = $applicant->ID;
        $vouchers = ccgn_application_vouchers_users_ids ( $applicant_id );
        foreach ( $vouchers as $voucher_id ) {
            $vouches = ccgn_vouches_for_applicant_by_voucher (
                $applicant_id,
                $voucher_id
            );
            // Check for existence of vouches
            if ( $vouches == [] ) {
                // No vouch? increment or start the count
                if ( isset( $open_requests[ $voucher_id ] ) ) {
                    $open_requests[ $voucher_id ]
                        = $open_requests[ $voucher_id ] + 1;
                } else {
                    $open_requests[ $voucher_id ] = 1;
                }
            }
        }
    }
    return $open_requests;
}

function ccgn_list_vouchers_render_highest ( $cutoff ) {
?>
    <table><thead align="left"><th>Voucher</th><th>Open Vouch Requests</th></thead><tbody>
<?php
    $vouchee_counts = ccgn_members_with_most_open_vouch_requests ();
    arsort( $vouchee_counts );
    foreach ( $vouchee_counts as $voucher_id => $num_open_requests ) {
        if ( $num_open_requests >= $cutoff ) {
            $user = get_user_by( 'ID', $voucher_id );
?>
    <tr><td><?php echo bp_core_get_userlink( $voucher_id ); ?></td>
    <td><?php echo $num_open_requests; ?></td></tr>
<?php
        }
    }
?>
    </tbody></table>
<?php
}

function ccgn_list_vouchers_admin_page () {
    $cutoff = '';
    if ( isset( $_GET['cutoff'] ) ) {
        $cutoff = filter_var(
            $_GET[ 'cutoff' ],
            // May allow negative number
            FILTER_SANITIZE_NUMBER_INT
        );
        // So catch bad values here
        if ( $cutoff < 1 ) {
            $cutoff = 1;
        }
    } else {
        $cutoff = 2;
    }
    ?>
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <?php ccgn_list_vouchers_render_highest ( $cutoff ); ?>
    <br />
    <form method="get" action="<?php
     echo esc_html( admin_url( 'admin.php?page=global-network-list-vouchers' ) );
?>">
    <input type="hidden" name="page" value="global-network-list-vouchers" />
        <label>Minimum Number of Un-handled Vouches to Show</label>
        <br />
        <input type="number" name="cutoff" id="ccgn-list-vouchers-cutoff"
          value="<?php echo $cutoff; ?>" placeholder="2" min="1"/>
        <br />
<?php
    submit_button('List');
?>
  </form>
<?php
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

// This shouldn't really have "application" in it but it would look out of place

function ccgn_application_list_vouchers_menu () {
    add_submenu_page(
        'global-network-application-approval',
        'List Vouchers',
        'List Vouchers',
        'ccgn_pre_approve',
        'global-network-list-vouchers',
        'ccgn_list_vouchers_admin_page'
    );
}
