<?php
/**
 * BuddyPress - Members Home
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>

<div id="buddypress">

	<?php

	/**
	 * Fires before the display of member home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_home_content' ); ?>

	<div id="item-header" role="complementary">

		<?php
		/**
		 * If the cover image feature is enabled, use a specific header
		 */
		if ( bp_displayed_user_use_cover_image_header() ) :
			bp_get_template_part( 'members/single/cover-image-header' );
		else :
			bp_get_template_part( 'members/single/member-header' );
		endif;
		?>

	</div><!-- #item-header -->
	<div id="ccgn-user-meta">
		<?php 
			$country = xprofile_get_field_data('Location', bp_displayed_user_id() );
			$chapter = xprofile_get_field_data('Preferred Country Chapter', bp_displayed_user_id() );
			$languages = xprofile_get_field_data('Languages', bp_displayed_user_id() );
			if ( !empty($country) ) {
				echo '<p class="ccgn-user-meta-item location" data-tooltip title="Country"><i class="ion-ios-location-outline"></i> '.$country.'</p>';
			}
			if ( !empty($chapter) ) {
				echo '<p class="ccgn-user-meta-item chapter" data-tooltip title="Prefered Country Chapter"><i class="ion-ios-color-filter-outline"></i> '.$chapter.'</p>';
			}
			if ( !empty($languages) ) {
				echo '<p class="ccgn-user-meta-item languages" data-tooltip title="Languages"><i class="ion-android-chat"></i> '.$languages.'</p>';
			}
		 ?>
	</div>
	<?php 
		
		$user = get_user_by('ID', bp_displayed_user_id());
		$chapters_reps = Commoners::reps();
		$membership_comitee = in_array( 'membership-council-member', $user->roles );
		$chapter_lead = $chapters_reps['chapter_leads'][ bp_displayed_user_id() ];
		$gnc_member = $chapters_reps['gnc_members'][ bp_displayed_user_id() ];
		if ( Commoners::is_excom_member( bp_displayed_user_id() ) || $membership_comitee || $chapter_lead || $gnc_member ) {
			echo '<div class="badges">';
				if ( Commoners::is_excom_member( bp_displayed_user_id() ) ) {
					echo '<div class="badge excom" data-tooltip title="This member is part of the Executive Committee">';
						get_template_part( 'inc/partials/badges/member', 'excom' );
					echo '</div>';
				}
				if ( $membership_comitee ) {
					echo '<div class="badge mc" data-tooltip title="This member is part of the Membership Committee">';
						get_template_part( 'inc/partials/badges/member', 'mc' );
					echo '</div>';
				}
				if ( !empty( $chapter_lead ) ) {
					echo '<div class="badge chapter-lead" data-tooltip title="This member is a Chapter Lead">';
						get_template_part( 'inc/partials/badges/member', 'chapter-lead' );
					echo '</div>';
				}
				if ( !empty( $gnc_member ) ) {
					echo '<div class="badge gnc" data-tooltip title="This member is a GNC representative">';
						get_template_part( 'inc/partials/badges/member', 'gnc' );
					echo '</div>';
				}
			echo '</div>';
		}
	?>
	<div id="item-nav">
		<div class="item-list-tabs no-ajax" id="object-nav" aria-label="<?php esc_attr_e( 'Member primary navigation', 'buddypress' ); ?>" role="navigation">
			<ul>

				<?php //bp_get_displayed_user_nav(); ?>

				<?php

				/**
				 * Fires after the display of member options navigation.
				 *
				 * @since 1.2.4
				 */
				do_action( 'bp_member_options_nav' ); ?>

			</ul>
		</div>
	</div><!-- #item-nav -->

	<div id="item-body">

		<?php

		/**
		 * Fires before the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_body' );

		if ( bp_is_user_front() ) :
			bp_displayed_user_front_template_part();

		elseif ( bp_is_user_activity() ) :
			bp_get_template_part( 'members/single/activity' );

		elseif ( bp_is_user_blogs() ) :
			bp_get_template_part( 'members/single/blogs'    );

		elseif ( bp_is_user_friends() ) :
			bp_get_template_part( 'members/single/friends'  );

		elseif ( bp_is_user_groups() ) :
			bp_get_template_part( 'members/single/groups'   );

		elseif ( bp_is_user_messages() ) :
			bp_get_template_part( 'members/single/messages' );

		elseif ( bp_is_user_profile() ) :
			bp_get_template_part( 'members/single/profile'  );

		elseif ( bp_is_user_notifications() ) :
			bp_get_template_part( 'members/single/notifications' );

		elseif ( bp_is_user_settings() ) :
			bp_get_template_part( 'members/single/settings' );

		// If nothing sticks, load a generic template
		else :
			bp_get_template_part( 'members/single/plugins'  );

		endif;

		/**
		 * Fires after the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_body' ); ?>

	</div><!-- #item-body -->

	<?php

	/**
	 * Fires after the display of member home content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_home_content' ); ?>

</div><!-- #buddypress -->
