<header class="entry-header post-header gallery-post-format">
    <?php 
        $gallery_ids = get_post_meta( get_the_ID(), 'post_gallery', false );
        if ( !empty( $gallery_ids ) ) {
            echo render::post_gallery($gallery_ids);
        }
     ?>
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php get_template_part('inc/partials/post','meta') ?>
</header>