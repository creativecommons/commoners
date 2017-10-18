<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Vouching and voting
////////////////////////////////////////////////////////////////////////////////

define( 'CCGN_NUMBER_OF_VOUCHES_NEEDED', 2 );

define( 'COMMONERS_USER_IS_AUTOVOUCHED', 'ccgn-user-autovouched' );

////////////////////////////////////////////////////////////////////////////////
// User Roles
////////////////////////////////////////////////////////////////////////////////

// Unvouched users cannot create a profile and can only see limited profiles

define( 'CCGN_USER_ROLE_NEW', 'new-user' );

// Which Field Groups different levels of registration/vouching can see
// Admin users are handled separately

// We don't use Base, so filter out 'Base' by not listing it.
// If we did use it, we'd have it at 'LOGGED_IN' and above.

$ccgn_access_levels = [
    'PUBLIC' => [],
    'LOGGED_IN' => [],
    'LOGGED_IN_AND_VOUCHED' => [ 'Individual Member', 'Insititutional Member' ],
    'ADMIN' => [  'Individual Member', 'Institutional Member' ]
];

function ccgn_add_roles_on_plugin_activation () {
    add_role(
        CCGN_USER_ROLE_NEW,
        'New User',
        array(
            'read' => true,
            'level_0' => true
        )
    );
}

function ccgn_user_is_new ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    return ($user->roles == null)
           || in_array( CCGN_USER_ROLE_NEW, $user->roles );
}

function ccgn_user_is_vouched( $user_id ) {
    // To get past being new, you must be vouched
    return ! ccgn_user_is_new ( $user_id );
}

function ccgn_current_user_is_vouched () {
    $vouched = false;
    $user = wp_get_current_user();
    // Check that the user is logged in
    if ( $user->exists() ) {
        $vouched = ccgn_user_is_vouched( $user->ID );
    }
    return $vouched;
}

function ccgn_current_user_level () {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        if ( ccgn_user_is_vouched( $user_id )) {
            $level = 'LOGGED_IN_AND_VOUCHED';
        }else {
            $level = 'LOGGED_IN';
        }
    } else {
        // User not logged in, this is public
        $level = 'PUBLIC';
    }
    return $level;
}

////////////////////////////////////////////////////////////////////////////////
// Buddypress member types
////////////////////////////////////////////////////////////////////////////////

function ccgn_register_member_types () {
    // Register Parent type member with Directory
    bp_register_member_type( 'individual-member', array(
        'labels' => array(
            'name' => 'Individual Members',
            'singular_name' => 'Individual Member',
        ),
        'has_directory' => 'individual'
    ));
    // Register Parent type member with Directory
    bp_register_member_type( 'institutional-member', array(
        'labels' => array(
            'name' => 'Institutional Members',
            'singular_name' => 'Institutional Member',
        ),
        'has_directory' => 'institution'
    ));
}

function ccgn_member_is_individual ( $user_id ) {
    $type = bp_get_member_type( $user_id );
    return $type == 'individual-member';
}

function ccgn_member_is_institution ( $user_id ) {
    $type = bp_get_member_type( $user_id );
    return $type == 'institutional-member';
}

////////////////////////////////////////////////////////////////////////////////
// Buddypress fields for member type
////////////////////////////////////////////////////////////////////////////////

DEFINE( 'CCGN_PROFILE_FIELD_GROUP_INDIVIDUAL', 'Individual Member' );
DEFINE( 'CCGN_PROFILE_FIELD_GROUP_INSTITUTION', 'Institutional Member' );

function ccgn_profile_group_id_by_name ( $name ) {
    // Not cached, but bp_profile_get_field_groups fails for some reason
    $groups = bp_xprofile_get_groups();
    $id = false;
    foreach ( $groups as $group ) {
        if ($group->name == $name) {
            $id = $group->id;
        }
    }
    return $id;
}

// This will update the group if it exists

function ccgn_ensure_profile_group ( $name, $desc ) {
    $group_id = xprofile_insert_field_group(
        array(
            'field_group_id' => ccgn_profile_group_id_by_name( $name ),
            'name' => $name,
            'description' => $desc
        )
    );
    return $group_id;
}

// FIXME: check if field exists, update if so

function ccgn_buddypress_member_field ($group, $name, $desc, $order,
                                            $required = true,
                                            $type = 'textbox',
                                            $member_type = false ) {
    $existing_id = xprofile_get_field_id_from_name( $name );
    if ( $existing_id === false ) {
        $existing_id = null;
    }
    $id = xprofile_insert_field(
        array (
            'field_group_id'  => $group,
            'field_id'        => $existing_id,
            'name'            => $name,
            'description'     => $desc,
            'field_order'     => $order,
            'is_required'     => $required,
            'type'            => $type
        )
    );
    if ( $id && $member_type ) {
        //FIXME: Update to handle multiple member types
        // This works, BP_XProfile_Field::set_member_types doesn't
        bp_xprofile_update_meta( $id, 'field', 'member_type', $member_type );
    }
    return $id;
}

