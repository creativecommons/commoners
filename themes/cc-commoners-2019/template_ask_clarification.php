<?php

/** Template name: User clarification status */

get_header();
the_post();
$level = ccgn_current_user_level();
$logged_in = is_user_logged_in();
$user_id = get_current_user_id();
$log_user = ccgn_ask_clarification_log_user_get( $user_id );

?>
<section class="main-content top-space">
	<div class="grid-container">
		<div class="grid-x grid-padding-x inner-space">
			<div class="cell large-12">
				<div class="page-header">
					<h1 class="entry-title"><?php the_title() ?></h1>
				</div>
				<div class="entry-content">
					<?php the_content(); ?>
					<div class="user-status-container">
						<?php if ($logged_in) : ?>
						   <?php 
								if (!empty($log_user)) {
									$check_status = get_user_meta($user_id, 'ccgn_need_to_clarify_vouch_reason_applicant_status', true);
									if (($check_status['applicant_id'] == $log_item['applicant_id']) && ($check_status['status'] == 1)) {
										echo '<h4>You have these open request for vouching clarification:</h4> <br>';
									} else {
										?>
										<div class="callout success">
											<h5 class="title">No requests</h5>
											<p>You don't have any requests for clarification</p>
										</div>
										<?php
									}
									echo '<div class="grid-x grid-padding-x large-up-3">';
									$check_ids = array();
									foreach ($log_user as $log_item) {
										//echo '<pre>'; print_r($check_status); echo '</pre>';
										$is_in_check_array = in_array($log_item['applicant_id'],$check_ids);
										if (!$is_in_check_array) {
											if ( ($check_status['applicant_id'] == $log_item['applicant_id']) && ($check_status['status'] == 0) ) { continue; }
											$check_ids[] = $log_item['applicant_id'];
											echo '<div class="cell">';
												echo '<article class="hentry entry-ask-request">';
													echo '<span class="subtitle">Vouching on user</span>';
													echo '<h5 class="entry-title">'.$log_item['applicant_name'].'</h5>';
													echo '<span class="subtitle">Asked for</span>';
													echo '<h5 class="entry-title">'.$log_item['ask_user_name'].'</h5>';
													if ( isset($_GET['clarification']) && ( esc_attr($_GET['applicant_id']) == $log_item['applicant_id'] ) )  {
														$user_clarification_mode = get_user_meta($user_id, 'ccgn_need_to_clarify_vouch_reason', true);
														if ($user_clarification_mode) {
															echo wp_nonce_field('clarification_voucher', 'clarification_voucher_nonce', true, false);
															$entries = ccgn_user_get_vouch_entry($log_item['applicant_id']);
															echo '<p><textarea name="clarification_voucher" id="clarification_voucher" cols="50" rows="10">' . $entries['entry_text'] . '</textarea></p>';
															echo '<button class="button button-primary" id="set-new-vouch-reason" data-applicant-id="'.$log_item['applicant_id'].'" data-entry-id="' . $entries['entry_id']. '">Set new reason</button>';
														} else {
															echo '<p>You already change your statement</p>';
														}
													} else {
														$change_url = add_query_arg(array('clarification' => true, 'applicant_id' => $log_item['applicant_id']), get_permalink(get_the_ID()));
														echo '<a href="'.$change_url.'" class="button">Change Vouching statement</a>';
													}
												echo '</article>';
											echo '</div>';
											//echo '<pre>'; print_r($check_ids); echo '</pre>';
										}
									}
									echo '</div>';
								} else {
									?>
									<div class="callout success">
										<h5 class="title">No requests</h5>
										<p>You don't have any request for clarification</p>
									</div>
									<?php
								}
							?>
						<?php else : ?>
						<div class="callout warning">
							<h5 class="title">Logged in</h5>
							<p>You have to log in in to see your requests</p>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>