<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Vouching and voting
////////////////////////////////////////////////////////////////////////////////

if ( defined( 'CCGN_DEVELOPMENT' ) ) {
    define( 'CCGN_NUMBER_OF_VOUCHES_NEEDED', 1 );
    define( 'CCGN_NUMBER_OF_VOUCHES_AGAINST_ALLOWED', 1 );
    define( 'CCGN_NUMBER_OF_VOTES_NEEDED', 1 );
    define( 'CCGN_NUMBER_OF_VOTES_AGAINST_ALLOWED', 1 );
} elseif ( defined( 'CCGN_TESTING' ) ) {
    define( 'CCGN_NUMBER_OF_VOUCHES_NEEDED', 1 );
    define( 'CCGN_NUMBER_OF_VOUCHES_AGAINST_ALLOWED', 0 );
    define( 'CCGN_NUMBER_OF_VOTES_NEEDED', 1 );
    define( 'CCGN_NUMBER_OF_VOTES_AGAINST_ALLOWED', 0 );
} else {
    define( 'CCGN_NUMBER_OF_VOUCHES_NEEDED', 2 );
    define( 'CCGN_NUMBER_OF_VOUCHES_AGAINST_ALLOWED', 0 );
    define( 'CCGN_NUMBER_OF_VOTES_NEEDED', 5 );
    define( 'CCGN_NUMBER_OF_VOTES_AGAINST_ALLOWED', 0 );
}

// The user has not been vouched by other members, but is considered vouched
define( 'COMMONERS_USER_IS_AUTOVOUCHED', 'ccgn-user-autovouched' );

////////////////////////////////////////////////////////////////////////////////
// Buddypress config
////////////////////////////////////////////////////////////////////////////////

define(
    'CCGN_BP_DISABLED_MODULES_MEMBER',
    array( 'activity', 'blogs', 'friends', 'groups', 'notifications', 'messages' )
);

define(
    'CCGN_BP_DISABLED_MODULES_APPLICANT',
    array_merge(
        CCGN_BP_DISABLED_MODULES_MEMBER,
        array( 'xprofile', 'settings' )
    )
);

define(
    'CCGN_BP_DISABLED_MODULES_NOT_LOGGED_IN',
    array_merge(
        CCGN_BP_DISABLED_MODULES_APPLICANT,
        // Not members
        array( /*'members'*/ )
    )
);

////////////////////////////////////////////////////////////////////////////////
// User Roles
////////////////////////////////////////////////////////////////////////////////

// Unvouched users cannot create a profile and can only see limited profiles

define( 'CCGN_USER_ROLE_NEW', 'new-user' );

// The user is a member of the membership council (in addition to being an
// individual member

define( 'CCGN_USER_ROLE_MEMBERSHIP_COUNCIL', 'membership-council-member' );

// User can give final application approval from the membership council approval

define( 'CCGN_USER_ROLE_FINAL_APPROVER', 'membership-final-approver' );

// User is part of CC legal

define( 'CCGN_USER_ROLE_CC_LEGAL_TEAM', 'membership-cc-legal' );

// Which Field Groups different levels of registration/vouching can see
// Admin users are handled separately

// We don't use Base, so filter out 'Base' by not listing it.
// If we did use it, we'd have it at 'LOGGED_IN' and above.

// Make sure institutional profiles are always visible

$ccgn_access_levels = [
    'PUBLIC' => [ 'Insititutional Member' ],
    'LOGGED_IN' => [ 'Insititutional Member' ],
    'LOGGED_IN_AND_VOUCHED' => [ 'Individual Member', 'Insititutional Member' ],
    'ADMIN' => [  'Individual Member', 'Institutional Member' ]
];

// Options for Interests
// These MUST match the text in GravityForms PRECISELY

define(
    'CCGN_INDIVIDUAL_INTERESTS_OPTIONS',
    array(
        "Arts & Culture", "Community Development", "Education / OER",
        "GLAM (Galleries, Libraries, Archives & Museums)", "Legal",
        "Open Access", "Open Data", "Open Science",
        "Policy / advocacy / copyright reform", "Technology",
    )
);

