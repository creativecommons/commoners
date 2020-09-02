<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ccgn_list_render_individual_applicants ( $members ) {
    $emails = [];
    foreach ( $members as $member ) {
        $member_id = $member [ CCGN_GF_FINAL_APPROVAL_APPLICANT_ID ];
        $user = get_user_by ( 'ID', $member_id );
        if ( ccgn_member_is_individual( $member_id ) ) {
            $emails[] = $user->user_email;
            echo '<tr><td>'
                . $user->display_name
                . '</td><td>'
                . $user->user_email
                . '</td><td>'
                . bp_get_profile_field_data(
                    'field=Location&user_id=' . $member_id
                )
                . '</td><td>'
                . bp_get_profile_field_data(
                    'field=Preferred%20Country%20Chapter&user_id=' . $member_id
                )
                . '</td><td>'
                . join( ', ',
                        bp_get_profile_field_data(
                            'field=Areas%20of%20Interest&user_id=' . $member_id
                        )
                )
                . '</td><td>'
                . ccgn_application_format_vouches_yes ( $member_id )
                . '</td><td>'
                . $member[ 'date_created' ]
                . '</td></tr>';
        }
    }
    return $emails;
}

function ccgn_application_format_vouches_yes ( $applicant_id ) {
    $vouchers = [];
    foreach ( ccgn_application_vouches ( $applicant_id ) as $vouch ) {
        if (
            $vouch[ CCGN_GF_VOUCH_DO_YOU_VOUCH ]
            == CCGN_GF_VOUCH_DO_YOU_VOUCH_YES
        ) {
            $voucher_id = $vouch[ 'created_by' ];
            $vouchers[] = bp_core_get_userlink( $voucher_id );
            $user = get_userdata( $voucher_id );
        }
    }
    return join( ', ', $vouchers );
}

function ccgn_list_render_institutional_applicants ( $members ) {
    $emails = [];
    foreach ( $members as $member ) {
        $member_id = $member [ CCGN_GF_LEGAL_APPROVAL_APPLICANT_ID ];
        $user = get_user_by ( 'ID', $member_id );
        if ( ccgn_member_is_institution( $member_id ) ) {
            $contact_name_to_use = bp_get_profile_field_data(
                'field=Representative&user_id=' . $member_id
            );
            //FIXME: Keep the email from the application form!!!
            $contact_email_to_use = $user->user_email;
            $emails[] = $contact_email_to_use;
            echo '<tr><td>'
                . $user->display_name
                . '</td><td>'
                . $contact_name_to_use
                . '</td><td>'
                . $contact_email_to_use
                . '</td><td>'
                . $member[ 'date_created' ]
                . '</td></tr>';
        }
    }
    return $emails;
}

function ccgn_report_member_country_count() {
    $countries = array();
    $member_ids = ccgn_members_individual_ids();
    foreach ( $member_ids as $member_id ) {
        $country = bp_get_profile_field_data(
            'field=Location&user_id=' . $member_id
        );
        if (! isset($countries[$country]) ) {
            $countries[$country] = 0;
        }
        $countries[$country] += 1;
    }
    echo '<h1>Total Individual Members by Country (All-time)</h1>';
    echo '<div class="ccgn-table-container">';
        echo '<table id="ccgn-members-by-country">';
            echo '<thead>';
                echo '<tr>';
                    echo '<td><strong>Country</strong></td>';
                    echo '<td><strong>Count</strong></td>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
}

