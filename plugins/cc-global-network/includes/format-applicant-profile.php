<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Get applicant details from request (if provided)
////////////////////////////////////////////////////////////////////////////////

function ccgn_request_applicant_id () {
    $applicant_id = filter_input(
        INPUT_GET,
        'user_id',
        FILTER_VALIDATE_INT
    );
    if ( $applicant_id === false ) {
        echo _( '<br />Invalid user id.' );
    } elseif ( $applicant_id === null ) {
        echo _( '<br />No user id specified.' );
        $applicant_id = false;
    } elseif ( $applicant_id == get_current_user_id() ) {
        echo _( '<br />You cannot edit your own application status' );
        $applicant_id = false;
    } else {
        $applicant = get_user_by( 'ID', $applicant_id );
        if( $applicant === false ) {
            echo _( '<br />Invalid user specified.' );
            $applicant_id = false;
            //FIXME: Check if really autovouched, check if not and should be
        } elseif ( ccgn_user_is_autovouched( $applicant_id ) ) {
            echo '<br><h4><i>User was autovouched, no application details.</i></h4>';
            $applicant_id = false;
        }
    }
    return $applicant_id;
}

////////////////////////////////////////////////////////////////////////////////
// The details the user provided.
////////////////////////////////////////////////////////////////////////////////

// Strip tags from the string and translate newlines to html breaks.

function ccgn_vp_clean_string( $string ) {
    //FIXME: If it's an array, format as an ul
    return str_replace(
        "\r\n",
        '<br />',
        filter_var( $string, FILTER_SANITIZE_STRING )
    );
}

function ccgn_field_values ( $entry, $id ) {
    // Get an array of the keys that match "$id" or "$id.1" but not "$id1"
    $keys = array_values(
        preg_grep(
            "/^($id|$id\.\d+)$/",
            array_keys(
                $entry
            )
        )
    );
    $results = array_values(
        array_intersect_key(
            $entry,
            array_flip(
                $keys
            )
        )
    );
    // This will be formatted to <br> by ccgn_vp_clean_string, which strips tags
    return implode( "\r\n", $results ? $results : [] );
}

// Format up a field from the Applicant Details.

function ccgn_vp_format_field ( $entry, $item ) {
    $html = '';
    $value = ccgn_vp_clean_string(
        ccgn_field_values(
            $entry,
            $item[ 1 ]
        )
    );
    // Make sure the entry has a value for this item
    if( $value ) {
        $html = '<tr>'
              . '<td class="title">'. $item[ 0 ] . '</td>'
              . '<td>'.$value.'</td>'
              . '</p>';
    }
    return $html;
}

// Format the avatar image from the Applicant Details as an html IMG tag.

function ccgn_vp_format_avatar ( $entry ) {
    $img = '';
    $user_id = $entry[ 'created_by' ];
    if ( ccgn_applicant_gravatar_selected ( $user_id ) ) {
        $img = ccgn_user_gravatar_img ( $user_id, 300 );
    } else {
        // If this has been removed
        if ( ! isset( $entry[ CCGN_GF_DETAILS_AVATAR_FILE ] ) ) {
            //FIXME: get profile image url
            $img = '';
        } else {
            $img_url = $entry[ CCGN_GF_DETAILS_AVATAR_FILE ];
            $img = '<strong>Applicant Image</strong><p><img style="max-height:300px; width:auto;" src="' . $img_url . '"></p>';
        }
    }
    return $img;
}

// Format the relevant fields from the Applicant Details form as html.

