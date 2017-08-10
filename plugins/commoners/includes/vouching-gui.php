<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

////////////////////////////////////////////////////////////////////////////////
// Vouching GUI
////////////////////////////////////////////////////////////////////////////////

if ( ! defined( 'COMMONERS_VOUCHING_VOUCH_URL' ) ) {
    define(
        'COMMONERS_VOUCHING_VOUCH_URL',
        wp_make_link_relative( plugin_dir_url( __FILE__ ) . 'vouch-for')
    );
}

function commoners_vouching_url_handler () {
    if ( // isset( $_GET['myplugin'] ) &&
        $_SERVER["REQUEST_URI"] == COMMONERS_VOUCHING_VOUCH_URL ) {
        commoners_vouching_control_post_handler();
    }
}

// Format the list of users that the user is vouched by as an html list

function commoners_vouching_by_html ($userid) {
    global $auto_vouch_username;
    $html = '<ul>';
    $vouches = commoners_vouching_by_vouchee( $userid );
    if (count(vouches) > 0) {
        foreach ($vouches as $vouch) {
            // Handle auto vouches
            if ( intval($vouch->autovouch) === 0) {
                $user_display = bp_core_get_userlink( $vouch->voucher );
            } else {
                $user_display = $auto_vouch_username;
            }
            $html .= '<li><b>' . $user_display
                  . '</b><blockquote>' . $vouch->description
                  . '</blockquote></li>';
        }
        $html .= '</ul>';
    }
    echo $html;
}

// Format the list of users that the user has vouched for as an html list

function commoners_vouching_for_html ($userid) {
    $html = '<ul>';
    $vouches = commoners_vouching_by_voucher( $userid );
    if ( count( vouches ) > 0) {
        foreach ($vouches as $vouch) {
            $html .= '<li>' . bp_core_get_userlink( $vouch->vouchee )
                     . '</li>';
        }
        $html .= '</ul>';
    }
    echo $html;
}

// Display the vouching control for the user being viewed

function commoners_vouching_control_html ($profile_id) {
    // User is logged in
    if ( ! is_user_logged_in() ) {
        echo '<b><i>You must be logged in to vouch.</i></b>';
        return;
    }
    // User is not viewing own profile
    $viewer_id = get_current_user_id();
    if ( $viewer_id === $profile_id ) {
        echo '<b><i>You cannot vouch for yourself.</i></b>';
        return;
    }
    // User can vouch
    if ( ! commoners_vouching_can_vouch( $viewer_id ) ) {
        echo '<b><i>You cannot vouch for other Commoners yet.</i></b>';
        return;
    }
    // User has not already vouched for this user
    if ( commoners_vouching_already ( $profile_id, $viewer_id ) ) {
        echo '<b><i>You have vouched for this Commoner.</i></b>';
        return;
    }
    // Profile hasn't reached maximum number of vouches
    if ( commoners_vouching_maxed ( $profile_id ) ) {
        echo '<b><i>Commoner already has maximum number of vouches</i></b>';
        return;
    }
    // Generate html for control
?>
    <h3>Vouch for this Commoner if you know them</h3>
    <form method="POST"
        action="<?php echo COMMONERS_VOUCHING_VOUCH_URL; ?>">
        <input type="hidden" name="vouchee" value="<?php echo $profile_id; ?>">
        </input>
        <label>Description:
            <textarea name="description" minlength="64" maxlength="500"
                placeholder="Tell us in your own words why they are awesome"
                required></textarea>
        </label>
        <br>
        <input type="submit" name="vouch-submit" value="Vouch"></input>
    </form>
<?php
}

function commoners_vouching_control_post_handler () {

    // User is logged in
    if ( ! is_user_logged_in() ) {
        echo 'Must be logged in';
        exit;
    }

    $data = filter_input_array(
        INPUT_POST,
        array(
            'vouchee' => FILTER_VALIDATE_INT,
            'description' => FILTER_SANITIZE_STRING
        )
    );

    // Check presence of data here, extract it later
    if ( ! isset( $data['vouchee'], $data['description'] ) ) {
        echo 'Need vouchee and description';
        exit;
    }

    $voucher = wp_get_current_user();
    $voucher_id = $voucher->ID;

    // User can vouch
    if ( ! commoners_vouching_can_vouch( $voucher_id ) ) {
        echo 'Must be able to vouch';
        exit;
    }

    // User id to vouch for as int
    $vouchee_id = $data['vouchee'];
    if (! get_userdata( $vouchee_id ) ) {
        echo 'User does not exist';
        exit;
    }

    // User is not vouching for themself
    if ( $voucher_id === $vouchee_id ) {
        echo 'You cannot vouch for yourself';
        exit;
    }

    // User has not already vouched for this user
    if ( commoners_vouching_already ( $vouchee_id, $voucher_id ) ) {
        echo 'You have already vouched for that Commoner';
        exit;
    }

    // Profile hasn't reached maximum number of vouches
    if ( commoners_vouching_maxed ( $vouchee_id ) ) {
        echo 'Commoner already has maximum number of vouches';
        exit;
    }

    // Vouch text should not contain tags
    $description = $data['description'];
    if ( $description === '' ) {
        echo 'Description cannot be empty';
        exit;
    }

    //FIXME: Also check description for too short, spam, hate
    if ( count($description) > 500 ) {
        echo 'Description is too long.';
        exit;
    }

    // Insert vouch
    commoners_vouching_add( $vouchee_id, $voucher_id, $description, false );

    // Redirect to show the new vouch
    wp_redirect(
        bp_core_get_user_domain( $vouchee_id ) . 'vouching/vouched-by/'
    );
    exit;
}