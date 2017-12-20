<?php

/*
Template name: Column and sidebar
*/

get_header(); ?>

	


	<div class="block-area">

		<div class="inner-section-left-col">



			<?php
			if (have_posts()):
				while (have_posts()):
			        the_post(); ?>

			    	
				<h2><?php the_title(); ?></h2>

			    	<?php the_content(); ?>

			<?php endwhile; ?>

			<?php
			else: // no posts found
			?>
			<p>No posts found matching your criteria.</p>
			<?php
			endif; // done checking for posts
			?>


		</div>


		<aside>

			<img src="http://via.placeholder.com/1000x550">

		</aside>

	</div><!--/.block-area-->
	

<?php get_footer(); ?>