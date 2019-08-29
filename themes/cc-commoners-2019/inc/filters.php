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
/**
 *  We add member metadata when and administrator is added to the website
 *  The administraror will ve an approved individual member but won't be listed in the members section because isn't a subscriber
 */
function add_admin_member_metadata( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    if ( isset( $_POST['md_multiple_roles'] ) && ( in_array( 'administrator', $_POST['md_multiple_roles'] ) ) ) {
        ccgn_user_set_individual_applicant( $user_id );
        _ccgn_registration_user_set_stage( $user_id, 'accepted' );
    }
}
//Hooks for user (Update/add)
add_action('personal_options_update', 'add_admin_member_metadata');
add_action('edit_user_profile_update', 'add_admin_member_metadata');
add_action('user_register', 'add_admin_member_metadata'); //When adding new users