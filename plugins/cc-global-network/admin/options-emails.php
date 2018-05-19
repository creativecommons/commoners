<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// The email templates that we send to applicants and vouchers
// Beware changing option names, they are also used in:
//   includes/registration-form-emails.php
////////////////////////////////////////////////////////////////////////////////

// FIXME: Less repetition. Much less.

function ccgn_settings_emails_section_callback () {
    ?>
    <?php
}

function ccgn_settings_emails_sender_name () {
    $options = get_option( 'ccgn-email-sender' );
    ?>
    <input type="text" name="ccgn-email-sender[name]"
      class="large-text"
      value="<?php echo $options['name']; ?>" />
    <?php
}

function ccgn_settings_emails_sender_address () {
    $options = get_option( 'ccgn-email-sender' );
    ?>
    <input type="email" name="ccgn-email-sender[address]"
      class="large-text"
      value="<?php echo $options['address']; ?>" />
    <?php
}

function ccgn_settings_emails_legal_address () {
    $options = get_option( 'ccgn-email-legal' );
    ?>
    <input type="email" name="ccgn-email-legal[address]"
      class="large-text"
      value="<?php echo $options['address']; ?>" />
    <?php
}

function ccgn_settings_emails_received_subject () {
    $options = get_option( 'ccgn-email-received' );
    ?>
    <input type="text" name="ccgn-email-received[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_received_message () {
    $options = get_option( 'ccgn-email-received' );
    ?>
    <textarea name="ccgn-email-received[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_vouch_request_subject () {
    $options = get_option( 'ccgn-email-vouch-request' );
    ?>
    <input type="text" name="ccgn-email-vouch-request[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_vouch_request_message () {
    $options = get_option( 'ccgn-email-vouch-request' );
    ?>
    <textarea name="ccgn-email-vouch-request[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_vouch_request_reminder_subject () {
    $options = get_option( 'ccgn-email-vouch-request-reminder' );
    ?>
    <input type="text" name="ccgn-email-vouch-request-reminder[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_vouch_request_reminder_message () {
    $options = get_option( 'ccgn-email-vouch-request-reminder' );
    ?>
    <textarea name="ccgn-email-vouch-request-reminder[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_voucher_cannot_message () {
    $options = get_option( 'ccgn-email-voucher-cannot' );
    ?>
    <textarea name="ccgn-email-voucher-cannot[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_voucher_cannot_subject () {
    $options = get_option( 'ccgn-email-voucher-cannot' );
    ?>
    <input type="text" name="ccgn-email-voucher-cannot[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_voucher_cannot_reminder_message () {
    $options = get_option( 'ccgn-email-voucher-cannot-reminder' );
    ?>
    <textarea name="ccgn-email-voucher-cannot-reminder[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_voucher_cannot_reminder_subject () {
    $options = get_option( 'ccgn-email-voucher-cannot-reminder' );
    ?>
    <input type="text" name="ccgn-email-voucher-cannot-reminder[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_institution_legal_subject () {
    $options = get_option( 'ccgn-email-institution-legal' );
    ?>
    <input type="text" name="ccgn-email-institution-legal[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_institution_legal_message () {
    $options = get_option( 'ccgn-email-institution-legal' );
    ?>
    <textarea name="ccgn-email-institution-legal[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_approved_subject () {
    $options = get_option( 'ccgn-email-approved' );
    ?>
    <input type="text" name="ccgn-email-approved[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_approved_message () {
    $options = get_option( 'ccgn-email-approved' );
    ?>
    <textarea name="ccgn-email-approved[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_rejected_subject () {
    $options = get_option( 'ccgn-email-rejected' );
    ?>
    <input type="text" name="ccgn-email-rejected[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_rejected_message () {
    $options = get_option( 'ccgn-email-rejected' );
    ?>
    <textarea name="ccgn-email-rejected[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_notify_legal_subject () {
    $options = get_option( 'ccgn-email-notify-legal' );
    ?>
    <input type="text" name="ccgn-email-notify-legal[subject]"
      class="large-text"
      value="<?php echo $options['subject']; ?>" />
    <?php
}

