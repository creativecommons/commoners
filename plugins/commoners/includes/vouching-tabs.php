<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function commoners_vouching_add_tabs () {
    global $bp;

    $access = is_user_logged_in();

    bp_core_new_nav_item( array(
        'name'                  => 'Vouching',
        'slug'                  => 'vouching',
        'parent_url'            => $bp->displayed_user->domain,
        'parent_slug'           => $bp->profile->slug,
        'screen_function'       => 'vouching_screen',
        'position'              => 200,
        'default_subnav_slug'   => 'vouches',
        'user_has_access'       => $access
    ) );

    bp_core_new_subnav_item( array(
        'name'              => 'Vouched for',
        'slug'              => 'vouched-for',
        'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'vouching' ),
        'parent_slug'       => 'vouching',
        'screen_function'   => 'vouched_for_screen',
        'position'          => 100,
        'user_has_access'   => $access
    ) );

    bp_core_new_subnav_item( array(
        'name'              => 'Vouched by',
        'slug'              => 'vouched-by',
        'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'vouching' ),
        'parent_slug'       => 'vouching',
        'screen_function'   => 'vouched_by_screen',
        'position'          => 150,
        'user_has_access'   => $access
    ) );

}

function vouching_screen () {
    //add_action( 'bp_template_title', 'vouching_screen_title' );
    add_action( 'bp_template_content', 'vouching_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function vouching_screen_title () {
    echo 'Vouch for this Commoner if you know them';
}

function vouching_screen_content () {
    $userid = bp_displayed_user_id();
    if ( $userid !== 0 ) {
        commoners_vouching_control_html ($userid);
    }
}

function vouched_for_screen () {
    add_action( 'bp_template_content', 'vouched_for_screen_content' );
    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        ) );
}

function vouched_for_screen_content () {
    $userid = bp_displayed_user_id();
    if ( $userid !== 0 ) {
        commoners_vouching_for_html ($userid);
    }
}

function vouched_by_screen () {
    add_action( 'bp_template_content', 'vouched_by_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function vouched_by_screen_content () {
    $userid = bp_displayed_user_id();
    if ( $userid !== 0 ) {
        commoners_vouching_by_html ($userid);
    }
}