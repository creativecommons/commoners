<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

function commoners_registration_email_sub($key, $value, $text) {
    return str_replace(
        "*|${key}|*",
        $value,
        $text
    );
}

function commoners_registration_email_sub_names($applicant_name, $applicant_id,
                                                $voucher_name, $text) {
    $result = commoners_registration_email_sub(
        'APPLICANT_NAME',
        $applicant_name,
        $text
    );
    $result = commoners_registration_email_sub(
        'APPLICANT_ID',
        $applicant_id,
        $result
    );
    $result = commoners_registration_email_sub(
        'VOUCHER_NAME',
        $voucher_name,
        $result
    );
    return $result;
}

function commoners_registration_email( $applicant_name, $applicant_id,
                                       $voucher_name, $to_address,
                                       $subject, $message ) {
    $subject_substituted = commoners_registration_email_sub_names(
        $applicant_name,
        $applicant_id,
        $voucher_name,
        $subject
    );
    $message_substituted = commoners_registration_email_sub_names(
        $applicant_name,
        $applicant_id,
        $voucher_name,
        $message
    );
    $result = wp_mail(
        $to_address,
        $subject_substituted,
        $message_substituted
    );
}

function commoners_registration_email_to_applicant ( $applicant_id,
                                                     $email_option ) {
    $applicant = get_user_by( 'ID', $applicant_id );
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    commoners_registration_email(
        $applicant->user_nicename,
        $applicant->ID,
        '',
        $applicant->user_email,
        $subject,
        $message
    );
}

function commoners_registration_email_to_voucher ( $applicant_id,
                                                   $voucher_id,
                                                   $email_option ) {
    $applicant = get_user_by( 'ID', $applicant_id );
    $voucher = get_user_by( 'ID', $voucher_id );
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    commoners_registration_email(
        $applicant->user_nicename,
        $applicant->ID,
        $voucher->user_nicename,
        $voucher->user_email,
        $subject,
        $message
    );
}

function commoners_registration_email_application_received ( $applicant_id ) {
    commoners_registration_email_to_applicant(
        $applicant_id,
        'commoners-email-received'
    );
}

function commoners_registration_email_application_approved ( $applicant_id ) {
    commoners_registration_email_to_applicant(
        $applicant_id,
        'commoners-email-approved'
    );
}

function commoners_registration_email_application_rejected ( $applicant_id ) {
    commoners_registration_email_to_applicant(
        $applicant_id,
        'commoners-email-rejected'
    );
}

function commoners_registration_email_vouching_request ( $applicant_id,
                                                         $voucher_id ) {
    commoners_registration_email_to_voucher(
        $applicant_id,
        $voucher_id,
        'commoners-email-vouch-request'
    );
}