function ccgn_vouching_form_profile_format( $entry, $map, $applicant_id ) {
    $is_individual = ccgn_user_is_individual_applicant($entry['created_by']);
    $statement = ($is_individual) ? $entry[3] : $entry[6];
    $bio = $entry[2];
    $html = '';
    if (!is_admin()){
        $the_user = get_user_by('ID', $applicant_id);
        $html .= '<h1 class="applicant-title">'.$the_user->display_name.'</h1>';
    }
    $html .= '<div class="ccgn-vouching-profile">';
    $html .= '<table class="preview-details">';
        $html .= '<tr>';
            $html .= '<td>';
            $html .= '<h6>Membership Statement</h6>';
            $html .= ccgn_vp_clean_string($statement);
            $html .= '</td>';
            if ($is_individual):
            $html .=  '<td>';
                $html .= '<h6>Brief Biography</h6>';
                $html .= ccgn_vp_clean_string($bio);
            $html .= '</td>';
            endif;
        $html .= '</tr>';
    $html .= '</table>';
    $html .= '<a href="#" class="display-details" data-target="#ccgn-profile-table">View more applicant details  <span class="dashicons dashicons-arrow-down-alt2"></span></a>';
    $html .= '<table class="ccgn-profile" id="ccgn-profile-table">';
    if (is_admin()) {
        if ($is_individual) {
            unset($map[2]);
            unset($map[3]);
        } else {
            unset($map[4]);
        }
    } else {
        if ($is_individual) {
            unset($map[0]);
            unset($map[1]);
        } else {
            unset($map[2]);
        }
    }
    unset ($statement);
    unset ($bio);
    foreach( $map as $item ) {
         $html .= ccgn_vp_format_field( $entry, $item );
    }
    $html .= '</table>';
    $html .= '</div>';
    return $html;
}

// Get the applicant's (latest) Applicant Details form and return them
// formatted as html.

function ccgn_vouching_form_individual_profile_text ( $applicant_id ) {
    $entry = ccgn_details_individual_form_entry( $applicant_id );
    return '<h3>Individual Applicant</h3>'
        //. ccgn_vp_format_avatar( $entry )
        . ccgn_vouching_form_profile_format(
            $entry,
            CCGN_GF_DETAILS_VOUCH_MAP,
            $applicant_id
        );
}

function ccgn_vouching_form_institution_profile_text ( $applicant_id ) {
    return '<h3>Institutional Applicant</h3>'
        . ccgn_vouching_form_profile_format(
            ccgn_details_institution_form_entry ( $applicant_id ),
            CCGN_GF_INSTITUTION_DETAILS_VOUCH_MAP,
            $applicant_id
        );
}

function ccgn_vouching_form_applicant_profile_text ( $applicant_id ) {
    if( ccgn_user_is_individual_applicant( $applicant_id ) ) {
        return ccgn_vouching_form_individual_profile_text( $applicant_id );
    } elseif( ccgn_user_is_institutional_applicant( $applicant_id ) ) {
        return ccgn_vouching_form_institution_profile_text(
            $applicant_id
        );
    } else {
        return "<p>Error: newbie.</p>";
    }
}

function ccgn_user_page_individual_profile_text ( $applicant_id ) {
    $entry = ccgn_details_individual_form_entry( $applicant_id );
    
    return //. ccgn_vp_format_avatar ( $entry )
            ccgn_applicant_display_name_formatted ( $applicant_id )
        . ccgn_vouching_form_profile_format(
            $entry,
            CCGN_GF_DETAILS_USER_PAGE_MAP
        );
}

function ccgn_user_page_institution_profile_text ( $applicant_id ) {
    return ccgn_applicant_display_name_formatted ( $applicant_id )
        .ccgn_vouching_form_profile_format(
            ccgn_details_institution_form_entry ( $applicant_id ),
            CCGN_GF_INSTITUTION_DETAILS_USER_PAGE_MAP
        );
}

function ccgn_user_page_applicant_profile_text ( $applicant_id ) {
    if( ccgn_user_is_individual_applicant( $applicant_id ) ) {
        return ccgn_user_page_individual_profile_text( $applicant_id );
    } elseif( ccgn_user_is_institutional_applicant( $applicant_id ) ) {
        return ccgn_user_page_institution_profile_text( $applicant_id );
    } else {
        return "<p>Error: newbie.</p>";
    }
}

function ccgn_applicant_display_name ( $applicant_id ) {
    if ( ccgn_user_is_individual_applicant ( $applicant_id ) ) {
        return get_user_by( 'ID', $applicant_id)->display_name;
    } else {
        return ccgn_institutional_applicant_name ( $applicant_id );
    }
}

