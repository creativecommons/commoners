<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// The email templates that we send to applicants and vouchers
// Beware changing option names, they are also used in:
//   includes/registration-form-emails.php
////////////////////////////////////////////////////////////////////////////////

function commoners_settings_emails_section_callback () {
    ?>
    <?php
}

function commoners_settings_emails_received_subject () {
    $options = get_option( 'commoners-email-received' );
    ?>
    <input type="text" name="commoners-email-received[subject]"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function commoners_settings_emails_received_message () {
    $options = get_option( 'commoners-email-received' );
    ?>
    <textarea name="commoners-email-received[message]"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function commoners_settings_emails_vouch_request_subject () {
    $options = get_option( 'commoners-email-vouch-request' );
    ?>
    <input type="text" name="commoners-email-vouch-request[subject]"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function commoners_settings_emails_vouch_request_message () {
    $options = get_option( 'commoners-email-vouch-request' );
    ?>
    <textarea name="commoners-email-vouch-request[message]"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function commoners_settings_emails_approved_subject () {
    $options = get_option( 'commoners-email-approved' );
    ?>
    <input type="text" name="commoners-email-approved[subject]"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function commoners_settings_emails_approved_message () {
    $options = get_option( 'commoners-email-approved' );
    ?>
    <textarea name="commoners-email-approved[message]"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function commoners_settings_emails_declined_subject () {
    $options = get_option( 'commoners-email-declined' );
    ?>
    <input type="text" name="commoners-email-declined[subject]"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function commoners_settings_emails_declined_message () {
    $options = get_option( 'commoners-email-declined' );
    ?>
    <textarea name="commoners-email-declined[message]"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function commoners_settings_emails_register () {
    add_options_page(
        'Global Network Emails',
        'Global Network Emails',
        'manage_options',
        'global-network-emails',
        'commoners_settings_emails_render'
    );

    register_setting(
        'commoners-emails',
        'commoners-email-received'
    );
    add_settings_section(
        'commoners-email-received',
        'Application Received',
        'commoners_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        commoners_settings_emails_received_subject,
        'global-network-emails',
        'commoners-email-received'
    );

    add_settings_field(
        'registration-message',
        'Message',
        commoners_settings_emails_received_message,
        'global-network-emails',
        'commoners-email-received'
    );

    register_setting(
        'commoners-emails',
        'commoners-email-vouch-request'
    );
    add_settings_section(
        'commoners-email-vouch-request',
        'Application Vouch Request',
        'commoners_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        commoners_settings_emails_vouch_request_subject,
        'global-network-emails',
        'commoners-email-vouch-request'
    );

    add_settings_field(
        'registration-message',
        'Message',
        commoners_settings_emails_vouch_request_message,
        'global-network-emails',
        'commoners-email-vouch-request'
    );

    register_setting(
        'commoners-emails',
        'commoners-email-approved'
    );

    add_settings_section(
        'commoners-email-approved',
        'Application Approved',
        'commoners_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        commoners_settings_emails_approved_subject,
        'global-network-emails',
        'commoners-email-approved'
    );

    add_settings_field(
        'registration-message',
        'Message',
        commoners_settings_emails_approved_message,
        'global-network-emails',
        'commoners-email-approved'
    );

        register_setting(
        'commoners-emails',
        'commoners-email-declined'
    );

    add_settings_section(
        'commoners-email-declined',
        'Application Declined',
        'commoners_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        commoners_settings_emails_declined_subject,
        'global-network-emails',
        'commoners-email-declined'
    );

    add_settings_field(
        'registration-message',
        'Message',
        commoners_settings_emails_declined_message,
        'global-network-emails',
        'commoners-email-declined'
    );
}

function commoners_settings_emails_print_info () {
    print 'These are the emails that people are sent during application. Only update after discussion with Legal and Comms.';
}

function commoners_settings_emails_render () {
    ?>
    <div class="wrap">
      <h2>Membership Application Notification Emails</h2>
      <form method="post" action="options.php">
        <?php
          settings_fields( 'commoners-emails' );
          do_settings_sections( 'global-network-emails' );
          submit_button();
        ?>
      </form>
    </div>
    <p>The following substitutions can be made (where appropriate):
       *|APPLICANT_NAME|* *|VOUCHER_NAME|* *|APPLICANT_ID|* .</p>
    <?php
}