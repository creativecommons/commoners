<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

///////////////////////////////////////////////////////////////////////////////
// Allow users to search for members who have expressed an interest in joining
// a particular chapter, and then email them.
///////////////////////////////////////////////////////////////////////////////

// Render the drop down of existing countries that people are interested in

function ccgn_member_search_chapter_interest_dropdown ( $selected=false ) {
    $countries = ccgn_members_chapter_interest_countries ();
    ?><select name="chapter"><?php
    foreach ( $countries as $country ) {
        ?><option value="<?php echo $country; ?>"<?php
            if ($country == $selected) echo ' selected' ?>
             ><?php echo $country; ?></option><?php
    }
    ?></select><?php
}

// Display the search form

function ccgn_send_member_chapter_search_form () {
    $chapter = xprofile_get_field_data( 'Preferred Country Chapter' );
    ?>
    <p>Select a country in the list below to see the Global Network Members
       who have expressed an interest in participating in the Chapter for that
       country.</p>
    <form action="<?php echo wp_get_canonical_url (); ?>" method="post"><?php
    ccgn_member_search_chapter_interest_dropdown ( $chapter );
    ?>
    <input type="submit" name="search" value="Find Members" />
    </form><?php
}

// List member names with profile links

function ccgn_print_members_list ( $members ) {
    $query = array(
        //FIXME: Paginate in future
        'per_page' => 999999,
        'include' => implode(
            ',',
            array_map(
                function( $a ) { return $a->ID; },
                $members
            )
        )
    );
    if (! bp_has_members( $query ) ) {
        return;
    }
    do_action( 'bp_before_directory_members_list' );
    ?><table style="width:100%;">
    <th><tr><td></td><td>Name</td><td>Email</td></tr></th>
    <?php while ( bp_members() ) : bp_the_member(); ?>
    <tr>
      <td>
           <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
      </td>
      <td>
            <a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
      </td>
      <td>
            <a href="mailto:<?php bp_member_user_email(); ?>"><?php bp_member_user_email(); ?></a>
      </td>
    </tr>
    <?php endwhile; ?>
    </table>
    <?php
    do_action( 'bp_after_directory_members_list' );
}

// Display the email form

function ccgn_send_member_chapter_email_form (
    $country,
    $subject,
    $message,
    $agreement
) {
    $users = ccgn_members_interested_in_chapter ( $country );
    ?>
    <h3>Members Interested In Joining The Chapter for
        <b><?php echo $country; ?></b></h3>
    <p><?php ccgn_print_members_list ( $users ); ?></p>
    <h3>Contact Form</h3>
    <p>Additionally, to contact Members directly by email, you can contact the Global Network Members listed above using this form. That will trigger an email sent by this website to you and the rest of the Members listed above.</p>
    <?php
      if ( ($subject || $message || $agreement)
           && ( ! ($subject && $message && $agreement) ) ) {
          echo '<p style="color: red"><b>You must fill out the Subject and Message fields then check the Agreement checkbox before you can click Email Members.</b>';
      }
    ?>
    <form action="<?php echo wp_get_canonical_url (); ?>" method="post">
    <input type="hidden" name="chapter" value="<?php echo $country; ?>" />
    <p><b>Subject: </b><br />
    <input type="text" style="border: 1px solid gray" name="subject" value="<?php echo $subject; ?>"/></p>
    <p><b>Message: </b><br />
    <textarea name="message"
      style="border: 1px solid gray"><?php echo $message ?></textarea>
    </p>
    <p><input type="checkbox" name="agreement" value="yes" />&nbsp
    I agree to use this facility only to organize country Chapter activity,
    in-keeping with the <a href="https://creativecommons.org/network/charter/" target="_blank">Global Network Charter</a>.</p>
    <input type="submit" name="send" value="Email Members" />
    </form>
    <?php
}

// Handle the email form

function ccgn_member_chapter_search_send_email (
    $chapter_country,
    $subject,
    $message
) {
    $users = ccgn_members_interested_in_chapter ( $chapter_country );
    $emails = array_map( function ( $a ) { return $a->user_email; }, $users );
    $result = ccgn_contact_email_to_many (
        $emails,
        'ccgn-email-chapter-contact',
        $subject,
        $message
    );
    if ( $result ) {
        ?><h3>Your message has been sent</h3>
          <p>Watch for it in your email inbox, and send any follow-up mails
             using your email client.</p><?php
    } else {
        ?><h3>There was an error</h3><p>Please contact the site admins.</p><?php
    }
}

///////////////////////////////////////////////////////////////////////////////
// Handle any form arguments and render the page
///////////////////////////////////////////////////////////////////////////////

function ccgn_member_search_chapter_interest_shortcode_render ( $atts ) {
    if ( ! is_user_logged_in () ) {
        echo '<p><b>You must be logged in to use this facility.</b></p>';
        return;
    }
    // User is a member, and an individual member
    if ( ! ccgn_member_is_individual ( get_current_user_id () ) ) {
        echo '<p><b>You must be registered as an individual member in to use this facility.</b></p>';
        return;
    }
    $chapter = filter_input( INPUT_POST, 'chapter', FILTER_SANITIZE_STRING );
    $subject = filter_input( INPUT_POST, 'subject', FILTER_SANITIZE_STRING );
    $message = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );
    $agreement = filter_input( INPUT_POST, 'agreement', FILTER_SANITIZE_STRING);
    if ( $chapter && $subject && $message && ($agreement == 'yes') ) {
        ccgn_member_chapter_search_send_email (
            $chapter,
            $subject,
            $message
        );
    } elseif ( $chapter ) {
        ccgn_send_member_chapter_email_form (
            $chapter,
            $subject,
            $message,
            $agreement
        );
    } else {
        $chapter = xprofile_get_field_data( 'Preferred Country Chapter' );
        ccgn_send_member_chapter_search_form ();
    }
}
