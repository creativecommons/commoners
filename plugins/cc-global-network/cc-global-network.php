<?php
/*
  Plugin Name: CC Global Network
  Plugin URI: http://github.com/creativecommons/commoners
  Description: Buddypress extensions for network.creativecommons.org .
  Author: Creative Commons Corporation
  Version: 2.5
  Author URI: http://github.com/creativecommons/
  License: GPLv2 or later at your option.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Includes
////////////////////////////////////////////////////////////////////////////////

defined( 'CCGN_PATH' )
    or define( 'CCGN_PATH', plugin_dir_path( __FILE__ ) );
defined('CCGN_URL_PATH')
    or define('CCGN_URL_PATH', plugin_dir_url(__FILE__));

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

// Finding and emailing members

require_once(CCGN_PATH . 'includes/contact-emails.php');
require_once(CCGN_PATH . 'public/chapter-member-search-shortcode.php');

// User page application interface for admins

require_once(CCGN_PATH . 'admin/user-application-page.php');
require_once(CCGN_PATH . 'admin/user-pre-approve-list-page.php');
require_once(CCGN_PATH . 'admin/user-final-approval-list-page.php');
require_once(CCGN_PATH . 'admin/user-legal-approval-list-page.php');
require_once(CCGN_PATH . 'admin/user-vouchers-change-page.php');
require_once(CCGN_PATH . 'admin/list-members.php');
require_once(CCGN_PATH . 'admin/list-vouchers.php');
require_once(CCGN_PATH . 'admin/application-email-log.php');

// Vouching UI for existing members to vouch for new applicant

require_once(CCGN_PATH . 'public/vouching-form-shortcode.php');

// Cron

require_once CCGN_PATH . 'cron/email-vouch-request-reminders.php';
require_once CCGN_PATH . 'cron/email-update-vouchers-reminders.php';
require_once CCGN_PATH . 'cron/email-update-details-reminders.php';


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

add_action( 'bp_profile_header_meta', 'ccgn_username_display' );

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
//We're going to avoid this filter cause it crashes with the "public only" rendering of institutional profiles
//add_action( 'bp_setup_nav', 'ccgn_not_logged_in_ui', 150 );

//add_filter( 'bp_xprofile_get_groups', 'ccgn_filter_role_groups', 999, 2 );
add_filter( 'bp_after_has_profile_parse_args', '_bp_hide_profile_field_group' );

add_action( 'bp_core_setup_globals', '_bp_set_default_component' );

//add_action( 'bp_profile_header_meta', '_bp_meta_member_type', 10, 0 );

// Remove unused modules

add_filter( 'bp_is_active', '_bp_remove_components', 10, 2 );

// Hide group/member directories etc. from users who are not logged in
//add_filter( 'get_header', '_bp_not_signed_in_redirect', 1 );

// Don't show unvouched users in member directory, do list alphabetically
add_action( 'bp_ajax_querystring', 'ccgn_bp_directory_query', 20, 2 );

////////////////////////////////////////////////////////////////////////////////
// Registration Forms
////////////////////////////////////////////////////////////////////////////////

// Make sure the applicant selects all the checkboxes

add_action( 'gform_validation', 'ccgn_agree_to_terms_validate' );

// Populate voucher selects

add_action( 'gform_pre_render', 'ccgn_set_vouchers_options' );
add_action( 'gform_pre_render', 'ccgn_set_vouchers_changeable' );

add_action( "gform_pre_submission", "ccgn_choose_vouchers_pre_submission" );

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
//Manully doing a strict sanitize on wordpress username
//This filter was added to avoid wordpress strip '+' on username email alias
function ccgn_sanitize_user_not_strict($user, $raw_user, $strict)
{
    if ($strict) {
        $user = sanitize_user($raw_user, false);
        $user = preg_replace('|[^a-z0-9 _.+\-@]|i', '', $user);
    }
    return $user;
}
add_filter('sanitize_user', 'ccgn_sanitize_user_not_strict', 10, 3);
////////////////////////////////////////////////////////////////////////////////
// Admin forms
////////////////////////////////////////////////////////////////////////////////

add_filter(
    'gform_validation',
    'ccgn_application_change_vouchers_validate'
);

add_action(
    'gform_after_submission',
    'ccgn_application_change_vouchers_after_submission',
    10,
    2
);

////////////////////////////////////////////////////////////////////////////////
// Forms for existing members
////////////////////////////////////////////////////////////////////////////////

// The shortcode to display the vouching form.

add_shortcode(
    'ccgn-vouching-form',
    'ccgn_vouching_shortcode_render'
);

// The shortcode for searching for members by chapter interest

add_shortcode(
    'ccgn-member-chapter-interest-search',
    'ccgn_member_search_chapter_interest_shortcode_render'
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
    add_action( 'admin_menu', 'ccgn_application_final_approval_menu' );
    add_action( 'admin_menu', 'ccgn_application_users_menu' );
    add_action( 'admin_menu', 'ccgn_hide_application_users_menu', 999 );
    add_action( 'admin_menu', 'ccgn_settings_emails_register' );
    add_action( 'admin_menu', 'ccgn_application_pre_approval_menu' );
    add_action( 'admin_menu', 'ccgn_application_legal_approval_menu' );
    add_action( 'admin_menu', 'ccgn_application_list_members_menu' );
    add_action( 'admin_menu', 'ccgn_application_list_vouchers_menu' );
    add_action( 'admin_menu', 'ccgn_application_change_vouchers_menu' );
    add_action( 'admin_menu', 'ccgn_application_email_log_menu' );
    add_filter( 'user_row_actions', 'ccgn_application_user_link', 10, 2 );
    add_filter( 'user_row_actions', 'ccgn_application_vouches_link', 10, 2 );
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

add_filter('bp_core_fetch_avatar_no_grav', '__return_true');

////////////////////////////////////////////////////////////////////////////////
// Cron
////////////////////////////////////////////////////////////////////////////////

add_action(
    'ccgn_cleanup_old_records_event',
    'ccgn_cleanup_old_records'
);
register_activation_hook( __FILE__, 'ccgn_schedule_cleanup' );
register_deactivation_hook( __FILE__, 'ccgn_schedule_remove_cleanup' );

add_action(
    'ccgn_email_vouch_request_reminders_event',
    'ccgn_email_vouch_request_reminders'
);
register_activation_hook(
    __FILE__,
    'ccgn_schedule_email_vouch_request_reminders'
);
register_deactivation_hook(
    __FILE__,
    'ccgn_schedule_remove_email_vouch_request_reminders'
);

add_action(
    'ccgn_email_update_vouchers_reminders_event',
    'ccgn_email_update_vouchers_reminders'
);
register_activation_hook(
    __FILE__,
    'ccgn_schedule_email_upate_vouchers_reminders'
);
register_deactivation_hook(
    __FILE__,
    'ccgn_schedule_remove_email_update_vouchers_reminders'
);
add_action(
    'ccgn_email_update_details_reminders_event',
    'ccgn_email_update_details_reminders'
);
register_activation_hook(
    __FILE__,
    'ccgn_schedule_email_upate_details_reminders'
);
register_deactivation_hook(
    __FILE__,
    'ccgn_schedule_remove_email_update_details_reminders'
);

////////////////////////////////////////////////////////////////////////////////
// Admin script && styles
////////////////////////////////////////////////////////////////////////////////

add_action('admin_enqueue_scripts', 'ccgn_admin_enqueue_scripts');
function ccgn_admin_enqueue_scripts($hook_suffix) {
    global $pagenow;
    $style_version = '2.0.3';
    $script_version = '2.0.2';
    if (is_admin() && strstr($hook_suffix, 'global-network') ) {
        wp_enqueue_style('datatables-style', CCGN_URL_PATH . 'admin/assets/css/datatables.css');
        wp_enqueue_style('admin-style', CCGN_URL_PATH . 'admin/assets/css/admin_styles.css',array(),$style_version);
        wp_enqueue_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('datatables', CCGN_URL_PATH . 'admin/assets/js/datatables.min.js', array('jquery'), '', '');
        wp_enqueue_script('ccgn-script', CCGN_URL_PATH . 'admin/assets/js/script.js', array('jquery'), $script_version, '');
        wp_localize_script('ccgn-script', 'wpApiSettings', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
            'current_user' => get_current_user_id(),
            'site_epoch' => CCGN_SITE_EPOCH,
            'date_now' => date("Y-m-d"),
            'ajax_url' => admin_url('admin-ajax.php'),
            'is_sub_admin' => (ccgn_current_user_is_sub_admin()) ? 'yes' : 'no'
        ));
    }
}
/**
 * Register new wp-api endpoints 
 */
function register_commoners_endpoints($uri, $callback, $method='GET')
{
    $args = ['uri' => $uri, 'callback' => $callback, 'method' => $method];
    add_action('rest_api_init', function () use ($args) {
        register_rest_route('commoners/v2', $args['uri'],
            array(
                'methods'  => $args['method'],
                'callback' => $args['callback']
            )
        );
    });
}