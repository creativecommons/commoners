<?php /* Template Name: Full Width */

get_header();

?>
	<div class="block-area">
		<div class="inner-section-left-col fullwidth-col">
			<?php
			if (have_posts()):
				while (have_posts()):
			        the_post(); ?>
				<h2><?php the_title(); ?></h2>
        <?php if( is_page( 'members' ) ): ?>
        <div style="float:left">You can search for (and contact) Members who have expressed interest in joining a Chapter <a href="/search-members-chapter-interest/">here</a>.</div>
        <?php endif ?>

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