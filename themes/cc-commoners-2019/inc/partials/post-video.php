<header class="entry-header post-header video-post-format">
    <?php 
        $video_url = get_post_meta( get_the_ID(), 'post_video_url', true );
        if ( !empty( $video_url ) ) {
            echo '<div class="responsive-embed">';
                echo videos::get_video_embed( $video_url );
            echo '</div>';
        }
     ?>
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php get_template_part('inc/partials/post','meta') ?>
</header>