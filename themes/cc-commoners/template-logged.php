<?php

/*
Template Name: Logged in content Page
 */

get_header();
?>
<div class="cc-content-area">
    <?php if (is_user_logged_in()): ?>
    <div class="inner-section-title">
        <h1><?php the_title(); ?></h1>
    </div>
    <?php endif; ?>
    <div class="block-area">
        <?php $logged_class = (is_user_logged_in()) ? 'login' : 'not-login'; ?>
        <div class="inner-section-list entry-content <?php echo $logged_class ?>">
            <?php if (is_user_logged_in()) : ?>
                <?php get_template_part('template-parts/loop/frontpage-loop'); ?>
            <?php else: ?>
                <div class="callout warning">
                    <h5 class="title">Sorry</h5>
                    <p>You have to be logged in to see this content</p>
                </div>
            <?php endif; ?>
        </div>

    </div><!--/.block-area-->
</div>

<?php get_footer(); ?>