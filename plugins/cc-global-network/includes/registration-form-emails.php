<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Email all the reasons
////////////////////////////////////////////////////////////////////////////////

function ccgn_registration_email_sub($key, $value, $text) {
    return str_replace(
        "*|${key}|*",
        $value,
        $text
    );
}

function ccgn_registration_email_sub_names($applicant_name, $applicant_id,
                                           $voucher_name, $text) {
    $result = ccgn_registration_email_sub(
        'APPLICANT_NAME',
        $applicant_name,
        $text
    );
    $result = ccgn_registration_email_sub(
        'APPLICANT_ID',
        $applicant_id,
        $result
    );
    $result = ccgn_registration_email_sub(
        'VOUCHER_NAME',
        $voucher_name,
        $result
    );
    $result = ccgn_registration_email_sub(
        'SITE_URL',
        get_site_url(),
        $result
    );
    $result = ccgn_registration_email_sub(
        'APPLICANT_PROFILE_URL',
        bp_core_get_userlink($applicant_id, false, true),
        $result
    );
    $applicant_type = 'individual';
    if ( ccgn_user_is_institutional_applicant ( $applicant_id ) ) {
        $applicant_type = 'institution';
    }
    $result = ccgn_registration_email_sub(
        'APPLICATION_FORM_URL',
        get_site_url() . '/sign-up/' . $applicant_type . '/form/',
        $result);
    return $result;
}

function ccgn_registration_email( $applicant_name, $applicant_id,
                                  $voucher_name, $to_address,
                                  $subject, $message ) {
    $subject_substituted = ccgn_registration_email_sub_names(
        $applicant_name,
        $applicant_id,
        $voucher_name,
        $subject
    );
    $message_substituted = ccgn_registration_email_sub_names(
        $applicant_name,
        $applicant_id,
        $voucher_name,
        $message
    );
    add_filter( 'wp_mail_from', 'ccgn_mail_from_address' );
    add_filter( 'wp_mail_from_name', 'ccgn_mail_from_name' );
    add_filter( 'wp_mail_content_type', 'ccgn_html_mail_content_type' );
    wp_mail(
        $to_address,
        $subject_substituted,
        $message_substituted
    );
    remove_filter( 'wp_mail_content_type', 'ccgn_html_mail_content_type' );
    remove_filter( 'wp_mail_from_name', 'ccgn_mail_from_name' );
    remove_filter( 'wp_mail_from', 'ccgn_mail_from_address' );
}

function ccgn_registration_email_to_applicant ( $applicant_id,
                                                $email_option ) {
    $applicant = get_user_by( 'ID', $applicant_id );
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    ccgn_registration_email(
        $applicant->user_nicename,
        $applicant->ID,
        '',
        $applicant->user_email,
        $subject,
        $message
    );
}

function ccgn_registration_email_to_applicant_about_voucher ( $applicant_id,
                                                              $voucher_id,
                                                              $email_option ) {
    $applicant = get_user_by( 'ID', $applicant_id );
    $voucher = get_user_by( 'ID', $voucher_id );
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    ccgn_registration_email(
        $applicant->user_nicename,
        $applicant->ID,
        $voucher->user_nicename,
        $applicant->user_email,
        $subject,
        $message
    );
}

function ccgn_registration_email_to_voucher ( $applicant_id,
                                              $voucher_id,
                                              $email_option ) {
    $applicant = get_user_by( 'ID', $applicant_id );
    $voucher = get_user_by( 'ID', $voucher_id );
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    ccgn_registration_email(
        $applicant->user_nicename,
        $applicant->ID,
        $voucher->user_nicename,
        $voucher->user_email,
        $subject,
        $message
    );
}

function ccgn_registration_email_application_received ( $applicant_id ) {
    ccgn_registration_email_to_applicant(
        $applicant_id,
        'ccgn-email-received'
    );
}

function ccgn_registration_email_application_approved ( $applicant_id ) {
    ccgn_registration_email_to_applicant(
        $applicant_id,
        'ccgn-email-approved'
    );
}

function ccgn_registration_email_application_rejected ( $applicant_id ) {
    ccgn_registration_email_to_applicant(
        $applicant_id,
        'ccgn-email-rejected'
    );
}

function ccgn_registration_email_vouching_request ( $applicant_id,
                                                    $voucher_id ) {
    ccgn_registration_email_to_voucher(
        $applicant_id,
        $voucher_id,
        'ccgn-email-vouch-request'
    );
}

function ccgn_registration_email_vouching_request_reminder ( $voucher_id ) {
    $voucher = get_user_by( 'ID', $voucher_id );
    $options = get_option( 'ccgn-email-vouch-request-reminder' );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    ccgn_registration_email(
        '',
        '',
        $voucher->user_nicename,
        $voucher->user_email,
        $subject,
        $message
    );
}

function ccgn_registration_email_voucher_cannot ( $applicant_id,
                                                  $voucher_id ) {
    ccgn_registration_email_to_applicant_about_voucher(
        $applicant_id,
        $voucher_id,
        'ccgn-email-voucher-cannot'
    );
}


////////////////////////////////////////////////////////////////////////////////
// Use the name and address from our settings for emails
////////////////////////////////////////////////////////////////////////////////

function ccgn_mail_from_address( $old ) {
    $address = get_option( 'ccgn-email-sender' )[ 'address' ];
    if ( ! $address) {
        $address = bloginfo( 'admin_email' );
    }
    return $address;
}

function ccgn_mail_from_name( $old ) {
 $name = get_option( 'ccgn-email-sender' )[ 'name' ];
 if ( ! $name ) {
     // As opposed to 'WordPress'
     // 319: https://developer.wordpress.org/reference/functions/wp_mail/
     $name = 'Creative Commons Global Network';
 }
 return $name;
}

function ccgn_html_mail_content_type () {
    return 'text/html';
}