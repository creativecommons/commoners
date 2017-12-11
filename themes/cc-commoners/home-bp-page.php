<?php

/* Template Name: Buddypress home */

get_header(); ?>


	<?php
	//while ( have_posts() ) : the_post();

		//get_template_part( 'template-parts/loop/frontpage-loop' );

		/*
		if ( comments_open() || get_comments_number() ) :
			//comments_template();
		endif;
		*/

	//endwhile; // End of the loop.
	?>



<figure class="main-slideshow">

		<div class="swiper-wrapper">

			<div class="swiper-slide">

				<img src="<?php echo get_template_directory_uri(); ?>/assets/i/slide-hub-1.jpg" />

				<div class="slide-caption caption-bphome">
					<h3>GIVE US YOUR BEST SMILE!</h3>
					<p>Actually this is how everyone else can see you.<br>
						Why not update your profile now?</p>
				</div>

			</div><!--/.swiper-slide-->

		</div>
	
	</figure>


	<div class="we-are-gorwing-block">

		<h4>We are growing</h4>

		<div class="we-are-gorwing-block-content">

			<div class="we-are-gorwing-col chapters-col">

				<p><span>88</span> Chapters</p>

			</div>

			<div class="we-are-gorwing-col members-col">

				<p><span>500</span> Members</p>
				
			</div>

			<div class="we-are-gorwing-col partners-col">

				<p><span>80</span> Partners</p>
				
			</div>

		</div>

	</div>


	<div class="we-are-growing-grid">
		
		<div class="we-are-growing-grid-row">
		
			<div class="we-are-growing-grid-block">

				<h3>Our Platforms</h3>
				
				<ul>
					<li><a href="#" class="copyright-platform">Copyright Reform Platform</a></li>
					<li><a href="#" class="community-development">Comunity Development Platform</a></li>
					<li><a href="#" class="glam-platform">GLAM Platform</a></li>
					<li><a href="#" class="open-education">Open Education</a></li>
				</ul>
				
			</div>
			
			<div class="we-are-growing-grid-block chapters-block">

				<h3>Latest Chapters</h3>
				
				<ul>
					<li><a href="#" class="canada">Canada</a></li>
					<li><a href="#" class="swiss">Swiss</a></li>
					<li><a href="#" class="usa">USA</a></li>
					<li><a href="#" class="china">China</a></li>
					<li><a href="#" class="chile">Chile</a></li>
					<li><a href="#" class="canada">Canada</a></li>
					<li><a href="#" class="swiss">Swiss</a></li>
					<li><a href="#" class="usa">USA</a></li>
				</ul>
				
			</div>
			
			<div class="we-are-growing-grid-block">

				<h3>Newest Members</h3>
				
				<article>
					<div class="avatar-container">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/i/avatar-1.png">
					</div>

					<div class="member-info">
						<h4><a href="#">Nabakanta Panighrahi</a></h4>
						<p class="member-country">Chile</p>
						<p>Last updated: May 2017</p>
						<p>Job: Machine Learning</p>
					</div>
				</article>

				<article>
					<div class="avatar-container">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/i/avatar-1.png">
					</div>

					<div class="member-info">
						<h4><a href="#">Nabakanta Panighrahi</a></h4>
						<p class="member-country">Chile</p>
						<p>Last updated: May 2017</p>
						<p>Job: Machine Learning</p>
					</div>
				</article>

				<article>
					<div class="avatar-container">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/i/avatar-1.png">
					</div>

					<div class="member-info">
						<h4><a href="#">Nabakanta Panighrahi</a></h4>
						<p class="member-country">Chile</p>
						<p>Last updated: May 2017</p>
						<p>Job: Machine Learning</p>
					</div>
				</article>
				
			</div>
		
		</div><!--/.hub-grid-row-->

		
	</div>
	

<?php get_footer();