function ccgn_create_profile_fields_individual () {
    $individual_id = ccgn_ensure_profile_group(
        CCGN_PROFILE_FIELD_GROUP_INDIVIDUAL,
        'Individual Member Profile Fields'
    );
    ccgn_buddypress_member_field(
        $individual_id,
        'Bio',
        'A brief biography for the member',
        1,
        false,
        'textbox',
        'individual-member'
    );
    ccgn_buddypress_member_field(
        $individual_id,
        'Languages',
        'Languages the member can speak',
        2,
        false,
        'textbox',
        'individual-member'
    );
    ccgn_buddypress_member_field(
        $individual_id,
        'Location',
        'The country the member is based in',
        3,
        false,
        'textbox',
        'individual-member'
    );
    ccgn_buddypress_member_field(
        $individual_id,
        'Links',
        'Links to the user\'s publicly shareable web sites, social media profiles etc.',
        4,
        false,
        'textbox',
        'individual-member'
    );
}

function ccgn_create_profile_fields_institution () {
    $institution_id = ccgn_ensure_profile_group(
        CCGN_PROFILE_FIELD_GROUP_INSTITUTION,
        'Institutional Member Profile Fields'
    );
    ccgn_buddypress_member_field(
        $institution_id,
        'Website',
        'The URL of the organization\'s web site',
        1,
        true,
        'textbox',
        'institutional-member'
    );
    ccgn_buddypress_member_field(
        $institution_id,
        'About',
        'A brief description of the organization',
        2,
        true,
        'textbox',
        'institutional-member'
    );
    ccgn_buddypress_member_field(
        $institution_id,
        'Representative',
        'The person to contact at the organization about Creative Commons Global Network-related matters',
        3,
        true,
        'textbox',
        'institutional-member'
    );
    ccgn_buddypress_member_field(
        $institution_id,
        'Contact',
        'An email address or other means of getting in touch with the organization\'s representative',
        4,
        true,
        'textbox',
        'institutional-member'
    );
}

////////////////////////////////////////////////////////////////////////////////
// No, users should not be able to change their own member type, that's silly
////////////////////////////////////////////////////////////////////////////////

function ccgn_remove_member_type_metabox() {
    remove_meta_box(
        'bp_members_admin_member_type',
        get_current_screen()->id,
        'side'
    );
}

////////////////////////////////////////////////////////////////////////////////
// BuddyPress UI display control by user level
////////////////////////////////////////////////////////////////////////////////

// Hide core UI if the user is not logged in

function ccgn_not_logged_in_ui () {
    global $bp;
    if (! is_user_logged_in() ) {
        // Just don't display people's profiles
        bp_core_remove_nav_item( 'profile' );
        bp_core_remove_nav_item( 'activity' );
        bp_core_remove_nav_item( 'groups' );
        // Hide the "view" subtab. Ideally we would hide the "profile" tab...
        //unset($bp->bp_options_nav['profile']['public']);
    }
}

// Hide various field grous depending on the user's logged in / vouched status

function ccgn_filter_role_groups ( $groups ) {
    $user = wp_get_current_user();
    // Admins can access everything
    if ( in_array( 'administrator', $user->roles) ) {
        $accessible = $groups;
    } else {
        // Otherwise, users can access only what their level permits
        global $ccgn_access_levels;
        $level = ccgn_current_user_level();
        $userGroups = $ccgn_access_levels[ $level ];
        $accessible = [];
        // TODO: Cache group IDs and check these instead
        foreach ( $groups as $group ) {
            if ( in_array( $group->name, $userGroups)  ) {
                $accessible[] = $group;
            }
        }
    }
    return $accessible;
}

////////////////////////////////////////////////////////////////////////////////
// Buddypress UI configuration for vouching level
////////////////////////////////////////////////////////////////////////////////

function _bp_remove_profile_options_if_unvouched () {
    if ( ! ccgn_current_user_is_vouched() ) {
        bp_core_remove_nav_item( 'activity' );
        // Unvouched users need to be able to see other users profiles
        //bp_core_remove_nav_item( 'profile' );
        bp_core_remove_nav_item( 'groups' );
        bp_core_remove_nav_item( 'forums' );
        bp_core_remove_nav_item( 'notifications' );
    }
}

function _bp_set_default_component () {
    define ( 'BP_DEFAULT_COMPONENT', 'profile' );
}

// FIXME - need to hide correct elements

