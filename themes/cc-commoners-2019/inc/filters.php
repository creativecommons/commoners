<?php

/*
    Filter gravityforms countries list to show them with country code
 */
add_filter('gform_countries', function ($countries) {
    $new_countries = array();

    foreach ($countries as $country) {
        $code = GF_Fields::get('address')->get_country_code($country);
        $new_countries[$code] = $country;
    }

    return $new_countries;
});

add_filter('body_class', function( $classes ){
    $classes[] = ( is_user_logged_in() ) ? 'logged-in' : 'not-logged-in';
    $classes[] = ( bp_commoners::current_user_is_accepted() ) ? 'accepted-member' : '';
    return $classes;
});

/*
    Disable admin bar except for administrators
 */

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}