function ccgn_list_recent_members ( $start_date, $end_date ) {
    $date_spec_string = '';
    if ( $start_date || $end_date ) {
        $date_spec_string = " ( $start_date &mdash; $end_date )";
    }
    //$individuals = ccgn_new_final_approvals_since ( $start_date, $end_date );
    //if ( $individuals ) {
?>
    <h2>New Members</h2>
    <div class="custom-filters">
        <div class="member-type">
            <h4 class="filter-title">Filters</h4>
            <label for="member_type" class="inline-label">
                Member type
                <select name="member_type" id="member_type">
                    <option value="">Both</option>
                    <option value="Individual">Individual</option>
                    <option value="Institution">Institution</option>
                </select>
            </label>
            <label class="inline-label"><a href="#TB_inline?width=600&height=550&inlineId=emails-modal" class="thickbox email-list button button-primary">View Emails</a></label>
            <label for="date-start" class="inline-label with-description">
                <input type="text" class="ui-datepicker-input" name="date-start" id="date-start" placeholder="Start date">
                <small class="description">(Leave blank for since registration began)</small>
            </label>
            <label for="date-end" class="inline-label with-description">
                <input type="text" class="ui-datepicker-input" name="date-end" id="date-end" placeholder="End date">
                <small class="description">(Leave blank for since registration began)</small>
            </label>
        </div>
    </div>
    <div class="ccgn-table-container">

        <table id="ccgn-list-new-individuals" class="tablesorter">
        <thead align="left">
            <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Location</th>
            <th><span style="white-space: nowrap">Chapter of Interest</span></th>
            <th><span style="white-space: nowrap">Aread of Interest</span></th>
            <th>Vouchers</th>
            <th><span style="white-space: nowrap">Final Approval Date</span></th>
            </tr>
        </thead>
        <tbody>
<?php
        //echo bp_profile_field_data('field=Location&user_id=5811');
        //echo xprofile_get_field_data(array('field' => 'Location', 'user_id' => 5811));
        
    //    $individual_emails = ccgn_list_render_individual_applicants (
    //        $individuals
    //    );
?>
        </tbody>
        </table>
    </div>
    <?php add_thickbox(); ?>
    <div id="emails-modal" style="display:none;">
        <p>
            Emails list
        </p>
    </div>
<?php
    //}
    
    ccgn_report_member_country_count();
}
//Get application stats
//we use WP transient because we don't wanna stress the server
function ccgn_application_stats() {
    delete_transient('ccgn_application_stats');
    if (false === ($application_stats = get_transient('ccgn_application_stats'))) {
        $individuals = ccgn_new_final_approvals_since('', '');
        $application_stats = array();
        $individual_members = 0;
        $institutional_members = 0;
        $total = 0;
        foreach ($individuals as $entry) {
            $member_id = $entry[CCGN_GF_FINAL_APPROVAL_APPLICANT_ID];
            $member_type = ccgn_applicant_type_desc($member_id);
            if ($member_type == 'Individual') { $individual_members++; }
            if ($member_type == 'Institution') { $institutional_members++; }
            $total++;
        }
        $application_stats['individual'] = $individual_members;
        $application_stats['institutional'] = $institutional_members;
        $application_stats['total'] = $total;
        set_transient( 'ccgn_application_stats', $application_stats, 6*60*60 );
    }
    return $application_stats;
}
function ccgn_list_members_stats() {
    echo '<h2>Members stats</h2>';
    if (ccgn_current_user_is_final_approver() || ccgn_current_user_is_sub_admin()):
        $stats = ccgn_application_stats();
        echo '<div class="stats-columns">';
            echo '<div class="stats-box">';
                echo '<h4 class="stats-title">Total members</h4>';
                echo '<span class="stats-number">'.$stats['total'].'</span>';
            echo '</div>';
            echo '<div class="stats-box">';
                echo '<h4 class="stats-title">Individual members</h4>';
                echo '<span class="stats-number">'.$stats['individual'].'</span>';
            echo '</div>';
            echo '<div class="stats-box">';
                echo '<h4 class="stats-title">Institutional members</h4>';
                echo '<span class="stats-number">'.$stats['institutional'].'</span>';
            echo '</div>';
        echo '</div>';
    else: 
        echo '<p>You\'re not allowed to see this</p>';
    endif;
}
function ccgn_list_and_search_members() {
    echo '<h2>Search Members</h2>';
    echo '<div id="alert-messages"></div>';
    echo '<div class="custom-filters">';
        echo '<div class="member-type">';
            echo '<h4 class="filter-title">Search for</h4>';
            echo '<label for="user_id" class="inline-label">';
                echo '<input type="text" id="user_id" name="user_id" placeholder="User email, username or ID">';
            echo '</label>';
            echo '<label class="inline-label"><a href="#" class="button button-primary" id="search-all-members">Search Member</a></label>';
        echo '</div>';
    echo '</div>';
    echo '<div class="search-results ccgn-table-container" id="search-results">';
        echo '<table id="ccgn-search-users">';
            echo '<thead>';
                echo '<tr>';
                    echo '<td><strong>ID</strong></td>';
                    echo '<td><strong>Name</strong></td>';
                    echo '<td><strong>Mail</strong></td>';
                    echo '<td><strong>Roles</strong></td>';
                    echo '<td><strong>User since</strong></td>';
                    echo '<td><strong>Application Status</strong></td>';
                    echo '<td><strong>Status last update</strong></td>';
                    echo '<td><strong>Actions</strong></td>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
     add_thickbox();
    echo '<div id="resetvouchers-modal" style="display:none;">';
        echo '<h2>You are about to reset the vouching state of an user</h2>';
        echo '<p>Are you sure you want to do this? with this action you will:</p>';
        echo '<ul>';
            echo '<li>- Roll back an application that was automatically closed</li>';
            echo '<li>- Notify the applicant by mail</li>';
        echo '</ul>';
        echo '<br><br>';
        echo  wp_nonce_field('reset_vouchers', 'reset_vouchers_nonce', true, false);
        echo '<div class="buttons">';
            echo '<button id="close-reset-vouchers" class="button close-window">Close</button> ';
            echo " <button id=\"reset-vouchers-for-sure\" class=\"button button-primary reset-vouchers-for-sure\">Yes, I'm sure</button>";
        echo '</div>';
        echo '</p>';
    echo '</div>';
}
function ccgn_ajax_reset_vouchers() {
    $user_id = $_POST['user_id'];
    if ( check_ajax_referer('reset_vouchers', 'sec') && (!empty($user_id) ) ) {
        $reset_vouchers = ccgn_reopen_application_auto_closed_because_cannots($user_id);
        if ($reset_vouchers) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }
    exit(0);
}
add_action('wp_ajax_nopriv_reset_vouchers', 'ccgn_ajax_reset_vouchers');
add_action('wp_ajax_reset_vouchers', 'ccgn_ajax_reset_vouchers');

