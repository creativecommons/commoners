<?php

/*
Template Name: Chapter.
*/

get_header(); ?>


	<div class="block-area">


		<div class="banner-wrapper">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/i/platforms-1.jpg" class="col-main-banner">
		</div>
			

		<div class="inner-section-left-col">

			<h1>Vancouverites <a class="join-btn" href="#">Join</a></h1>

			<p>Creative Commons is committed to copyright policy advocacy, and it makes sense that advocacy efforts rely on collaboration with the members of the Creative Commons Global Network.
			<br>
			<br>
			See here for details – <a href="#">https://github.com/creativecommons/network-platforms/blob/master/copyright-reform.md</a></p>

			<div class="secondary-left-col">

				<h3>Members <span class="members-filter"><a href="#">Newest</a> | <a href="#">Active</a> | <a href="#">Chapter</a> | <a href="#">Alphabetical</a></span></h3>

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

			<div class="secondary-right-col">

				<h3>Events on this platform</h3>
				
				<article>
					<div class="date-container">
						<span class="event-date-day">13</span>
						<span class="event-date-month">April</span>
					</div>

					<div class="member-info">
						<h4><a href="#">CC Global summit</a></h4>
						<p>Venue TBA</p>
					</div>
				</article>

				<article>
					<div class="date-container">
						<span class="event-date-day">13</span>
						<span class="event-date-month">April</span>
					</div>

					<div class="member-info">
						<h4><a href="#">CC Global summit</a></h4>
						<p>Venue TBA</p>
					</div>
				</article>

				<article>
					<div class="date-container">
						<span class="event-date-day">13</span>
						<span class="event-date-month">April</span>
					</div>

					<div class="member-info">
						<h4><a href="#">CC Global summit</a></h4>
						<p>Venue TBA</p>
					</div>
				</article>


				<h3>Latest activity</h3>
				
				<article>
					<div class="avatar-container">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/i/avatar-1.png">
					</div>

					<div class="member-info">
						<p><a href="#">Nabakanta Panighrahi</a> joined community development platform 23 days ago.</p>
					</div>
				</article>

				<article>
					<div class="avatar-container">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/i/avatar-1.png">
					</div>

					<div class="member-info">
						<p><a href="#">Nabakanta Panighrahi</a> joined community development platform 23 days ago.</p>
					</div>
				</article>

			</div>

		</div>


		<aside>

			<img src="http://via.placeholder.com/1000x550">

		</aside>

	</div><!--/.block-area-->
	


<?php
if (have_posts()):
	while (have_posts()):
        the_post(); ?>


    	<?php the_content(); ?>

<?php endwhile; ?>

<?php
else: // no posts found
?>
<p>No posts found matching your criteria.</p>
<?php
endif; // done checking for posts
?>


	

<?php get_footer(); ?>