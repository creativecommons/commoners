<?php

/*
Template Name: Content Page
 */

get_header(); 
?>
<div class="cc-content-area">
    <div class="inner-section-title">
        <h1><?php the_title(); ?></h1>
    </div>
    <div class="block-area">

        <div class="inner-section-list entry-content">

            <?php get_template_part('template-parts/loop/frontpage-loop'); ?>

        </div>

    </div><!--/.block-area-->
</div>

<?php get_footer(); ?>