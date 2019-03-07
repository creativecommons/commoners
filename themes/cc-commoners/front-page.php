<?php get_header(); ?>


<div class="grid-container">
	<div class="grid-x">
		<div class="cell large-12">
			<div class="container-top-message">
				<div class="top-message">
					<div class="grid-x grid-padding-x align-center align-middle">
						<div class="cell large-4">
							<h2>Welcome to the CC Global Network Community Site</h2>
						</div>
						<div class="large-5 cell">
							<p class="to-r">The Creative Commons Global Network works together to realize our shared values and build relationships around the world.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="grid-container">
	<div class="grid-x">
		<div class="large-12 cell">
			<figure class="main-slideshow swiper-container">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<img src="<?php echo site_url('wp-content/uploads/2018/01/slide-1.jpg') ?>" />
						<div class="slide-caption">
							<h3>Get Involved</h3>
							<p>
								Are you an individual who is interested in joining the global movement for the commons?
							</p>
							<a class="slide-caption-btn" href="<?php echo site_url('get-involved') ?>">Find out how you can get involved!</a>
						</div>
					</div>
					<div class="swiper-slide">
						<img src="<?php echo site_url('wp-content/uploads/2018/01/slide-2.jpg') ?>"/>
						<div class="slide-caption">
							<h3>What do we do</h3>
							<p>
								The Creative Commons Global Network is a network made of people and institutions working to strengthen the Commons
							</p>
							<a class="slide-caption-btn" href="<?php echo site_url('about') ?>">More about the CC Network</a>
						</div>
					</div>
				</div>
			</figure>
		</div>
	</div>

	<section class="grid-x large-up-3 block-section">
		<div class="block cell">
			<h3>Individual Sign Up</h3>
			<p>
				Are you interested in joining the global movement for the commons?
			</p>
			<a class="block-link" href="/sign-up/individual/">Become a member</a>
		</div>
		<div class="block cell">
			<h3>Institutional Sign Up</h3>
			<p>
				If you are an existing or prospective institutional member, please visit the institutional membership page to learn more.
			</p>
			<a class="block-link" href="http://ccnetwork.lo/sign-up/institution/">Institutional Sign Up</a>
		</div>
		<div class="block cell">
			<h3>Chapters</h3>
			<p>
				Chapters are the way we organize locally. Check here if we there's already a group of volunteers working in your country.
			</p>
			<a class="block-link" href="http://ccnetwork.lo/chapters/">Read more</a>
		</div>
	</section>
</div>

<?php get_footer();
