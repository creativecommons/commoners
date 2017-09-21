<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// The details the user provided.
////////////////////////////////////////////////////////////////////////////////

// These are the field numbers in the Applicant Details form.

$commoners_vp_map = array(
    'name' => 1,
    'bio' => 2,
    'statement' => 3,
    'location' => 7,
    'urls' => 9,
    'avatar' => 11,

);

// Strip tags from the string and translate newlines to html breaks.

function commoners_vp_clean_string( $string ) {
    return str_replace(
        "\r\n",
        '<br />',
        filter_var( $string, FILTER_SANITIZE_STRING )
    );
}

// Format up a field from the Applicant Details.

function commoners_vp_format_field ( $entry, $field_name, $field_id ) {
    global $commoners_vp_map;
    $val = $entry[ $commoners_vp_map[ $field_id ] ];
    return '<strong>'
        . $field_name . '</strong><p>'
        . commoners_vp_clean_string( $val ) . '</p>';
}

// Format the avatar image from the Applicant Details as an html IMG tag.

function commoners_vp_format_avatar ( $entry ) {
    global $commoners_vp_map;
    $img_path = $entry[ $commoners_vp_map[ 'avatar' ] ];
    $img_editor = wp_get_image_editor( $img_path );
    $img_editor->resize( 300, 300, true );
    return '<img src="' . $img_path . '">';
}

// Format the relevant fields from the Applicant Details form as html.

function commoners_vouching_form_profile_format( $entry ) {
    global $commoners_vp_map;
    return '<div class="commoners-vouching-profile">'
        //. commoners_vp_format_avatar ( $entry )
        . commoners_vp_format_field( $entry, 'Applicant Name', 'name' )
        . commoners_vp_format_field( $entry, 'Brief Biography', 'bio' )
        . commoners_vp_format_field(
            $entry,
            'Membership Statement',
            'statement'
        )
        . commoners_vp_format_field( $entry, 'Location', 'location' )
        . commoners_vp_format_field( $entry, 'Social Media / URLs', 'urls' )
        . '</div>';
}

// Get the applicant's (latest) Applicant Details form and return them
// formatted as html.

function commoners_vouching_form_profile_text ( $applicant_id ) {
    $form_id = RGFormsModel::get_form_id( 'Applicant Details' );
    $entries = GFAPI::get_entries(
        $form_id,
        array(
            'created_by' => $applicant_id,
        ),
        array(
            array(
                'key' => 'date',
                'direction' => 'DESC',
                'is_numeric' => false
            )
        )
    );

    return commoners_vouching_form_profile_format( $entries[ 0 ] );
}
