<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// The details the user provided.
////////////////////////////////////////////////////////////////////////////////

// Strip tags from the string and translate newlines to html breaks.

function commoners_vp_clean_string( $string ) {
    //FIXME: If it's an array, format as an ul
    return str_replace(
        "\r\n",
        '<br />',
        filter_var( $string, FILTER_SANITIZE_STRING )
    );
}

// Format up a field from the Applicant Details.

function commoners_vp_format_field ( $entry, $item ) {
    $html = '';
    // Make sure the entry has a value for this item
    if( isset( $entry[ $item[ 1 ] ] ) ) {
        $html = '<p><strong>'
              . $item[ 0 ] . '</strong><br />'
              . commoners_vp_clean_string( $entry[ $item[ 1 ] ] )
              . '</p>';
    }
    return $html;
}

// Format the avatar image from the Applicant Details as an html IMG tag.

/*function commoners_vp_format_avatar ( $entry ) {
    global $commoners_vp_map;
    $img_path = $entry[ $commoners_vp_map[ 'avatar' ] ];
    $img_editor = wp_get_image_editor( $img_path );
    $img_editor->resize( 300, 300, true );
    return '<img src="' . $img_path . '">';
    }*/

// Format the relevant fields from the Applicant Details form as html.

function commoners_vouching_form_profile_format( $entry, $map ) {
    $html = '<div class="commoners-vouching-profile">';
    foreach( $map as $item ) {
         $html .= commoners_vp_format_field( $entry, $item );
    }
    $html .= '</div>';
    return $html;
}

// Get the applicant's (latest) Applicant Details form and return them
// formatted as html.

function commoners_vouching_form_individual_profile_text ( $applicant_id ) {
    return commoners_vouching_form_profile_format(
        commoners_details_individual_form_entry( $applicant_id ),
        COMMONERS_GF_DETAILS_VOUCH_MAP
    );
}

function commoners_vouching_form_institution_profile_text ( $applicant_id ) {
    return commoners_vouching_form_profile_format(
        commoners_details_institution_form_entry ( $applicant_id ),
        COMMONERS_GF_INSTITUTION_DETAILS_VOUCH_MAP
    );
}

function commoners_vouching_form_applicant_profile_text ( $applicant_id ) {
    if( commoners_user_is_individual_applicant( $applicant_id ) ) {
        return commoners_vouching_form_individual_profile_text( $applicant_id );
    } elseif( commoners_user_is_institution( $applicant_id ) ) {
        return commoners_vouching_form_institution_profile_text(
            $applicant_id
        );
    } else {
        return "<p>Error: newbie.</p>";
    }
}

function commoners_user_page_individual_profile_text ( $applicant_id ) {
    return commoners_vouching_form_profile_format(
        commoners_details_individual_form_entry( $applicant_id ),
        COMMONERS_GF_DETAILS_VOUCH_MAP
    );
}

function commoners_user_page_institution_profile_text ( $applicant_id ) {
    return commoners_vouching_form_profile_format(
        commoners_details_institution_form_entry ( $applicant_id ),
        COMMONERS_GF_INSTITUTION_DETAILS_MAP
    );
}

function commoners_user_page_applicant_profile_text ( $applicant_id ) {
    if( commoners_user_is_individual_applicant( $applicant_id ) ) {
        return commoners_user_page_individual_profile_text( $applicant_id );
    } elseif( commoners_user_is_institutional_applicant( $applicant_id ) ) {
        return commoners_user_page_institution_profile_text( $applicant_id );
    } else {
        return "<p>Error: newbie.</p>";
    }
}
