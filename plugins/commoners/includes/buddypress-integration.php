<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Autovouch domains
////////////////////////////////////////////////////////////////////////////////

define(
    'COMMONERS_AUTOVOUCH_EMAIL_DOMAINS',
    [
        'creativecommons.org'
    ]
);

////////////////////////////////////////////////////////////////////////////////
// User Roles
////////////////////////////////////////////////////////////////////////////////

// Unvouched users cannot create a profile and can only see limited profiles

define( 'COMMONERS_USER_ROLE_APPLICANT_NEW', 'applicant-new' );
define( 'COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL', 'applicant-individual' );
define( 'COMMONERS_USER_ROLE_APPLICANT_INSTITUTION', 'applicant-institution' );
define( 'COMMONERS_USER_ROLE_MEMBER_INDIVIDUAL', 'member-individual' );
define( 'COMMONERS_USER_ROLE_MEMBER_INSTITUTION', 'member-institution' );
define( 'COMMONERS_USER_ROLE_MEMBER_AUTOVOUCHED', 'member-autovouched' );

// Which Field Groups different levels of registration/vouching can see
// Admin users are handled separately

$commoners_access_levels = [
    'PUBLIC' => [],
    'LOGGED_IN' => [ 'Base' ],
    'LOGGED_IN_AND_VOUCHED' => [ 'Base', 'Profile Details' ],
    'ADMIN' => ['Base', 'Profile Details']
];

function commoners_add_roles_on_plugin_activation () {
    add_role(
        COMMONERS_USER_ROLE_APPLICANT_NEW,
        'Individual Applicant',
        array(
            'read' => true,
            'level_0' => true
        )
    );
    add_role(
        COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL,
        'Individual Applicant',
        array(
            'read' => true,
            'level_0' => true
        )
    );
    add_role(
        COMMONERS_USER_ROLE_APPLICANT_INSTITUTION,
        'Institution Applicant',
        array(
            'read' => true,
            'level_0' => true
        )
    );
    $subscriber = get_role( 'subscriber' );
    add_role(
        COMMONERS_USER_ROLE_MEMBER_INDIVIDUAL,
        'Individual Member',
        $subscriber->capabilities
    );
    add_role(
        COMMONERS_USER_ROLE_MEMBER_INSTITUTION,
        'Institution Member',
        $subscriber->capabilities
    );
    add_role(
        COMMONERS_USER_ROLE_MEMBER_AUTOVOUCHED,
        'Autovouched Member',
        $subscriber->capabilities
    );
}

function commoners_user_is_new ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_NEW, $user->roles );
}

function commoners_user_is_individual_applicant ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL, $user->roles );
}

function commoners_user_is_institution_applicant ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL, $user->roles );
}

function commoners_user_is_individual ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL, $user->roles )
        || in_array( COMMONERS_USER_ROLE_MEMBER_INDIVIDUAL, $user->roles )
        || in_array( COMMONERS_USER_ROLE_MEMBER_AUTOVOUCHED, $user->roles );
}

function commoners_user_is_institution ( $user_id ) {
    return in_array( COMMONERS_USER_ROLE_APPLICANT_INSTITUTION, $user->roles )
        || in_array( COMMONERS_USER_ROLE_MEMBER_INSTITUTION, $user->roles );
}

function commoners_user_is_vouched ( $user ) {
    return ! (
        in_array( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL, $user->roles )
        || in_array( COMMONERS_USER_ROLE_APPLICANT_INSTITUTION, $user->roles )
    );
}

function commoners_current_user_is_vouched () {
    $vouched = false;
    $user = wp_get_current_user();
    // Check that the user is logged in
    if ( $user->exists() ) {
        $vouched = commoners_user_is_vouched( $user );
    }
    return $vouched;
}

