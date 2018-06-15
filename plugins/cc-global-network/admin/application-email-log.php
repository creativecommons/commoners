<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

///////////////////////////////////////////////////////////////////////////////
// Show email logs
// These are not all for registration (e.g. chapter contact emails)
// The name is strongly associated with these pages though,
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
              <td><h4>To</h4></td>
              <td><h4>Status</h4></td>
              <td><h4>Option</h4></td>
              <td><h4>Subject</h4></td>
              <td><h4>Content</h4></td>
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
                    . (is_array( $send[ 'address' ] )
                       ? implode( ', ', $send[ 'address' ] )
                       : $send[ 'address' ])
                    . '</td><td style="padding-right: 32px;">'
                    . ($send[ 'status' ] ? 'Sent' : '<b>Not Sent</b>')
                    . '</td><td style="padding-right: 32px;">'
                    . $type
                    . '</td><td style="padding-right: 32px;">'
                    . ($send[ 'subject' ] ? $send[ 'subject' ] : $type_subject)
                    . '</td><td style="padding-right: 32px;">'
                    . ($send[ 'body' ] ? $send[ 'body' ] : '')
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
        'global-network-application-approval',
        'Email Log',
        'Email Log',
        'ccgn_pre_approve',
        'global-network-email-log',
        'ccgn_application_email_log_page'
    );
}
