<?php get_header(); ?>
<section class="main-content">
    <?php 
        $featured_item = front::get_last_featured();
        if ( $featured_item ) {
            echo render::home_feature($featured_item[0]);
        }
     ?>
     <?php 
        if ( !is_user_logged_in() ) {
            get_template_part('inc/partials/home','not-logged-in');
        } else {
            if ( bp_commoners::current_user_is_accepted() ) {
                get_template_part('inc/partials/home','logged-in-accepted');
            } else {
                get_template_part('inc/partials/home','logged-in-not-accepted');
            }
        }
    ?>
<?php get_footer(); ?>