function ccgn_applicant_display_name_formatted ( $applicant_id ) {
    if ( ccgn_user_is_individual_applicant ( $applicant_id ) ) {
        return '<p class="title-container with-borders">'
            . '<span class="big-name">' .get_user_by( 'ID', $applicant_id)->display_name. '</span>'
            . '<span class="badge individual">Individual Applicant</span>'
            . '</p>';
    } else {
        return '<p class="title-container with-borders">'
            . '<span class="big-name">' . ccgn_institutional_applicant_name ( $applicant_id ) .'</span>'
            . '<span class="badge institutional">Institutional Applicant</span>'
            . '</p>';
    }
}

function ccgn_get_ajax_public_user_url() {
    $user_id = esc_attr($_POST['user_id']);
    $user_link = bp_core_get_userlink($user_id, false, true);
    echo $user_link;
    exit(0);
}

add_action('wp_ajax_nopriv_get_public_user_url', 'ccgn_get_ajax_public_user_url');
add_action('wp_ajax_get_public_user_url', 'ccgn_get_ajax_public_user_url');

///////////////////////////////////////////////////////////////////////////////
// Log ask for Clarification
///////////////////////////////////////////////////////////////////////////////

define('CCGN_CLARIFICATION_LOG_REG_PROP', 'ccgn-ask-clarification-log');

function ccgn_ask_clarification_log_get()
{
    return get_option(CCGN_CLARIFICATION_LOG_REG_PROP, array());
}
function ccgn_ask_clarification_log_get_id($applicant_id) {
    $log = ccgn_ask_clarification_log_get();
    return $log[$applicant_id];
}
function ccgn_ask_clarification_log_user_get($user_id) {
    $log = ccgn_ask_clarification_log_get();
    $saved_entries = array();
    foreach ($log as $key => $item) {
        foreach ($item as $entry) {
            if ($entry['voucher_id'] == $user_id) {
                $saved_entries[] = $entry;
            }
        }
    }
    return $saved_entries;
}
function ccgn_ask_clarification_log_get_id_ajax()
{
    $applicant_id = esc_attr($_POST['applicant_id']);
    $voucher_id = esc_attr($_POST['voucher_id']);
    $log = ccgn_ask_clarification_log_get();
    $return_log = array();
    foreach ($log[$applicant_id] as $entry) {
        if ($entry['voucher_id'] == $voucher_id) {
            $return_log[] = $entry;
        }
    }
    echo json_encode($return_log);
    exit(0);
}
add_action('wp_ajax_nopriv_ask_voucher_log', 'ccgn_ask_clarification_log_get_id_ajax');
add_action('wp_ajax_ask_voucher_log', 'ccgn_ask_clarification_log_get_id_ajax');
function ccgn_ask_clarification_log_ensure($today)
{
    $option = ccgn_ask_clarification_log_get();
    $days = array_keys($option);
    // Oldest to newest
    sort($days);
    // No entry for today? Insert it
    if ($days[count($days) - 1] != $today) {
        $option[$today] = array();
    }
    // Too many entries? Remove the oldest
    if (count($option) > CCGN_CLARIFICATION_LOG_REG_TRUNCATE) {
        unset($option[$days[0]]);
    }
    return $option;
}

function ccgn_ask_clarification_log_set($log_structure)
{
    update_option(CCGN_CLARIFICATION_LOG_REG_PROP, $log_structure);
}

function ccgn_ask_clarification_log_append(
    $applicant_id,
    $voucher_id
) {
    $today = date('Y-m-d');
    $log = ccgn_ask_clarification_log_get();
    $log[$applicant_id][] = array(
        'applicant_id' => $applicant_id,
        'date' => $today,
        'voucher_id' => $voucher_id,
        'ask_user_id' => get_current_user_id(),
        'applicant_name' => get_user_by('ID', $applicant_id)->display_name,
        'ask_user_name' => get_user_by('ID', get_current_user_id())->display_name,
    );
    ccgn_ask_clarification_log_set($log);
}