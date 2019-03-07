<?php
/**
 * The template for displaying search results pages
 *
 */

get_header(); ?>
<div class="inner-section-title">
	<div class="grid-content">
		<div class="grid-x align-center">
			<header class="post-header">
                <?php if (have_posts()) : ?>
                    <h1 class="entry-title big"><?php printf(__('Search Results for: %s', 'twentyseventeen'), '<span>' . get_search_query() . '</span>'); ?></h1>
                <?php else : ?>
                    <h1 class="entry-title big"><?php _e('Nothing Found', 'twentyseventeen'); ?></h1>
                <?php endif; ?>
			</header>
		</div>
	</div>
</div>
<div class="page-body">
    <div class="grid-container main-content">
        <div class="grid-x grid-padding-x align-center">
            <div class="large-10 cell">

                <div class="inner-section-list">

                    <section class="item-acordion entry-content">

                        <div id="primary" class="content-area post-content">
                            <?php
                                if ( have_posts() ) :
                                    while (have_posts()): the_post(); ?>
                                        <article class="hentry search-result">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="entry-title"><?php the_title(); ?></a>
                                            <?php the_excerpt(); ?>
                                        </article>
                                        <?php
                                    endwhile;
                                else : ?>
                                    <p class="text-center"><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentyseventeen' ); ?></p>
                                <?php
                                    get_search_form();
                                endif;
                            ?>

                        </div><!-- #primary -->
                    </section><!-- .item-accordion -->
                </div><!-- .inner-selection-list -->
            </div>
        </div>
    </div><!-- .block-area -->
</div>

<?php get_footer();
