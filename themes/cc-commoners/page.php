<?php get_header(); ?>

	


	<div class="signup-form-body" id="signup-now">
		
		<?php
		//while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/loop/frontpage-loop' );

		//endwhile; // End of the loop.
		?>
		
	</div>

	

<?php get_footer(); ?>