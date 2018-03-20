<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ccgn_list_vouchers_render_highest ( $cutoff ) {
?>
    <table><thead align="left"><th>Voucher</th><th>Open Vouch Requests</th></thead><tbody>
<?php
    $vouchee_counts = ccgn_members_with_most_open_vouch_requests ();
    arsort( $vouchee_counts );
    foreach ( $vouchee_counts as $voucher_id => $open_requests ) {
        $num_open_requests = count( $open_requests );
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