function _bp_admin_bar_remove_some_menu_items () {
    global $wp_admin_bar;
    if ( ! ccgn_current_user_is_vouched() ) {
        // Do not allow un-approved members to edit their WordPress profile
        $wp_admin_bar->remove_menu( 'edit-profile', 'user-actions');
        $wp_admin_bar->remove_node('my-account');
    }
}

function ccgn_profile_access_control () {
    if ( ! ccgn_current_user_is_vouched() ) {
        if( IS_PROFILE_PAGE === true ) {
            wp_die( 'You will be able to edit your profile once your membership is approved' );
        }
        remove_menu_page( 'profile.php' );
    }
}

////////////////////////////////////////////////////////////////////////////////
// Disallow user from changing settings.
// Password and email are functions of CCID so they must not be changed.
////////////////////////////////////////////////////////////////////////////////

function ccgn_remove_settings() {
    bp_core_remove_nav_item( 'settings' );
    //FIXME: Do this then restore other items
    //bp_core_remove_subnav_item( 'settings', 'general' );
}

////////////////////////////////////////////////////////////////////////////////
// BuddyPress Member type (and WordPress Role) setting
////////////////////////////////////////////////////////////////////////////////

function ccgn_user_level_set_applicant_new( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    $user->set_role( CCGN_USER_ROLE_NEW );
}

function ccgn_user_level_set_member_individual( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    $user->set_role( 'subscriber' );
    bp_set_member_type( $user_id, 'individual-member' );
}

function ccgn_user_level_set_member_institution( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    $user->set_role( 'subscriber' );
    bp_set_member_type( $user_id, 'institutional-member' );
}

////////////////////////////////////////////////////////////////////////////////
// Setting user level on registration
////////////////////////////////////////////////////////////////////////////////

function ccgn_user_level_set_pre_approved ( $user_id ) {
    ccgn_registration_user_set_stage(
        $user_id,
        CCGN_APPLICATION_STATE_VOUCHING
    );
}

function ccgn_user_level_set_approved ( $user_id ) {
    if ( ccgn_user_is_individual_applicant( $user_id ) ) {
        ccgn_user_level_set_member_individual( $user_id );
    } else {
        ccgn_user_level_set_member_institution( $user_id );
    }
    ccgn_registration_user_set_stage(
        $user_id,
        CCGN_APPLICATION_STATE_ACCEPTED
    );
}

// For User #1 and for interim membership council members

function ccgn_user_level_set_autovouched ( $user_id ) {
    ccgn_user_level_set_member_individual( $user_id );
    ccgn_registration_user_set_stage(
        $user_id,
        CCGN_APPLICATION_STATE_ACCEPTED
    );
    update_user_meta( $user_id, CCGN_USER_IS_AUTOVOUCHED, true );
}

function ccgn_ensure_admin_access () {
    $admin = 1;
    ccgn_user_level_set_autovouched ( $admin );
    //FIXME: Get and restore role around the autovouch
    $user = get_user_by( 'ID', $admin );
    $user->set_role( 'administrator' );
}

function ccgn_user_level_set_rejected ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    // Lock the account
    $user->set_role( '' );
    $user->remove_all_caps();
    ccgn_registration_user_set_stage(
        $user_id,
        CCGN_APPLICATION_STATE_REJECTED
    );
}

// This is called by WordPress when the user signs up to the site

function ccgn_user_level_register( $user_id ) {
    // We could just set the default user type option...
    ccgn_user_level_set_applicant_new( $user_id );
}

// This is called in testing to reset a user's application
// It really does wipe their application details, so be careful.

function _ccgn_user_level_reset ( $user_id ) {
    _ccgn_application_delete_entries( $user_id );
    delete_user_meta( $user_id, CCGN_APPLICATION_STATE );
    ccgn_user_level_set_applicant_new( $user_id );
    bp_remove_member_type( $user_id, 'individual-member' );
    bp_remove_member_type( $user_id, 'institutional-member' );
    xprofile_delete_field_data( '', $user_id );
}

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

function ccgn_user_by_nicename ($nicename) {
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

function _bp_core_get_userid($userid, $username){
    if(is_numeric($username)){
        $aux = get_userdata( $username );
        if( get_userdata( $username ) ) {
            $userid = $username;
        }
    } else {
        $decoded_username = rawurldecode( $username );
        $userid = ccgn_user_by_nicename( $decoded_username );
    }
    return $userid;
}

function _bp_get_activity_parent_content($content){
    global $bp;
    $user = get_user_by('slug', $bp->displayed_user->fullname); // 'slug' - user_nicename
    return preg_replace('/href=\"(.*?)\"/is', 'href="'.bp_core_get_user_domain($user->ID, $bp->displayed_user->fullname).'"', $content);
}

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
