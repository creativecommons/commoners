<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

///////////////////////////////////////////////////////////////////////////////

function ccgn_registration_email_log_html () {
    $log = ccgn_registration_email_log_get();
    $dates = array_keys( $log );
    sort( $dates );
    $dates = array_reverse( $dates );
    foreach ( $dates as $date ) {
        echo '<h3>' . $date . '</h3>';
        $emails = $log[ $date ];
?>
                <table>
          <thead>
            <tr>
              <td><h4>Address</h4></td>
              <td><h4>Status</h4></td>
              <td><h4>Subject</h4></td>
              <td><h4>Option</h4></td>
            </tr>
          </thead>
          <tbody>
<?php
        $types = array_keys($emails);
        sort( $types );
        foreach ( $types as $type ) {
            $type_subject = get_option( $type )[ 'subject' ];
            foreach ( $emails[ $type ] as $send ) {
                echo '<tr><td style="padding-right: 32px;">'
                    . $send[ 'address' ]
                    . '</td><td style="padding-right: 32px;">'
                    . ($send[ 'status' ] ? 'Sent' : '<b>Not Sent</b>')
                    . '</td><td style="padding-right: 32px;">'
                    . $type_subject
                    . '</td><td style="padding-right: 32px;">'
                    . $type
                    . '</td></tr>';
            }
        }
?>
          </tbody>
        </table>
<?php
    }
}


function ccgn_application_email_log_page () {
    ?>
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<?php
    ccgn_registration_email_log_html();
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

// This shouldn't really have "application" in it but it would look out of place

function ccgn_application_email_log_menu () {
    add_submenu_page(
        'global-network-emails',
        'Email Log',
        'Email Log',
        'ccgn_pre_approve',
        'global-network-email-log',
        'ccgn_application_email_log_page'
    );
}
