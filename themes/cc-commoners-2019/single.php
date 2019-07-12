<?php 
    get_header();
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        $format = get_post_format();
?>
<section class="main-content hentry">
    <div class="grid-container">
        <div class="grid-x align-center">
            <div class="cell large-8">
                <?php 
                    if ( !empty( $format ) ) {
                        get_template_part('inc/partials/post',$format);
                    } else {
                        get_template_part('inc/partials/post','default');
                    }
                ?>
                <section class="entry-content">
                    <div class="content-format">
                        <?php the_content(); ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>