<?php
/*
  Plugin Name: Commoners Registration
  Plugin URI: http://github.com/creativecommons/commoners
  Description: Fine-tune sign-up via CCID.
  Author: Creative Commons Corporation
  Version: 1.0
  Author URI: http://github.com/creativecommons/
  License: GPLv2 or later at your option.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Disallow user from changing settings.
// Password and email are functions of ccid so they must not be changed.
////////////////////////////////////////////////////////////////////////////////

function commoners_remove_settings() {
    bp_core_remove_nav_item( 'settings');
    //FIXME: Do this then restore other items
    //bp_core_remove_subnav_item( 'settings', 'general' );
}
add_action( 'bp_setup_nav', 'commoners_remove_settings', 15 );
