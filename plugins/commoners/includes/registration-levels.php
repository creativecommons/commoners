<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Control access to field groups by level.
// The groups are currently created in the admin UI, we should make them here.
// We use group names as identifiers rather than group IDs, which are unstable.
// This does mean we have to do our own lookup though.
////////////////////////////////////////////////////////////////////////////////

// Which Field Groups different levels of registration/vouching can see
// Admin users are handled separately

$commoners_access_levels = [
    'PUBLIC' => [],
    'LOGGED_IN' => [ 'Base'],
    'LOGGED_IN_AND_VOUCHED' => [ 'Base', 'Profile Details' ],
    'LOGGED_IN_AND_CAN_VOUCH' => [ 'Base', 'Profile Details' ],
    'ADMIN' => ['Base', 'Profile Details']
];

function commoners_user_level ($user) {
    // User logged in?
    if ( ! empty( $user->roles[0] ) ) {
        list($vouched, $can_vouch) = commoners_vouching_status($user->ID);
        if ($can_vouch) {
            $level = 'LOGGED_IN_AND_CAN_VOUCH';
        } elseif ($vouched) {
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

define( 'BP_DEFAULT_COMPONENT', 'profile' );

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
