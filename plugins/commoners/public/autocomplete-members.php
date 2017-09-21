<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Function to return matching usernames in response to an AJAX POST request.
// To call:
/*
  var data = {'q': 'aaa'};
  data.action = 'autocomplete_members'
  $.post('/wp-admin/admin-ajax.php', data, function(response) {
        somecallback(response);
    });
 */
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Configuration
////////////////////////////////////////////////////////////////////////////////

// The number of characters the user has to supply before we start matching

define( 'COMMONERS_AUTOCOMPLETE_MIN_CHARACTERS', 3);

// The maximum number of matches we return

define( 'COMMONERS_AUTOCOMPLETE_MAX_RESPONSES', 100);

////////////////////////////////////////////////////////////////////////////////
// Service AJAX POST request
////////////////////////////////////////////////////////////////////////////////

function commoners_ajax_autocomplete_members () {
    global $wpdb;

    // Check security
    /*$nonce = $_POST[ '_wpnonce_name' ];
      if ( empty( $_POST )
      || ! wp_verify_nonce( $nonce, 'my-nonce' ) ) {
      die('Security check');
    }*/

    // Make sure the argument was provided
    if ( ! isset( $_POST[ 'q' ] ) ) {
        return false;
    }

    $to_match = $_POST[ 'q' ];

    // Clean the string to match, erroring if we can't

    $to_match = filter_var( $to_match, FILTER_SANITIZE_STRING );
    if ( !  $to_match ) {
        http_response_code( 400 );
        wp_die();
    }

    // Nothing or not enough to match? Return empty JSON array
    $to_match = trim( $to_match );
    if ( ( $to_match == '' )
         || ( strlen( $to_match ) < COMMONERS_AUTOCOMPLETE_MIN_CHARACTERS ) ) {
        $names = [];
    } else {
        // Format match string as SQL LIKE string
        $to_match = "%$to_match%";

        // Query the database for username matches. Note exclusion of admin.

        $table_name = $wpdb->prefix . 'users';
        $names = $wpdb->get_col(
            $wpdb->prepare(
                "
                 SELECT           user_nicename
                 FROM             $table_name
                 WHERE            user_nicename LIKE %s
                 ORDER BY         user_nicename
                 DESC
                 LIMIT            %d
                ",
                $to_match,
                COMMONERS_AUTOCOMPLETE_MAX_RESPONSES
            )
        );
    }

    // Return the username matches as a JSON array.

    wp_send_json( $names );
}