function ccgn_list_members_admin_page () {
?>
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<?php
    if ( isset( $_GET['start_date'] ) ) {
        $start_date = filter_var(
            $_GET[ 'start_date' ],
            FILTER_SANITIZE_STRING
        );
    } else {
        $start_date = date( 'Y-m-d', strtotime( '-1 week' ) );
    }
    if ( isset( $_GET['end_date'] ) ) {
        $end_date = filter_var(
            $_GET[ 'end_date' ],
            FILTER_SANITIZE_STRING
        );
    } else {
        $end_date = date( 'Y-m-d', time() );
    }
        $active_tab = isset($_GET ['tab']) ? $_GET ['tab'] : 'individual-members';
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=global-network-list-users&tab=individual-members" class="nav-tab <?php echo $active_tab == 'individual-members' ? 'nav-tab-active' : ''; ?>">New Members</a>
        <a href="?page=global-network-list-users&tab=search-members" class="nav-tab <?php echo $active_tab == 'search-members' ? 'nav-tab-active' : ''; ?>">Search Members</a>
        <?php if (ccgn_current_user_is_final_approver() || ccgn_current_user_is_sub_admin()) : ?>
            <a href="?page=global-network-list-users&tab=stats" class="nav-tab <?php echo $active_tab == 'stats' ? 'nav-tab-active' : ''; ?>">Stats</a>
        <?php endif; ?>
    </h2>
    <?php 
    if ($active_tab == 'individual-members') {
        ccgn_list_recent_members( $start_date, $end_date );
    }
    if ($active_tab == 'search-members') {
        ccgn_list_and_search_members();
    }
    if ($active_tab == 'stats') {
        ccgn_list_members_stats();
    }
}

////////////////////////////////////////////////////////////////////////////////
// Admin UI hooks
////////////////////////////////////////////////////////////////////////////////

// This shouldn't really have "application" in it but it would look out of place

function ccgn_application_list_members_menu () {
    add_submenu_page(
        'global-network-application-approval',
        'List Members',
        'List Members',
        'ccgn_sub_admin_view',
        'global-network-list-users',
        'ccgn_list_members_admin_page'
    );
}

/**
 * Register endpoints to use data
 */
register_commoners_endpoints('/list-members', 'ccgn_rest_return_members', 'POST');

