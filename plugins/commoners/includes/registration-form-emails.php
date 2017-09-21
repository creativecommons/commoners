<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

function commoners_registration_email_sub($key, $value, $text) {
    return string_replace(
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
        $voucher,
        $result
    );
    return $result;
}

function commoners_registration_email( $applicant_name, $applicant_id,
                                       $voucher_name, $to_address,
                                       $subject, $message ) {
    $subject_substituted = commoners_registration_email_sub_names(
        $applicant,
        $applicant_id,
        $voucher,
        $subject
    );
    $message_substituted = commoners_registration_email_sub_names(
        $applicant,
        $applicant_id,
        $voucher,
        $message
    );
    wp_mail( $to_address, $subject_substituted, $message_substituted );
}

function commoners_registration_email_to_applicant ( $applicant,
                                                     $email_option ) {
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];
    commoners_registration_email(
        $applicant->user_nicename,
        $applicant->ID,
        '',
        $applicant->email,
        $subject,
        $message
    );
}

function commoners_registration_email_to_voucher ( $applicant,
                                                   $voucher,
                                                   $email_option ) {
    $options = get_option( $email_option );
    $subject = $options[ 'subject' ];
    $message = $options[ 'message' ];

    commoners_registration_email(
        $user->user_nicename,
        $applicant->ID,
        $voucher->user_nicename,
        $voucher->email,
        $subject,
        $message
    );
}

function commoners_registration_email_application_received ( $applicant ) {
    commoners_registration_email_to_applicant(
        $applicant,
        'commoners-email-received'
    );
}

function commoners_registration_email_application_approved ( $applicant ) {
    commoners_registration_email_to_applicant(
        $applicant,
        'commoners-email-approved'
    );
}

function commoners_registration_email_application_declined ( $applicant ) {
    commoners_registration_email_to_applicant(
        $applicant,
        'commoners-email-declined'
    );
}

function commoners_registration_email_vouching_request ( $applicant,
                                                         $voucher_id ) {
    commoners_registration_email_to_voucher(
        $applicant,
        $voucher_id,
        'commoners-email-vouch-request'
    );
}
