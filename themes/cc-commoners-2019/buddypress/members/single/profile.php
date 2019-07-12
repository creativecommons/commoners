<?php
/**
 * BuddyPress - Users Profile
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>

<?php
/** NOTE: sub navigation items were removed from this part of the template and moved to cover-image-header.php */
/**
 * Fires before the display of member profile content.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_profile_content' ); ?>

<div class="profile">

<?php switch ( bp_current_action() ) :

	// Edit
	case 'edit'   :
		bp_get_template_part( 'members/single/profile/edit' );
		break;

	// Change Avatar
	case 'change-avatar' :
		bp_get_template_part( 'members/single/profile/change-avatar' );
		break;

	// Change Cover Image
	case 'change-cover-image' :
		bp_get_template_part( 'members/single/profile/change-cover-image' );
		break;

	// Compose
	case 'public' :

		// Display XProfile
		if ( bp_is_active( 'xprofile' ) )
			bp_get_template_part( 'members/single/profile/profile-loop' );

		// Display WordPress profile (fallback)
		else
			bp_get_template_part( 'members/single/profile/profile-wp' );

		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch; ?>
</div><!-- .profile -->

<?php

/**
 * Fires after the display of member profile content.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_profile_content' );
