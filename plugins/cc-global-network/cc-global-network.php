<?php
/*
  Plugin Name: CC Global Network
  Plugin URI: http://github.com/creativecommons/commoners
  Description: Buddypress extensions for network.creativecommons.org .
  Author: Creative Commons Corporation
  Version: 2.4
  Author URI: http://github.com/creativecommons/
  License: GPLv2 or later at your option.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Includes
////////////////////////////////////////////////////////////////////////////////

defined( 'CCGN_PATH' )
    or define( 'CCGN_PATH', plugin_dir_path( __FILE__ ) );

// Buddypress UI behaviour configuration

require_once(CCGN_PATH . 'includes/buddypress-integration.php');

// Configuration options for this plugin

require_once(CCGN_PATH . 'admin/options-emails.php');

// Tracking user membership application state

require_once(CCGN_PATH . 'includes/application-state.php');

// Interfacing with GravityForms

require_once(CCGN_PATH . 'includes/gravityforms-interaction.php');

require_once(CCGN_PATH . 'includes/format-applicant-profile.php');

// Sign-up form workflow for application

require_once(CCGN_PATH . 'includes/registration-form-emails.php');
require_once(
    CCGN_PATH . 'public/registration-individual-form-shortcode.php'
);
require_once(
    CCGN_PATH . 'public/registration-institution-form-shortcode.php'
);

// User page application interface for admins

require_once(CCGN_PATH . 'admin/user-application-page.php');
require_once(CCGN_PATH . 'admin/user-pre-approve-list-page.php');
require_once(CCGN_PATH . 'admin/user-final-approval-list-page.php');
require_once(CCGN_PATH . 'admin/user-legal-approval-list-page.php');

// Vouching UI for existing members to vouch for new applicant

require_once(CCGN_PATH . 'public/vouching-form-shortcode.php');

// Testing support

if ( defined( 'CCGN_DEVELOPMENT' ) || defined( 'CCGN_TESTING' ) ) {
    require_once(CCGN_PATH . 'testing/reset-state.php');
}

////////////////////////////////////////////////////////////////////////////////
// CAS / WordPress registration
////////////////////////////////////////////////////////////////////////////////

// CAS User Registration handling

add_action( 'user_register', 'ccgn_user_level_register' );

// Plugin-specific User roles

register_activation_hook(
    __FILE__,
    'ccgn_add_roles_on_plugin_activation'
);

////////////////////////////////////////////////////////////////////////////////
// Buddypress
////////////////////////////////////////////////////////////////////////////////

// Remove settings we want to hide because they clash with CAS signup

add_action( 'bp_setup_nav', 'ccgn_remove_settings', 15 );

add_filter( 'bp_core_get_user_domain', '_bp_core_get_user_domain', 10, 4 );
add_filter( 'bp_core_get_userid', '_bp_core_get_userid', 10, 2 );
add_filter(
    'bp_get_activity_parent_content',
    '_bp_get_activity_parent_content',
    10,
    1
);

// Member types, registration, and application

add_action( 'bp_register_member_types', 'ccgn_register_member_types' );

// Profile fields

register_activation_hook(
    __FILE__,
    'ccgn_create_profile_fields_individual'
);

register_activation_hook(
    __FILE__,
    'ccgn_create_profile_fields_institution'
);

// Don't lock the admin out

register_activation_hook(
    __FILE__,
    'ccgn_ensure_admin_access_activation_callback'
);
add_action( 'admin_init', 'ccgn_ensure_admin_access_load_plugin_callback' );

add_action(
    'bp_get_activity_action_pre_meta',
    '_bp_get_activity_action_pre_meta'
);
add_filter( 'bp_core_get_userid_from_nicename', '_bp_core_get_userid', 10, 2 );
add_action( 'bp_setup_nav', 'ccgn_not_logged_in_ui', 150 );

add_filter( 'bp_xprofile_get_groups', 'ccgn_filter_role_groups' );

add_action( 'bp_core_setup_globals', '_bp_set_default_component' );

add_action( 'bp_profile_header_meta', '_bp_meta_member_type', 10, 0 );

// Don't let unvouched users set their profiles

add_action( 'bp_ready', '_bp_remove_profile_options_if_unvouched' );

// Hide messaging from unvouched users

add_filter(
    'bp_get_send_public_message_button',
    '_bp_remove_instant_messaging_if_unvouched'
);

// Hide group/member directories etc. from users who are not logged in
add_filter( 'get_header', '_bp_not_signed_in_redirect', 1 );

////////////////////////////////////////////////////////////////////////////////
// Registration Forms
////////////////////////////////////////////////////////////////////////////////

// Populate voucher selects

add_action( 'gform_pre_render', 'ccgn_set_vouchers_options' );

add_filter( 'gform_validation', 'ccgn_choose_vouchers_validate' );

// The shortcode to display the sign-up workflow forms.
// The exact form (or other content) displayed depends on the user's
// application state/stage.

add_shortcode(
    'ccgn-signup-individual-form',
    'ccgn_registration_individual_shortcode_render'
);

add_filter( 'gform_validation', 'ccgn_vouching_form_post_validate' );

add_action(
    'gform_after_submission',
    'ccgn_application_vouching_form_submit_handler',
    10,
    2
);

// After each form in the Member sign-up process is submitted,
// we update the user's application stage/state

add_action(
    'gform_after_submission',
    'ccgn_registration_individual_form_submit_handler',
    10,
    2
);

add_shortcode(
    'ccgn-signup-institution-form',
    'ccgn_registration_institution_shortcode_render'
);

add_action(
    'gform_after_submission',
    'ccgn_registration_institution_form_submit_handler',
    10,
    2
);

//FIXME: Handle redirects in form to /individual/ -> /institutional/.
//Either go full multipage or show text & render form in shortcode.
add_filter(
    'gform_confirmation',
    'ccgn_swizzle_form_url_for_institution',
    10,
    4
);

////////////////////////////////////////////////////////////////////////////////
// Form for existing members vouching for new applicant
////////////////////////////////////////////////////////////////////////////////

// The shortcode to display the vouching form.

add_shortcode(
    'ccgn-vouching-form',
    'ccgn_vouching_shortcode_render'
);

////////////////////////////////////////////////////////////////////////////////
// Add admin settings, menus etc.
////////////////////////////////////////////////////////////////////////////////

// Remove various BuddyPress settings in various circumstances
// This isn't in admin as we need to hide it from the bar on every page on site
add_action(
    'wp_before_admin_bar_render',
    '_bp_admin_bar_remove_some_menu_items'
);

if ( is_admin() ){
    // Remove various BuddyPress settings in various circumstances
    add_action(
        'bp_members_admin_user_metaboxes',
        'ccgn_remove_member_type_metabox'
    );
    add_action( 'admin_init', 'ccgn_profile_access_control' );
    add_action( 'admin_menu', 'ccgn_application_users_menu' );
    add_action( 'admin_menu', 'ccgn_hide_application_users_menu', 999 );
    add_action( 'admin_menu', 'ccgn_settings_emails_register' );
    add_action( 'admin_menu', 'ccgn_application_pre_approval_menu' );
    add_action( 'admin_menu', 'ccgn_application_final_approval_menu' );
    add_action( 'admin_menu', 'ccgn_application_legal_approval_menu' );
    add_filter( 'user_row_actions', 'ccgn_application_user_link', 10, 2 );
    // Filter applicant user page form approve/declines to hook user profile
    // creation and notification email sending.
    add_action(
        'gform_after_submission',
        'ccgn_application_users_page_pre_form_submit_handler',
        10,
        2
    );
    add_action(
        'gform_after_submission',
        'ccgn_application_users_page_final_form_submit_handler',
        10,
        2
    );
    add_action(
        'gform_after_submission',
         'ccgn_application_users_page_legal_approval_form_submit_handler',
        10,
        2
    );
}