function ccgn_settings_emails_notify_legal_message () {
    $options = get_option( 'ccgn-email-notify-legal' );
    ?>
    <textarea name="ccgn-email-notify-legal[message]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['message']; ?></textarea>
    <?php
}

function ccgn_settings_emails_chapter_contact_prefix () {
    $options = get_option( 'ccgn-email-chapter-contact' );
    ?>
    <input type="text" name="ccgn-email-chapter-contact[prefix]"
      class="large-text"
      value="<?php echo $options['prefix']; ?>" />
    <?php
}

function ccgn_settings_emails_chapter_contact_wrapper () {
    $options = get_option( 'ccgn-email-chapter-contact' );
    ?>
    <textarea name="ccgn-email-chapter-contact[wrapper]"
      rows="12" cols="64" class="large-text"
      ><?php echo $options['wrapper']; ?></textarea>
    <?php
}

function ccgn_settings_emails_options_page () {
    add_options_page(
        'Global Network Emails',
        'Global Network Emails',
        'manage_options',
        'global-network-emails',
        'ccgn_settings_emails_render'
    );
}

function ccgn_settings_emails_options_sender () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-sender'
    );

    add_settings_section(
        'ccgn-email-sender',
        'Email Sender',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'sender-name',
        'Display Name',
        'ccgn_settings_emails_sender_name',
        'global-network-emails',
        'ccgn-email-sender'
    );

    add_settings_field(
        'sender-address',
        'Email Address',
        'ccgn_settings_emails_sender_address',
        'global-network-emails',
        'ccgn-email-sender'
    );
}

function ccgn_settings_emails_options_legal_address () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-legal'
    );

    add_settings_section(
        'ccgn-email-legal',
        'CC Legal Team Email',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'sender-address',
        'Email Address',
        'ccgn_settings_emails_legal_address',
        'global-network-emails',
        'ccgn-email-legal'
    );
}

function ccgn_settings_emails_options_received () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-received'
    );

    add_settings_section(
        'ccgn-email-received',
        'Application Received',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_received_subject',
        'global-network-emails',
        'ccgn-email-received'
    );
    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_received_message',
        'global-network-emails',
        'ccgn-email-received'
    );

}

function ccgn_settings_emails_options_vouching () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-vouch-request'
    );

    add_settings_section(
        'ccgn-email-vouch-request',
        'Application Vouch Request',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_vouch_request_subject',
        'global-network-emails',
        'ccgn-email-vouch-request'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_vouch_request_message',
        'global-network-emails',
        'ccgn-email-vouch-request'
    );
}

function ccgn_settings_emails_options_vouching_reminder () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-vouch-request-reminder'
    );

    add_settings_section(
        'ccgn-email-vouch-request-reminder',
        'Application Vouch Request Reminder',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_vouch_request_reminder_subject',
        'global-network-emails',
        'ccgn-email-vouch-request-reminder'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_vouch_request_reminder_message',
        'global-network-emails',
        'ccgn-email-vouch-request-reminder'
    );
}

function ccgn_settings_emails_options_voucher_cannot () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-voucher-cannot'
    );

    add_settings_section(
        'ccgn-email-voucher-cannot',
        'Voucher Declined',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_voucher_cannot_subject',
        'global-network-emails',
        'ccgn-email-voucher-cannot'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_voucher_cannot_message',
        'global-network-emails',
        'ccgn-email-voucher-cannot'
    );
}

function ccgn_settings_emails_options_voucher_cannot_reminder () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-voucher-cannot-reminder'
    );

    add_settings_section(
        'ccgn-email-voucher-cannot-reminder',
        'Voucher Declined Reminder',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_voucher_cannot_reminder_subject',
        'global-network-emails',
        'ccgn-email-voucher-cannot-reminder'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_voucher_cannot_reminder_message',
        'global-network-emails',
        'ccgn-email-voucher-cannot-reminder'
    );
}