function ccgn_rest_return_members()
{
    $current_user = (isset($_POST['current_user'])) ? esc_attr($_POST['current_user']) : 0;
    $the_user = new WP_USER($current_user);
    $start_date = (isset($_POST['start_date'])) ? $start_date : '';
    $end_date = (isset($_POST['end_date'])) ? $end_date : '';
    $return_data = array();
    if (rest_cookie_check_errors() && $the_user->has_cap('ccgn_list_applications')) {
        $default =  array(
			'subscriber' => 'subscriber',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => 'ccgn-application-state',
                    'value' => 'accepted'
                )
            )
		);
        $query = new WP_User_Query($default);
        $individuals = $query->get_results();
        //$individuals = ccgn_new_final_approvals_since($start_date, $end_date);
        $final_approval_form_id = RGFormsModel::get_form_id(CCGN_GF_FINAL_APPROVAL);
        foreach ($individuals as $member) {
            $user = $member;
            $member_id = $user->data->ID;
            $search_criteria = array(
                'field_filters' => array(
                    array(
                        'key' => CCGN_GF_FINAL_APPROVAL_APPLICANT_ID,
                        'value' => $member_id
                    ),
                )
            );
            $approval_entry = GFAPI::get_entries( $final_approval_form_id, $search_criteria );
            $approval_date = (!empty($approval_entry[0]['date_created'])) ? $approval_entry[0]['date_created'] : CCGN_SITE_EPOCH ;
            $user_data = array();
            $member_last_date = get_user_meta( $member_id, CCGN_APPLICATION_STATE_DATE, true);
            $user_data['user_id'] = $member_id;
            $user_data['user_type'] = ccgn_applicant_type_desc($member_id);
            $user_data['user_url'] = ccgn_application_user_application_page_url($user->data->ID);
            $user_data['display_name'] = $user->display_name;
            $user_data['user_email'] = $user->user_email;
            $user_data['location'] = bp_get_profile_field_data( 'field=Location&user_id=' . $member_id );
            $user_data['location_chapter'] = bp_get_profile_field_data( 'field=Preferred%20Country%20Chapter&user_id=' . $member_id );
            $user_data['member_interests'] = join( ', ', bp_get_profile_field_data( 'field=Areas%20of%20Interest&user_id=' . $member_id ) );
            $user_data['member_vouchers'] = ccgn_application_format_vouches_yes($member_id);
            $user_data['member_approval_date'] = date('Y-m-d', strtotime($approval_date));

            $return_data['data'][] = $user_data;
        }
        return $return_data;
        
    } else {
        return new WP_Error('Forbidden', "You don't have access to request this data", array('status' => 403));
    }
}

register_commoners_endpoints('/list-members/by-country', 'ccgn_rest_return_members_by_country', 'POST');

function ccgn_rest_return_members_by_country()
{
    $current_user = (isset($_POST['current_user'])) ? esc_attr($_POST['current_user']) : 0;
    $the_user = new WP_USER($current_user);
    $return_data = array();
    $countries = array();
    
    if (rest_cookie_check_errors() && $the_user->has_cap('ccgn_list_applications')) {
        $member_ids = ccgn_members_individual_ids();
        foreach ($member_ids as $member_id) {
            $country = bp_get_profile_field_data(
                'field=Location&user_id=' . $member_id
            );
            if (!isset($countries[$country])) {
                $countries[$country] = 0;
            }
            $countries[$country] += 1;
        }
        foreach ($countries as $country => $count) {
            $user_data = array();
            $country_name = (!empty($country)) ? $country : '<strong>No country</strong>';
            $user_data['country'] = $country_name;
            $user_data['country_count'] = $count;
            
            $return_data['data'][] = $user_data;
        }
        return $return_data;

    } else {
        return new WP_Error('Forbidden', "You don't have access to request this data", array('status' => 403));
    }
}
register_commoners_endpoints('/list-members/by-id', 'ccgn_rest_return_members_by_id', 'POST');

function ccgn_rest_return_members_by_id()
{
    $current_user = (isset($_POST['current_user'])) ? esc_attr($_POST['current_user']) : 0;
    $the_user = new WP_USER($current_user);
    $search_user = (isset($_POST['search_user'])) ? esc_attr($_POST['search_user']) : 0;
    $return_data = array();

    if (rest_cookie_check_errors() && $the_user->has_cap('ccgn_list_applications')) {
        $params = array(
            'search' => $search_user
        );
        $users = get_users($params);
        foreach ($users as $user) {
            $user_data = array();
            $user_data['ID'] = $user->data->ID;
            $user_data['user_name'] = $user->data->display_name;
            $user_data['user_url'] = ccgn_application_user_application_page_url($user->data->ID);
            $user_data['user_mail'] = $user->data->user_email;
            $user_data['user_register_date'] = date('Y-m-d',strtotime($user->data->user_registered));
            $user_data['user_roles']  = join(', ', $user->roles);
            $user_data['user_application_status'] = get_user_meta($user->data->ID, 'ccgn-application-state', true);
            $user_data['user_status_update'] = date('Y-m-d',strtotime(get_user_meta($user->data->ID, 'ccgn-application-state-date', true)));

            $return_data[] = $user_data;
        }

        return $return_data;

    } else {
        return new WP_Error('Forbidden', "You don't have access to request this data", array('status' => 403));
    }
}