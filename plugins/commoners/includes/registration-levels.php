<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Control access to field groups by level.
// The groups are currently created in the admin UI, we should make them here.
// We use group names as identifiers rather than group IDs, which are unstable.
// This does mean we have to do our own lookup though.
////////////////////////////////////////////////////////////////////////////////

// Unvouched users cannot create a profile and can only see limited profiles

define ( 'COMMONERS_USER_ROLE_UNVOUCHED', 'unvouched' );
define ( 'COMMONERS_USER_ROLE_VOUCHED', 'subscriber' );

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
        'unvouched',
        'Unvouched',
        array(
            'read' => true,
            'level_0' => true
        )
    );
}

function commoners_user_is_vouched ( $user ) {
    return ! in_array( COMMONERS_USER_ROLE_UNVOUCHED, $user->roles);
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

function commoners_user_level_set_applicant( $user ) {
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_UNVOUCHED );
    $user->set_role( COMMONERS_USER_ROLE_UNVOUCHED );

}

function commoners_user_level_set_pre_approved ( $user ) {
    commoners_registration_user_set_stage(
        $user,
        COMMONERS_APPLICATION_STATE_VOUCHING
    );
}

function commoners_user_level_set_approved ( $user ) {
    //$user->remove_role( 'subscriber' );
    //$user->add_role( COMMONERS_USER_ROLE_VOUCHED );
    $user->set_role( COMMONERS_USER_ROLE_VOUCHED );
    commoners_registration_user_set_stage(
        $user,
        COMMONERS_APPLICATION_STATE_ACCEPTED
    );
}

function commoners_user_level_set_rejected ( $user ) {
    // Lock the account
    $user->set_role( '' );
    $user->remove_all_caps();
    commoners_registration_user_set_stage(
        $user,
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
function commoners_user_level_register( $userid ) {
    $user = get_userdata( $userid );
    if ( $user ) {
        $email = $user->user_email;
        if ( commoners_vouching_should_autovouch( $email ) ) {
            commoners_vouching_user_level_set_approved( $user );
        } else {
            commoners_user_level_set_applicant( $user );
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
