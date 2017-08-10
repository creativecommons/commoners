<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Globals
////////////////////////////////////////////////////////////////////////////////

global $commoners_vouching_db_version;
$commoners_vouching_db_version = '1.0';

global $commoners_vouching_max_vouches;
$commoners_vouching_max_vouches = 6;

global $commoners_vouching_can_vouch_threshold;
$commoners_vouching_can_vouch_threshold = 3;

global $auto_voucher_id;
// BIGINT_MAX
$auto_voucher_id = 18446744073709551615;

global $auto_vouch_reason;
$auto_vouch_reason = 'Automatically vouched';

global $auto_vouch_username;
$auto_vouch_username = 'Automatic';

////////////////////////////////////////////////////////////////////////////////
// Database
////////////////////////////////////////////////////////////////////////////////

// Create the database (call when when the plugin is registered)

function commoners_vouching_create_database_table () {
   global $wpdb;
   global $commoners_vouching_db_version;
   $table_name = $wpdb->prefix . "commoners_vouches";

   $charset_collate = $wpdb->get_charset_collate();
   // Don't enforce unique vouchee/voucher here, because of autovouch
   // Check for duplicates in code
   $sql = "CREATE TABLE $table_name (
             id          bigint(20) unsigned NOT NULL AUTO_INCREMENT,
             autovouch   boolean DEFAULT 0 NOT NULL,
             description varchar(500) NOT NULL,
             date        datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
             vouchee     bigint(20) unsigned NOT NULL,
             voucher     bigint(20) unsigned NOT NULL,
             PRIMARY KEY (id),
             INDEX       vouchee_id (vouchee),
             INDEX       voucher_id (voucher)
           ) $charset_collate;
          ";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );

   add_option(
       'commoners_vouching_db_version',
       $commoners_vouching_db_version
   );
}

function commoners_vouching_by_voucher ($voucher) {
    global $wpdb;
    $table_name = $wpdb->prefix . "commoners_vouches";
    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_name
         WHERE  voucher=%d",
        $voucher
    ) );
}

function commoners_vouching_by_vouchee ($vouchee) {
    global $wpdb;
    $table_name = $wpdb->prefix . "commoners_vouches";
    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_name
         WHERE  vouchee=%d",
        $vouchee
    ) );
}

// Count vouches created on $vouched_for

function commoners_vouching_count_vouchee ($vouchee) {
    global $wpdb;
    $table_name = $wpdb->prefix . "commoners_vouches";
    return $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name
         WHERE  vouchee=%d",
        $vouchee
    ) );
}

// Has $vouched_for already received the maximum number of vouches?

function commoners_vouching_maxed ($vouched_for) {
    global $commoners_vouching_max_vouches;
    return commoners_vouching_count_vouchee( $vouched_for )
        >= $commoners_vouching_max_vouches;
}

// Has $vouchee already been vouched for by $voucher ?

function commoners_vouching_already ($vouchee, $voucher) {
    global $wpdb;
    $table_name = $wpdb->prefix . "commoners_vouches";
    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM $table_name
         WHERE  vouchee=%d
         AND    voucher=%d",
        $vouchee,
        $voucher
    ) );
    return $result;
}

// Add a vouch. Make sure a vouch between these users doesn't already exist!

function commoners_vouching_add ($vouch_for, $vouched_by, $description,
                                 $automatic) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'commoners_vouches';
    $wpdb->insert(
        $table_name,
        array(
            'autovouch' => $automatic,
            'description' => $description,
            'vouchee' => $vouch_for,
            'voucher' => $vouched_by
        ),
        array(
            '%d',
            '%s',
            '%d',
            '%d'
        )
    );
}

// Is the user vouched at all?

function commoners_vouching_is_vouched ($userid) {
    global $commoners_vouching_can_vouch_threshold;
    return commoners_vouching_count_vouchee( $userid ) > 0;
}

// Can the user vouch?

function commoners_vouching_can_vouch ($userid) {
    global $commoners_vouching_can_vouch_threshold;
    return commoners_vouching_count_vouchee( $userid )
        >= $commoners_vouching_can_vouch_threshold;
}

// Return a bool array indicating whether the user is vouched, and can vouch

function commoners_vouching_status ($userid) {
    global $commoners_vouching_can_vouch_threshold;
    $vouch_count = commoners_vouching_count_vouchee( $userid );
    return array(
        $vouch_count > 0,
        $vouch_count >= $commoners_vouching_can_vouch_threshold
    );
}
