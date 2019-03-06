<?php

/* Template Name: Hub */

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


<div class="grid-container space-top">
	<div class="grid-x">
		<div class="cell large-12">
			<figure class="main-slideshow">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/i/slide-hub-1.jpg" />
						<div class="slide-caption caption-hub">
							<h3>Get Involved</h3>
							<p>Creative Commons empowers people to freely share their knowledge and creativity. The organization relies on its supporters, contributors, activists and creators who make our work possible.
		Learn how you can get involved and make an impact.</p>
						</div>
					</div><!--/.swiper-slide-->
				</div>
			</figure>
			<div class="hub-quote-block">
				<div class="hub-quote-content">
					<h2>"No tool is better than the people."</h2>
					<p>Caroline Woolard <a href="https://creativecommons.org/2017/10/30/no-tool-better-people-cc-artists-conversation-collaboration-community-commons/">@CC Artists in Conversations</a>.</p>
				</div>
			</div>

			<div class="hub-grid">
				<div class="hub-grid-row grid-x large-up-3">
					<div class="hub-grid-block cell">
						<div class="hub-grid-block-svg">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 78.58 84.76"><title>hub</title><path class="icon-path" d="M76.6,62.94,70.32,52.86h0a4.44,4.44,0,0,0-3.87-2.38,3.94,3.94,0,0,0-3.19,2.28c-.1.1-.1.19-.1.38l-.1-.1V6.47A6.53,6.53,0,0,0,56.49,0H6.58A6.53,6.53,0,0,0,0,6.47v67.6a6.53,6.53,0,0,0,6.58,6.47H51.17a15.37,15.37,0,0,0,18.57,2c.1,0,.1-.1.19-.1l2.61-1.62a13.22,13.22,0,0,0,5.71-8.08A13,13,0,0,0,76.6,62.94ZM31.24,3.33a.83.83,0,0,1,.77.76.77.77,0,0,1-1.55,0A.83.83,0,0,1,31.24,3.33ZM7.54,8.08H55.33V55a3.18,3.18,0,0,0-1.93.67,3.12,3.12,0,0,0-1.45,1.9l-3.29-5.23a3.82,3.82,0,0,0-5.22-1.14,3.66,3.66,0,0,0-1.16,5.13l8.32,12.93a7.24,7.24,0,0,0-4.45-1.33,5.06,5.06,0,0,0-3.87,2.47.72.72,0,0,0,.1,1l1.16,1.24h-36ZM33,76.82a1.9,1.9,0,0,1-1.35,1.33,1.88,1.88,0,0,1-2.32-2.28,1.9,1.9,0,0,1,1.35-1.33A1.85,1.85,0,0,1,33,76.82Zm38.59,2.66L69,81.1l-.1.1a13.74,13.74,0,0,1-15.38-.67c-.29-.19-.58-.48-.87-.67a.09.09,0,0,1-.1-.1l-.29-.29-.39-.38-8-8.46a3.66,3.66,0,0,1,2.32-1.33c2.13-.19,4.55,1.81,6,3.14.19.19.39.29.48.48h0l.1.1a2.56,2.56,0,0,0,.58.48.65.65,0,0,0,1,0,.75.75,0,0,0,.19-1l-11-17.11a2.15,2.15,0,0,1,.68-3,2.32,2.32,0,0,1,3.1.67l5.61,8.75a19.8,19.8,0,0,0,1.93,2.95c0,.1.1.1.1.19l.39.67h0l.29.48a.79.79,0,0,0,1.06.29.81.81,0,0,0,.39-1,7.2,7.2,0,0,0-.77-1.24l-.87-1.43h0l-1.06-1.62A6.18,6.18,0,0,1,53.39,58a1.89,1.89,0,0,1,.77-1.14,1.82,1.82,0,0,1,1.06-.38h.29a3.92,3.92,0,0,1,2.13,2l3.1,4.85a.79.79,0,0,0,1.06.29h0a.76.76,0,0,0,.29-1l-3.39-5.23a2.15,2.15,0,0,1,.68-3h0a1.52,1.52,0,0,1,1.35-.38h.77c.1,0,.1.1.19.1h0a.09.09,0,0,1,.1.1h0l.1.1h0l.1.1h0a4.16,4.16,0,0,1,.58.67h0c0,.1.1.1.1.19s.29.38.39.57L66,60.28a.79.79,0,0,0,1.06.29.76.76,0,0,0,.29-1c-.87-1.33-3-5-3-5a.09.09,0,0,0-.1-.1c-.19-.19-.1-.57.1-1a2.54,2.54,0,0,1,1.93-1.43c.87-.1,1.74.48,2.51,1.62l6.29,10.08A11.27,11.27,0,0,1,71.57,79.49Z"/><path class="icon-path" d="M43.29,40.8l-11.16,11a.9.9,0,0,1-.69.29,1.08,1.08,0,0,1-.69-.29l-11.16-11a7.14,7.14,0,0,1,5.14-12.23,7.88,7.88,0,0,1,5.33,2.14L31.34,32h.2l1.28-1.26a7.62,7.62,0,0,1,10.37-.19A7.15,7.15,0,0,1,43.29,40.8Z"/></svg>
						</div>
						<h3>Become a CC Socializer</h3>
						<p>Want to get involved in an easy, lightweight way? Follow us on social media, join our mailing list, and chat with us on Slack! <br>
						Subscribe to our <a href="https://creativecommons.org/newsletter">Newsletter</a> <br>
						Follow us on social media: <a href="https://www.facebook.com/creativecommons">Facebook</a>, <a href="https://twitter.com/creativecommons">Twitter</a>, <a href="https://instagram.com/creativecommons">Instagram</a>. Join our <a href="https://creativecommons.email/mailman/listinfo/community">Mailing list</a> and <a href="https://slack-signup.creativecommons.org/">Slack</a>.</p>
					</div>
					<div class="hub-grid-block cell">
						<div class="hub-grid-block-svg">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88 84.91"><title>hub</title><path class="icon-path" d="M32.61,32.61h0a2.7,2.7,0,0,0-.79,1.9,2.68,2.68,0,0,0,2.69,2.69,2.72,2.72,0,0,0,1.9-.79,2.67,2.67,0,0,0,.79-1.9,2.69,2.69,0,0,0-4.59-1.9Z"/><path class="icon-path" d="M37.2,10.64A2.69,2.69,0,0,0,34.51,8,26.59,26.59,0,0,0,8,34.51a2.69,2.69,0,1,0,5.37,0A21.21,21.21,0,0,1,34.51,13.32,2.69,2.69,0,0,0,37.2,10.64Z"/><path class="icon-path" d="M86.11,38.29a6.47,6.47,0,0,0-6.38-1.63V35.49A6.48,6.48,0,0,0,73.26,29a6.25,6.25,0,0,0-2.44.5,6.57,6.57,0,0,0-2.72-2.92A34.5,34.5,0,1,0,34.51,69a34.33,34.33,0,0,0,8.28-1A23,23,0,0,0,88,61.88v-19A6.42,6.42,0,0,0,86.11,38.29ZM41.95,53v8.93c0,.26,0,.52,0,.77A29.13,29.13,0,1,1,62.46,26.32a6.5,6.5,0,0,0-3.64,4,6.26,6.26,0,0,0-2.12-.37,6.48,6.48,0,0,0-6.47,6.47v10a6.25,6.25,0,0,0-3.91-.59C43.42,46.42,41.95,48.83,41.95,53Zm41.4,8.93a18.37,18.37,0,0,1-36.74,0V53c0-1.62.23-2.5.69-2.6h.05a3.26,3.26,0,0,1,1.76.79A2.64,2.64,0,0,1,50.23,53v8.11a2.33,2.33,0,0,0,4.66,0V36.4a1.81,1.81,0,1,1,3.63,0V52.15a2.33,2.33,0,1,0,4.66,0V32.25a1.81,1.81,0,0,1,3.62,0V50.08a2.33,2.33,0,0,0,4.66,0V35.49a1.81,1.81,0,0,1,3.63,0V55.25a2.33,2.33,0,0,0,4.66,0V42.86a1.81,1.81,0,1,1,3.62,0Z"/><path class="icon-path" d="M34.51,21.93A12.58,12.58,0,1,0,47.09,34.51,12.6,12.6,0,0,0,34.51,21.93Zm0,19.79a7.21,7.21,0,1,1,7.21-7.21A7.22,7.22,0,0,1,34.51,41.72Z"/></svg>
						</div>
						<h3>Share Your Work</h3>
						<p>Make something cool, license it under Creative Commons, and put it on one of our friendly partner platforms! <br>
						Looking for CC content for collaboration? Check out our new <a href="https://ccsearch.creativecommons.org/">CC Search!</a>. Wondering which license to use? Check out our useful <a href="https://creativecommons.org/choose/">Chooser</a>. Other questions? Find your answer via FAQ or <a href="https://creativecommons.org/about/contact/">contact us</a>.</p>
					</div>
					<div class="hub-grid-block cell">
						<div class="hub-grid-block-svg">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 74.13 84.07"><title>hub</title><path class="icon-path" d="M64.64,20.72V35.85h.09a6,6,0,0,1,4,1.8,6.09,6.09,0,0,1,1.8,4.27,5.75,5.75,0,0,1-1.8,4.27L61.9,52.95l-1.11-1.11,6.84-6.75A4.34,4.34,0,0,0,68.91,42a4.2,4.2,0,0,0-1.28-3.08A3.58,3.58,0,0,0,66.09,38a3.65,3.65,0,0,0-4,.68L45.48,55a6.48,6.48,0,0,0-2,4.62V84.07H59.85V68.85c1.28-1.2,11-10,12.14-11.11,2.05-2,2.14-5.13,2.14-5.9v-31a5,5,0,0,0-5-4.53A4.42,4.42,0,0,0,64.64,20.72Z"/><path class="icon-path" d="M9.58,20.72V35.85H9.49a6,6,0,0,0-4,1.8,6.09,6.09,0,0,0-1.8,4.27,5.75,5.75,0,0,0,1.8,4.27l6.84,6.75,1.11-1.11L6.58,45.08A4.34,4.34,0,0,1,5.3,42a4.06,4.06,0,0,1,1.28-3.08A3.58,3.58,0,0,1,8.12,38a3.65,3.65,0,0,1,4,.68l16.5,16.5a6.48,6.48,0,0,1,2,4.62V84.24H14.28V69C13,67.83,3.25,59,2.14,57.91.09,55.94,0,52.78,0,52V21a5,5,0,0,1,5-4.53A4.2,4.2,0,0,1,9.58,20.72Z"/><path class="icon-path" d="M37.65,0a22.08,22.08,0,1,0,0,44.17,21.78,21.78,0,0,0,16.67-7.64h0a22,22,0,0,0,5.28-14.44A21.93,21.93,0,0,0,37.65,0Zm7.22,33.89H39V23.47h8.47A31.13,31.13,0,0,1,44.87,33.89ZM28.48,23.47h7.78V33.89H31.12A28.14,28.14,0,0,1,28.48,23.47Zm7.78-12.36v9.58H28.48a25.43,25.43,0,0,1,2.22-9.58Zm2.64,9.58V11.11h6.25a28.39,28.39,0,0,1,2.22,9.58Zm0-18.06h.83A27.21,27.21,0,0,1,43.9,8.33h-5ZM36.26,8.47H32a21.25,21.25,0,0,1,4.31-5.69ZM21.53,11.11h6.25a32.69,32.69,0,0,0-1.94,9.58H18.2A19.38,19.38,0,0,1,21.53,11.11ZM18.2,23.47h7.64A29.94,29.94,0,0,0,28.2,33.89h-6A18.55,18.55,0,0,1,18.2,23.47ZM36.26,41.25a39.94,39.94,0,0,1-3.75-4.72h3.75Zm2.64.14V36.53h4.44a30.36,30.36,0,0,1-3.75,4.86Zm14.17-7.5H47.78a29.94,29.94,0,0,0,2.36-10.42H57A19.9,19.9,0,0,1,53.06,33.89ZM57,20.69H50.15a32.69,32.69,0,0,0-1.94-9.58h5.56A20.69,20.69,0,0,1,57,20.69Z"/></svg>
						</div>
						<h3>Join the Community</h3>
						<p>If you’re thinking local but want to act global, join our Global Network! <br>
							Connect with others via <a href="https://slack-signup.creativecommons.org/">Slack</a>. Participate in a <a href="https://github.com/creativecommons/network-platforms">Network Platform</a>. Lead the CC movement as a <a href="https://network.creativecommons.org/sign-up/">Network Member</a>.</p>
					</div>		
					<div class="hub-grid-block cell">
						<div class="hub-grid-block-svg">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 58.18 85.49"><title>hub</title><path class="icon-path" d="M58.18,32.78c0-8.34-4.27-14.55-11.05-16.68a2.22,2.22,0,0,0-2.52,1.36A1.84,1.84,0,0,0,46,19.78c5.24,1.55,8.34,6.4,8.34,12.8,0,9.12-5.62,17.65-11.64,17.65A7.74,7.74,0,0,1,38,48.48a1.94,1.94,0,0,0-2.33,3.1l1.75,1.16a10.48,10.48,0,0,0-2.52,7.18,1.83,1.83,0,0,0,1.94,1.94h3.88a8.49,8.49,0,0,1-4.65,7,1.71,1.71,0,0,0-.78,2.52A1.86,1.86,0,0,0,37,72.53c.39,0,.58,0,.78-.19a12.09,12.09,0,0,0,6.79-10.47h3.88a1.83,1.83,0,0,0,1.94-1.94,12,12,0,0,0-2.52-7.18C54.11,49.65,58.18,40.92,58.18,32.78Zm-19,25.41a8.24,8.24,0,0,1,1.94-3.3l.58-.58h2.13l.58.58a8.24,8.24,0,0,1,1.94,3.3Z"/><path class="icon-path" d="M42.67,23.27C42.67,9.7,33.74,0,21.33,0S0,9.7,0,23.27c0,8.92,4.07,18,10.47,23.08a1.94,1.94,0,1,0,2.33-3.1c-5.43-4.27-8.92-12-8.92-20C3.88,12,11.25,3.88,21.33,3.88S38.79,12,38.79,23.27c0,12.22-8.34,23.27-17.45,23.27H19.39a1.76,1.76,0,0,0-1.36.58l-1.16,1.16a11,11,0,0,0-3.3,8,1.83,1.83,0,0,0,1.94,1.94h3.88v1.55A14.69,14.69,0,0,0,25,71.37a10.65,10.65,0,0,1,4.07,8.53v3.49a1.94,1.94,0,1,0,3.88,0V79.9a14.69,14.69,0,0,0-5.62-11.64,10.65,10.65,0,0,1-4.07-8.53V58.18h3.88a1.83,1.83,0,0,0,1.94-1.94,10.67,10.67,0,0,0-2.33-6.79C35.68,46.16,42.67,35.3,42.67,23.27Zm-24.82,31A8.24,8.24,0,0,1,19.78,51l.58-.58H22.5l.58.58A8.24,8.24,0,0,1,25,54.3Z"/><path class="icon-path" d="M21.33,7.76A13.09,13.09,0,0,0,8.73,16.87a2,2,0,0,0,1.16,2.52c.19,0,.39.19.58.19a1.86,1.86,0,0,0,1.75-1.36,9.72,9.72,0,0,1,9.12-6.59A1.83,1.83,0,0,0,23.27,9.7,1.83,1.83,0,0,0,21.33,7.76Z"/></svg>
						</div>
						<h3>Plan Events</h3>
						<p>Interested in helping shape the future of CC events like Summit and CC Salons? Receive notifications about upcoming events.<br>
							Get in touch with us at <a href="mailto:events@creativecommons.org">events@creativecommons.org</a></p>
					</div>
					<div class="hub-grid-block cell">
						<div class="hub-grid-block-svg">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95.11 85.17"><title>hub</title><path class="icon-path" d="M7.16,1.08a7.26,7.26,0,0,1,7.19,7.36A7.29,7.29,0,0,1,7.16,15.8,7.26,7.26,0,0,1,0,8.44,7.29,7.29,0,0,1,7.16,1.08Z"/><path class="icon-path" d="M2.51,56.45V81.58a3.63,3.63,0,0,0,3.59,3.6H8.27a3.63,3.63,0,0,0,3.59-3.6V56.45h2.48V31.32l4.44,4.44a3.75,3.75,0,0,0,3.17,1h.26a3.54,3.54,0,0,0,1-.37L35.59,29.2a3.67,3.67,0,0,0,1.32-4.92h0A3.66,3.66,0,0,0,32,23L22,28.78,13,20c-1.59-1.53-3.43-3-5.81-3A7.2,7.2,0,0,0,0,24.23V56.5H2.51Z"/><path class="icon-path" d="M57.25,35.66l12.89-3.44L76.38,26l-.05-.37a7.18,7.18,0,1,1,14.21-2.06l4.6,32-2.43.37V81.58a3.63,3.63,0,0,1-3.59,3.6H86.95a3.63,3.63,0,0,1-3.59-3.6V57.24l-2.43.37L77.65,34.86l-2.8,2.8A3.68,3.68,0,0,1,73,38.89l-13.9,3.7a3.59,3.59,0,0,1-1.85-6.93Z"/><path class="icon-path" d="M77.8,1.95a7.26,7.26,0,0,0-7.19,7.36A7.19,7.19,0,1,0,85,9.31,7.29,7.29,0,0,0,77.8,1.95Z"/><path class="icon-path" d="M40.29,61.21H54.66A4.76,4.76,0,0,1,59.42,66V80.36a4.76,4.76,0,0,1-4.76,4.76H40.29a4.76,4.76,0,0,1-4.76-4.76V66A4.76,4.76,0,0,1,40.29,61.21Z"/><path class="icon-path" d="M40.29,34.92H54.61a7.15,7.15,0,0,1,1.06.11,4.75,4.75,0,0,0-2.11,5.29,4.81,4.81,0,0,0,5.87,3.39V54a4.77,4.77,0,0,1-4.76,4.76H40.34A4.77,4.77,0,0,1,35.59,54V39.68a4.77,4.77,0,0,1,4.76-4.76Z"/><path class="icon-path" d="M23.65,14.29,36,7a4.8,4.8,0,0,1,6.55,1.69l7.29,12.38a4.81,4.81,0,0,1-1.69,6.56L35.8,34.92a4.77,4.77,0,0,1-6.08-1.06l6.45-3.7a4.8,4.8,0,0,0-4.81-8.31l-6.55,3.81L22,20.85a4.81,4.81,0,0,1,1.69-6.56Z"/></svg>
						</div>
						<h3>Contribute Your skills</h3>
						<p>Use your language skills and help us with <a href="https://wiki.creativecommons.org/wiki/Translate">translation</a>. <br>
							Are you a developer? Join our <a href="https://creativecommons.github.io/" target="_blank">developer community</a> to learn about how to integrate CC license information and help us improve our tools. </p>
					</div>
					<div class="hub-grid-block cell">
						<div class="hub-grid-block-svg">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 63.3 84.7"><title>hub</title><path class="icon-path" d="M31.8,0A17.71,17.71,0,0,0,14,17.8H0V72A12.42,12.42,0,0,0,12.2,84.7H51.3c6.9,0,12-5.8,12-12.7V17.8H49.5A17.63,17.63,0,0,0,31.8,0Zm0,5.1A12.82,12.82,0,0,1,44.6,17.9H19A12.7,12.7,0,0,1,31.8,5.1Zm12,46.3L32.5,62.7a.91.91,0,0,1-.7.3,1.08,1.08,0,0,1-.7-.3L19.8,51.4a7.46,7.46,0,0,1,.1-10.6,7.17,7.17,0,0,1,5.1-2A7.91,7.91,0,0,1,30.4,41l1.3,1.3h.2L33.2,41a7.62,7.62,0,0,1,10.5-.2A7.45,7.45,0,0,1,43.8,51.4Z"/></svg>
						</div>
						<h3>Show Your Support</h3>
						<p>We produce a variety of swag for ages 0-100 (and beyond) as well as books, stickers, and pins! Show your CC pride by supporting our swag campaigns.</p>
					</div>
				</div><!--/.hub-grid-row-->
			</div>
		</div>	
	</div>
</div>
<?php get_footer();
