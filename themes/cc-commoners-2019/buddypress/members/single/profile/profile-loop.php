<?php
/**
 * BuddyPress - Members Profile Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>

	<?php 
		$bio = xprofile_get_field_data('Bio', bp_displayed_user_id() );
		if ( !empty( $bio ) ) {
			echo '<div class="grid-x align-center">';
				echo '<div class="cell large-8 medium-8">';
					echo '<h3 class="middle-title"><span>Bio</span></h3>';
					echo '<div class="entry-summary bio">'.apply_filters( 'the_content', $bio ).'</div>';
				echo '</div>';
			echo '</div>';
		}
		$areas = xprofile_get_field_data( 'Areas of Interest', bp_displayed_user_id() );
		if ( !empty( $areas ) ) {
			echo '<div class="grid-x align-center">';
				echo '<div class="cell large-8 medium-8">';
					echo '<div class="side-links">';
						echo '<div class="side-title">Areas of interest</div>';
						echo '<div class="link-list">';
							foreach ( $areas as $area) {
								echo '<a href="'.site_url('members').'/?members_search='.urlencode( $area ).'" class="button gray tiny">'.$area.'</a>';
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
		$links = xprofile_get_field_data( 'Links', bp_displayed_user_id() );
		if ( !empty( $links ) ) {
			preg_match_all("/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/", $links, $match);
			if ( !empty( $match[0] ) ) {
				echo '<div class="grid-x align-center">';
					echo '<div class="cell large-8 medium-8">';
						echo '<div class="side-links">';
							echo '<div class="side-title">Links</div>';
							echo '<div class="link-list">';
								foreach ( $match[0] as $link) {
									echo '<a href="'.esc_url( $link ).'" class="button gray tiny" target="_blank">'.esc_url( $link ).'</a>';
								}
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		}
		if ( bp_commoners::current_user_is_accepted() ) {
			$vouchers = ccgn_application_users_page_vouch_responses_data( bp_displayed_user_id() , true );
			if ( !empty( $vouchers ) ) {
				echo '<div class="grid-x align-center">';
					echo '<div class="cell large-8 medium-8">';
						echo '<h3 class="middle-title"><span>Vouching</span></h3>';			
							echo '<div class="grid-x grid-margin-x large-up-2 medium-up-2">';
							foreach ( $vouchers as $voucher ) {
								if ( $voucher['vouched'] == 'Yes' ) {
									echo render::voucher($voucher);
								}
							}
							echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		}
		$show_fields_list = false;
	 ?>
	<?php if ( $show_field_list ): ?>
		<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

			<?php if ( bp_profile_group_has_fields() ) : ?>

				<?php

				/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
				do_action( 'bp_before_profile_field_content' ); ?>

				<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

					<h2><?php bp_the_profile_group_name(); ?></h2>

					<table class="profile-fields">

						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

							<?php if ( bp_field_has_data() ) : ?>

								<tr<?php bp_field_css_class(); ?>>

									<td class="label"><?php bp_the_profile_field_name(); ?></td>

									<td class="data"><?php bp_the_profile_field_value(); ?></td>

								</tr>

							<?php endif; ?>

							<?php

							/**
							 * Fires after the display of a field table row for profile data.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_profile_field_item' ); ?>

						<?php endwhile; ?>

					</table>
				</div>

				<?php

				/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
				do_action( 'bp_after_profile_field_content' ); ?>

			<?php endif; ?>

		<?php endwhile; ?>
	<?php endif; ?>
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' );
