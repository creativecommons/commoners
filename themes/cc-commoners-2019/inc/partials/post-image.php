<header class="entry-header post-header image-post-format">
    <div class="wrap-image">
        <?php 
            if ( has_post_thumbnail() ) {
                echo '<figure class="entry-image">'.get_the_post_thumbnail(get_the_ID(),'landscape-medium').'</figure>';
            }
        ?>
        <div class="wrap-text">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </div>
    </div>
    <?php get_template_part('inc/partials/post','meta') ?>
</header>