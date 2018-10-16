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
                           
                            <div class="grid-x grid-padding-x large-up-3">
                                <div class="cell">
                                    <article class="entry-status <?php echo $step[1] ?>">
                                        <span class="icon"><span class="dashicons dashicons-universal-access-alt"></span></span>
                                        <span class="subtitle">Application Incomplete</span>
                                        <h4 class="entry-title">Create Account && Select vouchers</h4>
                                        <p class="entry-summary">Your application is still incomplete. You need to update the information provided or update the names of the vouchers</p>
                                        <?php if ($application_status['step']['step'] == 1): ?>
                                            <p class="entry-status-content">
                                                <span class="subtitle">Your current status</span>
                                                <span class="status-text"><?php echo $application_status['step']['msg'] ?></span>
                                                <small class="update-date">
                                                    Last updated: <?php echo date('Y-m-d', strtotime($application_status['date'])) ?>
                                                </small>
                                                <?php if (!empty($application_status['step']['link'])) : ?>
                                                    <a href="<?php echo $application_status['step']['link'] ?>" class="button status-action"><?php echo $application_status['step']['link_text'] ?></a>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </article>
                                </div>
                                <div class="cell">
                                    <article class="entry-status <?php echo $step[2] ?>">
                                        <span class="icon"><span class="dashicons dashicons-tickets-alt"></span></span>
                                        <span class="subtitle">Not yet vouched</span>
                                        <h4 class="entry-title">Wait for Vouchers</h4>
                                        <p class="entry-summary">Your application is not yet vouched. The members you selected to vouch for your application still do not respond</p>
                                        <?php if ($application_status['step']['step'] == 2) : ?>
                                            <p class="entry-status-content">
                                                <span class="subtitle">Your current status</span>
                                                <span class="status-text"><?php echo $application_status['step']['msg'] ?></span>
                                                <small class="update-date">
                                                    Last updated: <?php echo date('Y-m-d', strtotime($application_status['date'])) ?>
                                                </small>
                                                <?php if (!empty($application_status['step']['link'])) : ?>
                                                    <a href="<?php echo $application_status['step']['link'] ?>" class="button status-action"><?php echo $application_status['step']['link_text'] ?></a>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </article>
                                </div>
                                <div class="cell">
                                    <article class="entry-status <?php echo $step[3] ?>">
                                        <span class="icon"><span class="dashicons dashicons-thumbs-up"></span></span>
                                        <span class="subtitle">Under review</span>
                                        <h4 class="entry-title">Final Approval</h4>
                                        <p class="entry-summary">Your application has been vouched and now it’s under review from the Membership Council. Please be patient! It shouldn’t take very long.</p>
                                        <?php if ($application_status['step']['step'] == 3) : ?>
                                            <p class="entry-status-content">
                                                <span class="subtitle">Your current status</span>
                                                <span class="status-text"><?php echo $application_status['step']['msg'] ?></span>
                                                <small class="update-date">
                                                    Last updated: <?php echo date('Y-m-d', strtotime($application_status['date'])) ?>
                                                </small>
                                                <?php if (!empty($application_status['step']['link'])) : ?>
                                                    <a href="<?php echo $application_status['step']['link'] ?>" class="button status-action"><?php echo $application_status['step']['link_text'] ?></a>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
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