function ccgn_add_roles_on_plugin_activation () {
    add_role(
        CCGN_USER_ROLE_NEW,
        'New User',
        array(
            'read' => true,
            'level_0' => true
        )
    );
    add_role(
        CCGN_USER_ROLE_MEMBERSHIP_COUNCIL,
        'Membership Council',
        array()
    );
    add_role(
        CCGN_USER_ROLE_FINAL_APPROVER,
        'Membership Final Approver',
        array()
    );
    add_role(
        CCGN_USER_ROLE_CC_LEGAL_TEAM,
        'CC Legal Team',
        array()
    );

    $council = get_role( CCGN_USER_ROLE_MEMBERSHIP_COUNCIL );
    $approver = get_role( CCGN_USER_ROLE_FINAL_APPROVER );
    $legal = get_role( CCGN_USER_ROLE_CC_LEGAL_TEAM );

    //FIXME: Declare these
    $council->add_cap( 'ccgn_view_applications' );
    $approver->add_cap( 'ccgn_view_applications' );
    $legal->add_cap( 'ccgn_view_applications' );

    $council->add_cap( 'ccgn_list_applications' );
    $approver->add_cap( 'ccgn_list_applications' );
    $legal->add_cap( 'ccgn_list_applications' );

    $approver->add_cap( 'ccgn_pre_approve' );

    $legal->add_cap( 'ccgn_list_applications_legal' );
    // The Final Approver can list but not approve legal-stage applications
    $approver->add_cap( 'ccgn_list_applications_legal' );
}

function ccgn_user_is_new ( $user_id ) {
    $new = true;
    $user = get_user_by( 'ID', $user_id );
    if ( $user ) {
        $new = ($user->roles == null)
             || in_array( CCGN_USER_ROLE_NEW, $user->roles );
    }
    return $new;
}

// DANGER - We use this to mean "is a member" here. This must be changed.

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

function ccgn_user_join_membership_council ( $user_id ) {
    $user = new WP_User( $user_id );
    $user->add_role( CCGN_USER_ROLE_MEMBERSHIP_COUNCIL );
}

function ccgn_user_leave_membership_council ( $user_id ) {
    $user = new WP_User( $user_id );
    $user->remove_role( CCGN_USER_ROLE_MEMBERSHIP_COUNCIL );
}

function ccgn_current_user_is_membership_council () {
    $is = false;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_userdata( $user_id );
        $is = in_array( CCGN_USER_ROLE_MEMBERSHIP_COUNCIL, $data->roles );
    }
    return $is;
}

function ccgn_current_user_is_final_approver () {
    $is = false;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_userdata( $user_id );
        $is = in_array( CCGN_USER_ROLE_FINAL_APPROVER, $data->roles );
    }
    return $is;
}

function ccgn_current_user_is_legal_team () {
    $is = false;
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $data = get_userdata( $user_id );
        $is = in_array( CCGN_USER_ROLE_CC_LEGAL_TEAM, $data->roles );
    }
    return $is;
}

