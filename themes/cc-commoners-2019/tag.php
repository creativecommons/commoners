<?php get_header(); ?>
<section class="main-content">
    <div class="grid-container">
        <div class="grid-x hentry">
            <div class="cell auto">
                <header class="section-header">
                    <h1 class="archive-title"><?php echo front::page_title(); ?></h1>
                </header>
                <section class="entry-content">
                    <?php 
                    if ( have_posts() ) {
                        while ( have_posts() ) { the_post();
                            global $post;
                            echo render::archive_news( $post );
                        }
                        the_posts_pagination(
                            array(
                                'prev_text'          => __( 'Previous', 'ccgn-website' ),
                                'next_text'          => __( 'Next', 'ccgn-website' )
                            )
                        );
                    }
                     ?>
                </section>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>