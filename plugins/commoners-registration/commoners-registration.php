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
// Password and email are functions of CCID so they must not be changed.
////////////////////////////////////////////////////////////////////////////////

function commoners_remove_settings() {
    bp_core_remove_nav_item( 'settings');
    //FIXME: Do this then restore other items
    //bp_core_remove_subnav_item( 'settings', 'general' );
}
add_action( 'bp_setup_nav', 'commoners_remove_settings', 15 );

////////////////////////////////////////////////////////////////////////////////
// CCID 'global'(lowercased nickname) is used for user_nicename but is not
// url-safe.
// Make sure that it is urlencoded when used in URLs (browsers will display
// percent-encoded characters as their glyphs).
//
// See: https://stackoverflow.com/questions/22529937/how-to-custom-user-url-in-buddypress-and-wordpress
//
////////////////////////////////////////////////////////////////////////////////

// Get the user ID from the nicename.
// Which should be the slug but doesn't seem to be...

function commoners_user_by_nicename ($nicename) {
    $result = false;
    $query = new WP_User_Query(
        array (
            'nicename' => $nicename,
            'fields' => 'ID'
        )
    );
    if ( $query->get_total() === 1 ) {
        $result = $query->get_results()[0];
    }
    return $result;
}

function _bp_core_get_user_domain($domain, $user_id, $user_nicename = false, $user_login = false) {
    if ( empty( $user_id ) ){
        return;
    }
    if( isset($user_nicename) ){
        $user_nicename = bp_core_get_username($user_id);
    }
    $escaped_nicename = rawurlencode($user_nicename);
    $after_domain =  bp_get_members_root_slug() . '/' . $escaped_nicename;

    $domain = trailingslashit( bp_get_root_domain() . '/' . $after_domain );
    $domain = apply_filters( 'bp_core_get_user_domain_pre_cache', $domain, $user_id, $user_nicename, $user_login );
    if ( !empty( $domain ) ) {
        wp_cache_set( 'bp_user_domain_' . $user_id, $domain, 'bp' );
    }
    return $domain;
}

add_filter('bp_core_get_user_domain', '_bp_core_get_user_domain', 10, 4);

function _bp_core_get_userid($userid, $username){
    if(is_numeric($username)){
        $aux = get_userdata( $username );
        if( get_userdata( $username ) ) {
            $userid = $username;
        }
    } else {
        $decoded_username = rawurldecode( $username );
        $userid = commoners_user_by_nicename( $decoded_username );
    }
    return $userid;
}

add_filter('bp_core_get_userid', '_bp_core_get_userid', 10, 2);

function _bp_get_activity_parent_content($content){
    global $bp;
    $user = get_user_by('slug', $bp->displayed_user->fullname); // 'slug' - user_nicename
    return preg_replace('/href=\"(.*?)\"/is', 'href="'.bp_core_get_user_domain($user->ID, $bp->displayed_user->fullname).'"', $content);
}

add_filter( 'bp_get_activity_parent_content','_bp_get_activity_parent_content', 10, 1 );

function _bp_get_activity_action_pre_meta($content){
    global $bp;
    $fullname = $bp->displayed_user->fullname; // 'slug' - user_nicename
    $user = get_user_by('slug', $fullname);
    if(!is_numeric($user->ID) || empty($fullname)){
        $args = explode(' ', trim(strip_tags($content)));
        $fullname = trim($args[0]);
        $user = get_user_by('slug', $fullname);
    }
    return preg_replace('/href=\"(.*?)\"/is', 'href="'.bp_core_get_user_domain($user->ID, $fullname).'"', $content);
}

add_action('bp_get_activity_action_pre_meta', '_bp_get_activity_action_pre_meta');

add_filter('bp_core_get_userid_from_nicename', '_bp_core_get_userid', 10, 2);

////////////////////////////////////////////////////////////////////////////////
// Add vouched user role
////////////////////////////////////////////////////////////////////////////////

function commoners_add_user_roles() {
    // Copy subscriber capabilities
    $subscriber = get_role( 'subscriber' );
    add_role(
        'vouched', __( 'Vouched' ),
        $subscriber->capabilities
    );
}

register_activation_hook( __FILE__, 'commoners_add_user_roles' );
