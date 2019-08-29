<?php get_header(); ?>
<section class="main-content inner-space">
    <div class="grid-content">
        <div class="grid-x grid-padding-x">
            <div class="cell large-12 text-center">
                <figure class="404image">
                    <img src="<?php bloginfo('stylesheet_directory') ?>/assets/images/cc-logo-dashed.svg" alt="">
                </figure>
                <h1 class="entry-title"><strong>404</strong> Not found</h1>
                <div class="std-text">
                    <h2 class="subtitle">Sorry, we couldn't find what you're looking for :(</h2>
                    
                    <p class="inner-space"><a href="<?php echo site_url() ?>" class="button yellow">Back to Homepage</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>