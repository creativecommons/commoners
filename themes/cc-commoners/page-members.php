<?php

/*
Template Name: Index Members
*/

get_header(); ?>


	<div class="block-area">


		<div class="inner-section-left-col">

			<h2><?php the_title(); ?></h2>

			 <p>You can search for (and contact) Members who have expressed interest in joining a Chapter <a href="/search-members-chapter-interest/">here</a>.</p>
			 <p><a href="<?php echo site_url('members/type/institution') ?>">View institutional members</a></p>
                
			<?php //get_template_part( 'template-parts/loop/frontpage-loop' ); ?>
			
			<?php get_template_part( 'buddypress/members-and-partners' ); ?>


		</div>

	</div><!--/.block-area-->		


<?php get_footer(); ?>