function commoners_current_user_level () {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        if ( $commoners_user_is_vouched( $user )) {
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



function my_school_community_register_member_types() {
// Register Student Type member with directory
bp_register_member_type( 'student', array(
	'labels' => array(
		'name' => 'Students',
		'singular_name' => 'Student',
		),
	'has_directory' => 'student-directory'
));
// Register Parent type member with Directory
bp_register_member_type( 'parent', array(
	'labels' => array(
		'name' => 'Parents',
		'singular_name' => 'Parent',
		),
	'has_directory' => 'parent-directory'
));
// Register Teacher type member with Directory
bp_register_member_type( 'teacher', array(
	'labels' => array(
		'name' => 'Teachers',
		'singular_name' => 'Teacher',
	),
	'has_directory' => 'teacher-directory'
));
}
add_action( 'bp_init', 'my_school_community_register_member_types' );

////////////////////////////////////////////////////////////////////////////////
// Buddypress fields for member type
////////////////////////////////////////////////////////////////////////////////

DEFINE( 'COMMONERS_PROFILE_FIELD_GROUP_INDIVIDUAL', 'Individual Member' );
DEFINE( 'COMMONERS_PROFILE_FIELD_GROUP_INSTITUTION', 'Institutional Member' );

function commoners_buddypress_member_field ($group, $name, $desc, $order,
                                            $required = true,
                                            $type = 'textbox',
                                            $member_types = false ) {
    $id = xprofile_insert_field(
        array (
            'field_group_id'  => $group,
            'name'            => $name,
            'description'     => $desc,
            'field_order'     => $order,
            'is_required'     => $required,
            'type'            => $type
        )
    );
    if ( $id && $member_types ) {
        $field = new BP_XProfile_field( $id );
        $field->set_member_types( $member_types );
    }
    return $id;
}

function commoners_create_profile_fields_individual () {
    $individual_id = xprofile_insert_field_group(
        array(
            'name' => COMMONERS_PROFILE_FIELD_GROUP_INDIVIDUAL
        )
    );
    $bio_id = xprofile_insert_field(
        array (
            'field_group_id'  => $individual_id,
            'name'            => 'Bio',
            'description'     => 'A brief biography for the member',
            'field_order'     => 1,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $individual_id,
            'name'            => 'Languages',
            'description'     => 'Languages the member can speak',
            'field_order'     => 2,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $individual_id,
            'name'            => 'Location',
            'description'     => 'The country the member is based in',
            'field_order'     => 3,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $individual_id,
            'name'            => 'Links',
            'description'     => 'Links to the user\'s publicly shareable web sites, social media profiles etc.',
            'field_order'     => 4,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
}

function commoners_create_profile_fields_institution () {
    $institution_id = xprofile_insert_field_group(
        array(
            'name' => COMMONERS_PROFILE_FIELD_GROUP_INSTITUTION
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $institution_id,
            'name'            => 'Website',
            'description'     => 'The URL of the organization\'s web site',
            'field_order'     => 1,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $institution_id,
            'name'            => 'About',
            'description'     => 'A brief description of the organization',
            'field_order'     => 2,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $institution_id,
            'name'            => 'Representative',
            'description'     => 'The person to contact at the organization about Creative Commons Global Network-related matters',
            'field_order'     => 3,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
    xprofile_insert_field(
        array (
            'field_group_id'  => $institution_id,
            'name'            => 'Contact',
            'description'     => 'An email address or other means of getting in touch with the organization\'s representative',
            'field_order'     => 4,
            'is_required'     => false,
            'type'            => 'textbox'
        )
    );
}

function commoners_profile_group_id_by_name ( $name ) {
    $groups = bp_profile_get_field_groups();
    $id = false;
    foreach ( $groups as $group ) {
        if ($group[ 'name' ] == $name) {
            $id = $group[ 'id' ];
        }
    }
    return $id;
}

////////////////////////////////////////////////////////////////////////////////
// BuddyPress UI display control by user level
////////////////////////////////////////////////////////////////////////////////

// Hide core UI if the user is not logged in

function commons_not_logged_in_ui () {
    global $bp;
    if (! is_user_logged_in() ) {
        bp_core_remove_nav_item( 'activity' );
        bp_core_remove_nav_item( 'groups' );
        bp_core_remove_nav_item( 'vouching' );
        // Hide the "view" subtab. Ideally we'd hide the "profile" tab...
        unset($bp->bp_options_nav['profile']['public']);
    }
}

// Hide various field grous depending on the user's logged in / vouched status

function commoners_filter_role_groups ( $groups ) {
    $user = wp_get_current_user();
    // Admins can access everything
    if ( $user->roles[0] === 'administrator' ) {
        $accessible = $groups;
    } else {
        // Otherwise, users can access only what their level permits
        global $commoners_access_levels;
        $level = commoners_user_level( $user );
        $userGroups = $commoners_access_levels[ $level ];
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

function commoners_user_level_set_applicant_new( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_UNVOUCHED );
    $user->set_role( COMMONERS_USER_ROLE_APPLICANT_NEW );
}

function commoners_user_level_set_applicant_individual( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_UNVOUCHED );
    $user->set_role( COMMONERS_USER_ROLE_APPLICANT_INDIVIDUAL );
}

function commoners_user_level_set_applicant_institution( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_UNVOUCHED );
    $user->set_role( COMMONERS_USER_ROLE_APPLICANT_INSTITUTION );
}

function commoners_user_level_set_pre_approved ( $user_id ) {
    commoners_registration_user_set_stage(
        $user_id,
        COMMONERS_APPLICATION_STATE_VOUCHING
    );
}

function commoners_user_level_set_approved ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_VOUCHED );
    if ( commoners_user_is_individua( $user_id ) ) {
        $user->set_role( COMMONERS_USER_ROLE_MEMBER_INDIVIDUAL );
    } else {
        $user->set_role( COMMONERS_USER_ROLE_MEMBER_INSTITUTION );
    }
    commoners_registration_user_set_stage(
        $user_id,
        COMMONERS_APPLICATION_STATE_ACCEPTED
    );
}

function commoners_user_level_set_autovouched ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_VOUCHED );
    $user->set_role( COMMONERS_USER_ROLE_MEMBER_AUTOVOUCHED );
    commoners_registration_user_set_stage(
        $user_id,
        COMMONERS_APPLICATION_STATE_ACCEPTED
    );
}

function commoners_user_level_set_rejected ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    // Lock the account
    $user->set_role( '' );
    $user->remove_all_caps();
    commoners_registration_user_set_stage(
        $user_id,
        COMMONERS_APPLICATION_STATE_REJECTED
    );
}

