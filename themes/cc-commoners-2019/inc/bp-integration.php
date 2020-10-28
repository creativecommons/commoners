<?php
class bp_commoners {
    public static function current_user_is_accepted() {
        $current_user = get_user_by('ID', get_current_user_id() );
        $user_active = ccgn_registration_user_get_stage_and_date( get_current_user_id() );
        if ( $user_active['stage'] == 'accepted' ) {
            return true;
        } else {
            return false;
        }
    }
    static function check_if_user_is_accepted() {
        $active = ccgn_registration_user_get_stage_and_date( bp_displayed_user_id() );
        if ( $active['stage'] != 'accepted' ) {            
            wp_redirect( home_url() );
            exit;
        }
    }
    static function add_user_meta($text) {
        $user_id = bp_displayed_user_id();
        $displayed_user = get_user_by('ID', $user_id );
        if ( self::current_user_is_accepted() ) {
            echo '<p class="user-email">'.$displayed_user->user_email.'</p>';
        }
        if ( ccgn_member_is_individual ( $user_id ) ) {
            echo _('Individual Member');
        } elseif ( ccgn_member_is_institution ( $user_id ) ) {
            echo _('Institutional Member');
        }
    }
}
add_action('bp_members_screen_display_profile', array( 'bp_commoners', 'check_if_user_is_accepted' ), 10, 1);
add_action('bp_profile_header_meta', array( 'bp_commoners', 'add_user_meta' ),10,1);