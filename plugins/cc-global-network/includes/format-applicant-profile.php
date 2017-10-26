<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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
        $html = '<p><strong>'
              . $item[ 0 ] . '</strong><br />'
              . $value
              . '</p>';
    }
    return $html;
}

// Format the avatar image from the Applicant Details as an html IMG tag.

function ccgn_vp_format_avatar ( $entry ) {
    // If this has been removed
    if ( ! isset( $entry[ CCGN_GF_DETAILS_AVATAR_FILE ] ) ) {
        return '';
    }
    $img_url = $entry[ CCGN_GF_DETAILS_AVATAR_FILE ];
    return '<strong>Applicant Image</strong><p><img style="max-height:300px; width:auto;" src="' . $img_url . '"></p>';
}

// Format the relevant fields from the Applicant Details form as html.

function ccgn_vouching_form_profile_format( $entry, $map ) {
    $html = '<div class="ccgn-vouching-profile">';
    foreach( $map as $item ) {
         $html .= ccgn_vp_format_field( $entry, $item );
    }
    $html .= '</div>';
    return $html;
}

// Get the applicant's (latest) Applicant Details form and return them
// formatted as html.

function ccgn_vouching_form_individual_profile_text ( $applicant_id ) {
    $entry = ccgn_details_individual_form_entry( $applicant_id );
    return '<h3>Individual Applicant</h3>'
        . ccgn_vp_format_avatar( $entry )
        . ccgn_vouching_form_profile_format(
            $entry,
            CCGN_GF_DETAILS_VOUCH_MAP
        );
}

function ccgn_vouching_form_institution_profile_text ( $applicant_id ) {
    return '<h3>Institutional Applicant</h3>'
        . ccgn_vouching_form_profile_format(
            ccgn_details_institution_form_entry ( $applicant_id ),
            CCGN_GF_INSTITUTION_DETAILS_VOUCH_MAP
        );
}

function ccgn_vouching_form_applicant_profile_text ( $applicant_id ) {
    if( ccgn_user_is_individual_applicant( $applicant_id ) ) {
        return ccgn_vouching_form_individual_profile_text( $applicant_id );
    } elseif( ccgn_user_is_institution( $applicant_id ) ) {
        return ccgn_vouching_form_institution_profile_text(
            $applicant_id
        );
    } else {
        return "<p>Error: newbie.</p>";
    }
}

function ccgn_user_page_individual_profile_text ( $applicant_id ) {
    $entry = ccgn_details_individual_form_entry( $applicant_id );
    return '<h3>Individual Applicant</h3>'
        . ccgn_vp_format_avatar ( $entry )
        . ccgn_vouching_form_profile_format(
            $entry,
            CCGN_GF_DETAILS_VOUCH_MAP
        );
}

function ccgn_user_page_institution_profile_text ( $applicant_id ) {
    return '<h3>Institutional Applicant</h3>'
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
