<?php get_header(); ?>


	<?php
	//while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/loop/frontpage-loop' );

		/*
		if ( comments_open() || get_comments_number() ) :
			//comments_template();
		endif;
		*/

	//endwhile; // End of the loop.
	?>
	

<?php get_footer();
