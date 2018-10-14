<?php
/**Template name: User status*/
get_header(); 
the_post();
$level = ccgn_current_user_level();
$logged_in = is_user_logged_in();
$user_id = get_current_user_id();
$application_status = ccgn_show_current_application_status($user_id);
$step = array();
$step[1] = ($application_status['step']['step'] == 1) ? $application_status['step']['class'] : '';
$step[2] = ($application_status['step']['step'] == 2) ? $application_status['step']['class'] : '';
$step[3] = ($application_status['step']['step'] == 3) ? $application_status['step']['class'] : '';
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
                        <?php if ($logged_in): ?>
                            <?php if (!empty($application_status['step']['msg'])): ?>
                                <div class="application-status">
                                    <p><strong>Current: </strong><?php echo $application_status['step']['msg'] ?></p>
                                    <p><small>Last updated: <?php echo date('Y-m-d',strtotime($application_status['date'])) ?></small></p>
                                    <?php if (!empty($application_status['step']['link'])): ?>
                                        <a href="<?php echo $application_status['step']['link'] ?>" class="button top-right"><?php echo $application_status['step']['link_text'] ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="grid-x grid-padding-x large-up-3">
                                <div class="cell">
                                    <article class="entry-status <?php echo $step[1] ?>">
                                        <span class="icon"><span class="dashicons dashicons-universal-access-alt"></span></span>
                                        <span class="subtitle">Application</span>
                                        <h4 class="entry-title">Create Account && Select vouchers</h4>
                                        <p class="entry-summary">At this stage you have to agree with the Membership Charter and our privacy policy and terms, also you have to provide us some personal details and select two members to vouch for your application</p>
                                    </article>
                                </div>
                                <div class="cell">
                                    <article class="entry-status <?php echo $step[2] ?>">
                                        <span class="icon"><span class="dashicons dashicons-tickets-alt"></span></span>
                                        <span class="subtitle">Vouching</span>
                                        <h4 class="entry-title">Wait for Vouchers</h4>
                                        <p class="entry-summary">Wait for your vouchers and stay alert if you have to update them in case if they cannot vouch for you</p>
                                    </article>
                                </div>
                                <div class="cell">
                                    <article class="entry-status <?php echo $step[3] ?>">
                                        <span class="icon"><span class="dashicons dashicons-thumbs-up"></span></span>
                                        <span class="subtitle">Approval</span>
                                        <h4 class="entry-title">Final Approval</h4>
                                        <p class="entry-summary">You're almost ready for the final approval</p>
                                    </article>
                                </div>
                            </div>
                        <?php else: ?>
                        <div class="callout warning">
                            <h5 class="title">Logged in</h5>
                            <p>You have to log in in to see your Application status</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>