function ccgn_settings_emails_options_institution_legal () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-institution-legal'
    );

    add_settings_section(
        'ccgn-email-institution-legal',
        'Inform Institutional Applicant That Legal Will Be In Touch',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_institution_legal_subject',
        'global-network-emails',
        'ccgn-email-institution-legal'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_institution_legal_message',
        'global-network-emails',
        'ccgn-email-institution-legal'
    );
}

function ccgn_settings_emails_options_approved () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-approved'
    );

    add_settings_section(
        'ccgn-email-approved',
        'Application Approved',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_approved_subject',
        'global-network-emails',
        'ccgn-email-approved'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_approved_message',
        'global-network-emails',
        'ccgn-email-approved'
    );
}

function ccgn_settings_emails_options_rejected () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-rejected'
    );

    add_settings_section(
        'ccgn-email-rejected',
        'Application Rejected',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_rejected_subject',
        'global-network-emails',
        'ccgn-email-rejected'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_rejected_message',
        'global-network-emails',
        'ccgn-email-rejected'
    );
}

function ccgn_settings_emails_options_notify_legal () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-notify-legal'
    );

    add_settings_section(
        'ccgn-email-notify-legal',
        'Notify Legal of Institutional Applicant Approval',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject',
        'ccgn_settings_emails_notify_legal_subject',
        'global-network-emails',
        'ccgn-email-notify-legal'
    );

    add_settings_field(
        'registration-message',
        'Message',
        'ccgn_settings_emails_notify_legal_message',
        'global-network-emails',
        'ccgn-email-notify-legal'
    );
}

function ccgn_settings_emails_options_chapter_contact () {
    register_setting(
        'ccgn-emails',
        'ccgn-email-chapter-contact'
    );

    add_settings_section(
        'ccgn-email-chapter-contact',
        'Contact Members Interested In Chapter',
        'ccgn_settings_emails_section_callback',
        'global-network-emails'
    );

    add_settings_field(
        'registration-subject',
        'Subject Prefix',
        'ccgn_settings_emails_chapter_contact_prefix',
        'global-network-emails',
        'ccgn-email-chapter-contact'
    );

    add_settings_field(
        'registration-message',
        'Message Wrapper',
        'ccgn_settings_emails_chapter_contact_wrapper',
        'global-network-emails',
        'ccgn-email-chapter-contact'
    );
}

function ccgn_settings_emails_register () {
    ccgn_settings_emails_options_page();
    ccgn_settings_emails_options_sender();
    ccgn_settings_emails_options_legal_address();
    ccgn_settings_emails_options_received();
    ccgn_settings_emails_options_vouching();
    ccgn_settings_emails_options_vouching_reminder();
    ccgn_settings_emails_options_voucher_cannot();
    ccgn_settings_emails_options_voucher_cannot_reminder();
    ccgn_settings_emails_options_institution_legal();
    ccgn_settings_emails_options_approved();
    ccgn_settings_emails_options_rejected();
    ccgn_settings_emails_options_notify_legal();
    ccgn_settings_emails_options_chapter_contact();
}

function ccgn_settings_emails_print_info () {
    print 'These are the emails that people are sent during application. Only update after discussion with Legal and Comms.';
}

function ccgn_settings_emails_render () {
    ?>
    <div class="wrap">
      <h2>Membership Application Notification Emails</h2>
      <form method="post" action="options.php">
        <?php
          settings_fields( 'ccgn-emails' );
          do_settings_sections( 'global-network-emails' );
          submit_button();
        ?>
      </form>
    </div>
    <p>The following substitutions can be made (where appropriate):
       *|APPLICANT_NAME|* *|VOUCHER_NAME|* *|APPLICANT_ID|*
       *|APPLICANT_PROFILE_URL|* *|SITE_URL|* *|APPLICATION_FORM_URL|* .</p>
    <p><i>Note that SITE_URL does not have a terminating slash!</i></p>
    <?php
}