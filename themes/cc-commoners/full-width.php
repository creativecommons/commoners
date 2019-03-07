<?php
/* Template Name: Full Width */
	get_header();
	the_post();
?>
<div class="grid-container space-top">
	<div class="grid-x grid-padding-x align-center">
		<div class="large-10 cell">
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php if (is_page('members')) : ?>
					<p><a href="<?php echo site_url('members/type/institution') ?>">Institutional members</a></p>
					<div>You can search for (and contact) Members who have expressed interest in joining a Chapter <a href="/search-members-chapter-interest/">here</a>.</div>
				<?php endif ?>
			</header>
			<section class="entry-content">
				<div class="post-content">
					<?php the_content(); ?>
				</div>
			</section>
		</div>
	</div>
</div>
<?php get_footer(); ?>