////////////////////////////////////////////////////////////////////////////////
// Setting user level on registration
////////////////////////////////////////////////////////////////////////////////

function commoners_user_level_should_autovouch( $email ) {
    return
        // Make sure the explode won't give an Undefined Offset error
        (strpos( $email, '@') !== false)
        && in_array(
            explode( '@', $email )[1],
            COMMONERS_AUTOVOUCH_EMAIL_DOMAINS
        );
}
function commoners_user_level_register( $user_id ) {
    $userdata = get_userdata( $user_id );
    if ( $user ) {
        $email = $userdata->user_email;
        if ( commoners_vouching_should_autovouch( $email ) ) {
            commoners_vouching_user_level_set_autovouched ( $user_id );
        } else {
            commoners_user_level_set_applicant_new( $user_id );
        }
    }
}

////////////////////////////////////////////////////////////////////////////////
// Buddypress configuration for vouching level
////////////////////////////////////////////////////////////////////////////////

function _bp_remove_profile_options_if_unvouched () {
    if ( ! commoners_current_user_is_vouched() ) {
        bp_core_remove_nav_item( 'activity' );
        bp_core_remove_nav_item( 'profile' );
        bp_core_remove_nav_item( 'groups' );
        bp_core_remove_nav_item( 'forums' );
        //        bp_core_remove_nav_item( 'notifications' );
    }
}

function _bp_set_default_component () {
    if ( ! commoners_current_user_is_vouched() ) {
        define ( 'BP_DEFAULT_COMPONENT', 'notifications' );
    } else {
        define ( 'BP_DEFAULT_COMPONENT', 'profile' );
    }
}

// FIXME - need to hide correct elements

function _bp_admin_bar_remove_some_menu_items () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node(
        'wp-admin-bar-my-account-settings'
    );
    if ( ! commoners_current_user_is_vouched() ) {
        $wp_admin_bar->remove_node(
            'wp-admin-bar-edit-profile'
        );
        $wp_admin_bar->remove_node(
            'wp-admin-bar-my-account-xprofile-edit'
        );
        $wp_admin_bar->remove_node(
            'wp-admin-bar-my-account-xprofile-change-avatar'
        );
        $wp_admin_bar->remove_node(
            'wp-admin-bar-my-account-xprofile-change-cover'
        );
    }
}

////////////////////////////////////////////////////////////////////////////////
// Disallow user from changing settings.
// Password and email are functions of CCID so they must not be changed.
////////////////////////////////////////////////////////////////////////////////

function commoners_remove_settings() {
    bp_core_remove_nav_item( 'settings' );
    //FIXME: Do this then restore other items
    //bp_core_remove_subnav_item( 'settings', 'general' );
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
