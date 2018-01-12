<?php /* Template Name: Full Width */

get_header();

?>




	<div class="block-area">

		<div class="inner-section-left-col" style="width:100% !important; float:none !important;">

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

	</div><!--/.block-area-->
	

<?php get_footer(); ?>