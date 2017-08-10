<?php
/*
  Plugin Name: CC Commoners
  Plugin URI: http://github.com/creativecommons/commoners
  Description: Buddypress extensions for commoners.creativecommons.org .
  Author: Creative Commons Corporation
  Version: 1.0
  Author URI: http://github.com/creativecommons/
  License: GPLv2 or later at your option.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Includes
////////////////////////////////////////////////////////////////////////////////

if( ! defined( 'COMMONERS_PATH' ) ) {
    define('COMMONERS_PATH', plugin_dir_path( __FILE__ ) );
}

require_once(COMMONERS_PATH . '/includes/vouching-database.php');
require_once(COMMONERS_PATH . '/includes/vouching-auto.php');
require_once(COMMONERS_PATH . '/includes/vouching-gui.php');
require_once(COMMONERS_PATH . '/includes/vouching-tabs.php');

require_once(COMMONERS_PATH . '/includes/registration-settings.php');
require_once(COMMONERS_PATH . '/includes/registration-integration.php');
require_once(COMMONERS_PATH . '/includes/registration-levels.php');


////////////////////////////////////////////////////////////////////////////////
// Plugin lifecycle
////////////////////////////////////////////////////////////////////////////////

// Registration

add_action( 'bp_setup_nav', 'commoners_remove_settings', 15 );
add_filter( 'bp_core_get_user_domain', '_bp_core_get_user_domain', 10, 4 );
add_filter( 'bp_core_get_userid', '_bp_core_get_userid', 10, 2 );
add_filter(
    'bp_get_activity_parent_content',
    '_bp_get_activity_parent_content',
    10,
    1
);
add_action(
    'bp_get_activity_action_pre_meta',
    '_bp_get_activity_action_pre_meta'
);
add_filter( 'bp_core_get_userid_from_nicename', '_bp_core_get_userid', 10, 2 );
add_filter( 'bp_xprofile_get_groups', 'commoners_filter_role_groups' );

// Must be called after commoners_vouchinng_add_tabs
add_action( 'bp_setup_nav', 'commons_not_logged_in_ui', 150 );

// Vouching

register_activation_hook(
    __FILE__,
    'commoners_vouching_create_database_table'
);

add_action( 'user_register', 'commoners_vouching_maybe_autovouch' );

add_action( 'bp_setup_nav', 'commoners_vouching_add_tabs', 100 );
add_action( 'parse_request', 'commoners_vouching_url_handler' );
