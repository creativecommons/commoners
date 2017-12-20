<?php

/*
Template Name: Index Members
*/

get_header(); ?>


	<div class="block-area">


		<div class="inner-section-left-col">

			<h2><?php the_title(); ?></h2>
			
			<?php //get_template_part( 'template-parts/loop/frontpage-loop' ); ?>
			
			<?php get_template_part( 'buddypress/members-and-partners' ); ?>


		</div>


		<aside>

			<img src="http://via.placeholder.com/1000x550">

		</aside>

	</div><!--/.block-area-->		


<?php get_footer(); ?>