function ccgn_current_user_can_see_user_application_page () {
    return ccgn_current_user_is_membership_council ()
        || ccgn_current_user_is_final_approver ()
        || ccgn_current_user_is_legal_team ();
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

function ccgn_get_individual_members () {
    // Beware altering this - see ccgn_registration_form_list_members
    //                        in gravityforms-interaction.php
    // FIXME: Filter admin, council members, inactive members
    // INITIAL PHASE: Council members can be asked to Vouch
    return bp_core_get_users(
        array(
            'type' => 'alphabetical',
            'per_page' => '9999999',
            'member_type' => 'individual-member'
        )
    )["users"];
}

function ccgn_members_individual_ids () {
    return array_map( "_ccgn_wp_user_id", ccgn_get_individual_members () );
}

function ccgn_get_institutional_members () {
    // FIXME: Filter admin, council members, inactive members
    // INITIAL PHASE: Council members can be asked to Vouch
    return bp_core_get_users(
        array(
            'type' => 'alphabetical',
            'per_page' => '9999999',
            'member_type' => 'institutional-member'
        )
    )["users"];
}

function ccgn_members_institutional_ids () {
    return array_map( "_ccgn_wp_user_id", ccgn_get_institutional_members () );
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

// There is really no API for this. :-(

function ccgn_buddypress_checkbox_options( $group_id, $checkbox_id, $options ) {
    global $bp, $wpdb;

    $is_default = false;
    $counter = 1;
    foreach ($options as $option_value) {
        $exists = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$bp->profile->table_name_fields} WHERE group_id=%d AND parent_id=%d AND type='option' AND name=%s",
                $group_id,
                $checkbox_id,
                $option_value
            )
        );
        if ( $exists !== NULL ) {
            continue;
        }
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$bp->profile->table_name_fields} (group_id, parent_id, type, name, description, is_required, option_order, is_default_option) VALUES (%d, %d, 'option', %s, '', 0, %d, %d)",
                $group_id,
                $checkbox_id,
                $option_value,
                $counter,
                $is_default
            )
        );
        $counter++;
    }
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
        'texarea',
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
        'The country where the member is usually located',
        3,
        false,
        'textbox',
        'individual-member'
    );
    ccgn_buddypress_member_field(
        $individual_id,
        'Preferred Country Chapter',
        'The country chapter that the member is interested in',
        4,
        false,
        'textbox',
        'individual-member'
    );
    $interests_id = ccgn_buddypress_member_field(
        $individual_id,
        'Areas of Interest',
        'Areas the user is interested in.',
        5,
        false,
        'checkbox',
        'individual-member'
    );
    ccgn_buddypress_checkbox_options(
        $individual_id,
        $interests_id,
        CCGN_INDIVIDUAL_INTERESTS_OPTIONS
    );

    ccgn_buddypress_member_field(
        $individual_id,
        'Links',
        'Links to the user\'s publicly shareable web sites, social media profiles etc.',
        6,
        false,
        'textarea',
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
        'Representative',
        'The person to contact at the organization about Creative Commons Global Network-related matters',
        2,
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
// BuddyPress UI display control
////////////////////////////////////////////////////////////////////////////////

function _bp_remove_components( $enabled, $component ) {
    // If we are developing & in the CLI we need xprofile to reset applications
    if ( defined( 'WP_CLI' )
         && ( defined( 'CCGN_DEVELOPMENT' ) || defined( 'CCGN_TESTING' ) ) ) {
        return true;
    }
    $user_id = get_current_user_id();
    if ( ccgn_user_is_vouched( $user_id ) ) {
        $modules = CCGN_BP_DISABLED_MODULES_MEMBER;
    } elseif( ccgn_user_is_new ( $user_id ) ) {
        $modules = CCGN_BP_DISABLED_MODULES_APPLICANT;
    } else {
        $modules = CCGN_BP_DISABLED_MODULES_NOT_LOGGED_IN;
    }
    return ! in_array( $component, $modules );
}


// Hide core UI if the user is not logged in

function ccgn_not_logged_in_ui () {
    // Actually never show these
    //    bp_core_remove_nav_item( 'activity' );
    //bp_core_remove_nav_item( 'groups' );
    if (! is_user_logged_in() ) {
        // Just don't display people's profiles
        bp_core_remove_nav_item( 'profile' );
        // Hide the "view" subtab. Ideally we would hide the "profile" tab...
        //unset($bp->bp_options_nav['profile']['public']);
    }
}

// Hide various field groups depending on the user's logged in / vouched status
// We can't hide Base in edit, and shouldn't as it's the way the user changes
// their name.

function ccgn_filter_role_groups ( $groups, $args ) {
    $user = wp_get_current_user();
    // Admins can access everything
    if ( in_array( 'administrator', $user->roles) ) {
        $accessible = $groups;
    } else {
        // Otherwise, users can access only what their level permits
        global $ccgn_access_levels;
        $level = ccgn_current_user_level();
        $userGroups = $ccgn_access_levels[ $level ];
        $accessible = array();
        // Otherwise wp plugin activate complains about the in_array below
        if ( $userGroups ) {
            // TODO: Cache group IDs and check these instead
            $index = 0;
            foreach ( $groups as $id => $group ) {
                if ( in_array( $group->name, $userGroups)  ) {
                    $accessible[$index] = $group;
                    $index++;
                }
            }
        }
    }
    return $accessible;
}

function _bp_hide_profile_field_group( $retval ) {
    $exclude = [];
    $member_id = get_current_user_id();
    if ( ccgn_member_is_individual( $member_id ) ) {
        $exclude[] = ccgn_profile_group_id_by_name (
            CCGN_PROFILE_FIELD_GROUP_INSTITUTION
        );
    }
    if ( ccgn_member_is_institution( $member_id ) ) {
        $exclude[] = ccgn_profile_group_id_by_name (
            CCGN_PROFILE_FIELD_GROUP_INDIVIDUAL
        );
    }
    if ( ! is_super_admin() ) {
        $exclude[] = 1;
        // Prepend 1 to value
    }
    sort( $exclude );
    $retval['exclude_groups'] = implode( ',', $exclude );
    return $retval;
}

////////////////////////////////////////////////////////////////////////////////
// Buddypress UI configuration for vouching level
////////////////////////////////////////////////////////////////////////////////

function _bp_set_default_component () {
    define ( 'BP_DEFAULT_COMPONENT', 'profile' );
}

// FIXME - need to hide correct elements

function _bp_admin_bar_remove_some_menu_items () {
    global $wp_admin_bar;
    if ( ! ccgn_current_user_is_vouched() ) {
        // Do not allow un-approved members to edit their WordPress profile
        $wp_admin_bar->remove_menu( 'edit-profile', 'user-actions');
        $wp_admin_bar->remove_node( 'my-account' );
    }
    $wp_admin_bar->remove_node( 'my-account-forums' );
    $wp_admin_bar->remove_node( 'my-account-settings' );
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
    // We don't want people to be able to change their email, this breaks CCID
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
    // This order seems to be important
    bp_set_member_type( $user_id, 'individual-member' );
    $user = get_user_by( 'ID', $user_id );
    $user->set_role( 'subscriber' );
}

function ccgn_user_level_set_member_institution( $user_id ) {
    // This order seems to be important
    bp_set_member_type( $user_id, 'institutional-member' );
    $user = get_user_by( 'ID', $user_id );
    $user->set_role( 'subscriber' );
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

function ccgn_user_is_autovouched( $user_id ) {
    return get_user_meta( $user_id, CCGN_USER_IS_AUTOVOUCHED, true) === true;
}

// Use this in wp-cli shell to bootstrap interim membership council

if ( defined( 'INTERIM_MEMBERSHIP_COUNCIL' ) ) {
    function _ccgn_user_level_set_autouvouched_interim_membership_council (
        $user_id
    ) {
        ccgn_user_level_set_autovouched( $user_id );
        ccgn_user_join_membership_council( $user_id );
    }
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

function ccgn_user_level_set_didnt_update_vouchers ( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    // Lock the account
    $user->set_role( '' );
    $user->remove_all_caps();
    ccgn_registration_user_set_stage(
        $user_id,
        CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS
    );
}

// This is called by WordPress when the user signs up to the site

function ccgn_user_level_register( $user_id ) {
    // We could just set the default user type option...
    ccgn_user_level_set_applicant_new( $user_id );
}

////////////////////////////////////////////////////////////////////////////////
// Admin access hooks
////////////////////////////////////////////////////////////////////////////////

// Calling this from register_activation_hook doesn't set the user as an
// individual-user. So we go through some contortions to call it when
// bp_set_member_type() is happy.
// I'd prefer to understand this better but it works for the moment. - RobM.

function ccgn_ensure_admin_access () {
    $admin = 1;
    ccgn_user_level_set_autovouched ( $admin );
    //FIXME: Get and restore role around the autovouch
    $user = get_user_by( 'ID', $admin );
    $user->set_role( 'administrator' );
}

function ccgn_ensure_admin_access_activation_callback () {
  add_option( 'Activated_Plugin', 'ccgn-global-network' );
}

function ccgn_ensure_admin_access_load_plugin_callback () {
    if ( is_admin()
         && ( get_option( 'Activated_Plugin' ) == 'ccgn-global-network' ) ) {
        delete_option( 'Activated_Plugin' );
        ccgn_ensure_admin_access ();
    }
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

function _bp_meta_member_type () {
    $user_id = bp_displayed_user_id();
    if ( ccgn_member_is_individual ( $user_id ) ) {
        echo _('Individual Member');
    } elseif ( ccgn_member_is_institution ( $user_id ) ) {
        echo _('Institutional Member');
    }
}

function _bp_not_signed_in_redirect () {
    if ( bp_is_directory()
         || bp_is_activity_component() || bp_is_groups_component()
         // || bp_is_group_forum() // || bp_is_page( BP_MEMBERS_SLUG )
         || bp_is_profile_component() || bp_is_forums_component()
         /*|| bbp_is_single_forum() || bbp_is_single_topic()*/
    ) {
        if( ! is_user_logged_in() ) {
            wp_redirect(
                'https://login.creativecommons.org/login?service='
                . get_site_url()
                // Doesn't work at this point . get_permalink()
            );
            exit();
        }
    }
}

////////////////////////////////////////////////////////////////////////////////
// Member directory filtering
////////////////////////////////////////////////////////////////////////////////

// Not all users, just new users (who are not yet members)

function ccgn_bp_directory_query ( $qs=false, $object=false ) {
    if ( $object != 'members' ) {
        return $qs;
    }
    $new_users = get_users(
        array(
            // Exclude site admins and Applicants
            'role__in' => array( 'administrator', CCGN_USER_ROLE_NEW ),
            // But include site admins who are Members or part of the interim
            // membership council
            'role__not_in' => array(
                'subscriber',
                'membership-council-member'
            ),
            'fields' => array( 'ID' )
        )
    );
    $args = wp_parse_args( $qs );
    if(!empty($args['user_id'])) {
        return $qs;
    }
    $exclude = array();
    foreach ( $new_users as $user ) {
        $exclude[] = $user->ID;
    }
    if( ! empty( $args[ 'exclude' ] ) ) {
        $args[ 'exclude' ] = $args[ 'exclude' ] . ',' . $exclude;
    } else {
        $args[ 'exclude' ] = join( ',', $exclude );
    }

    // Force alphabetic order
    // Is the /members/ membership directory order wrong? This is the cause.
    // See also #members-order-select
    $args['type'] = 'alphabetical';

    $qs = build_query( $args );
    return $qs;
}

////////////////////////////////////////////////////////////////////////////////
// Profile Display
////////////////////////////////////////////////////////////////////////////////

function ccgn_username_display () {
    // Don't clash with the existing nicename
    // If we enable the activity module we'll have to hook bp_displayed_user_id
    if ( ! ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) ) {
        $displayed_user = get_user_by( 'ID', bp_displayed_user_id() );
        // h2 pushes the member type field below the avatar, which looks bad
        echo '<p class="user-nicename">'
            . $displayed_user->display_name
            .'</p>';
    }
}

///////////////////////////////////////////////////////////////////////////////
// Search
///////////////////////////////////////////////////////////////////////////////

function ccgn_members_distinct_xprofile_values ( $field_name ) {
    $field_id = xprofile_get_field_id_from_name( $field_name );
    global $wpdb;
    $query = $wpdb->prepare(
        "SELECT DISTINCT value
         FROM {$wpdb->prefix}bp_xprofile_data
         WHERE field_id = %d",
        $field_id
    );
    $values = $wpdb->get_col($query);
    sort( $values );
    return $values;
}

function ccgn_members_chapter_interest_countries () {
    return ccgn_members_distinct_xprofile_values (
        'Preferred Country Chapter'
    );
}

// Do this with hacky SQL rather than inefficient PHP

function ccgn_members_with_xprofile_value( $field, $value ) {
    $field_id = xprofile_get_field_id_from_name( $field );
    global $wpdb;
    $query = $wpdb->prepare(
        "SELECT user_id
         FROM {$wpdb->prefix}bp_xprofile_data
         WHERE field_id = %d
         AND value = %s",
        $field_id,
        $value
    );
    $user_ids = $wpdb->get_col($query);
    return get_users(
        array(
            'include' => $user_ids,
            // This is redundant because only registered members have xprofiles
            // But keep it in case that changes.
            'role' => 'subscriber',
            'orderby' => 'nicename',
            'order' => 'ASC'
        )
    );
}

function ccgn_members_interested_in_chapter ( $chapter_country ) {
    return ccgn_members_with_xprofile_value(
        'Preferred Country Chapter',
        $chapter_country
    );
}
