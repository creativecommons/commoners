<?php
/**
 * The template for displaying search results pages
 *
 */

get_header(); ?>

<div class="block-area">

  <div class="inner-section-list">

    <article class="item-acordion">
      <?php if ( have_posts() ) : ?>
        <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyseventeen' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
      <?php else : ?>
        <h1 class="page-title"><?php _e( 'Nothing Found', 'twentyseventeen' ); ?></h1>
      <?php endif; ?>

      <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
          <?php
            if ( have_posts() ) :

              while (have_posts()):
                the_post();
                ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><?php
                the_excerpt();
                ?><hr /><?php
              endwhile;

	        else : ?>

              <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentyseventeen' ); ?></p>
			  <?php
			  get_search_form();

            endif;
          ?>

		</main><!-- #main -->
	  </div><!-- #primary -->
    </article><!-- .item-accordion -->
  </div><!-- .inner-selection-list -->
</div><!-- .block-area -->

<?php get_footer();
