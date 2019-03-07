<?php
/* Template Name: Faqs */
	get_header(); 
	the_post();
?>
<div class="inner-section-title">
	<div class="grid-content">
		<div class="grid-x align-center">
			<header class="post-header">
				<h1 class="entry-title big"><?php the_title(); ?></h1>
			</header>
		</div>
	</div>
</div>
<div class="grid-container">
	<div class="grid-x grid-padding-x align-center">
		<div class="large-10 cell">
			<?php if (is_page('members')) : ?>
				<div class="members-meta float-left">
					<p><a href="<?php echo site_url('members/type/institution') ?>" class="button hollow secondary">View only institutional members</a></p>
					<div><em>You can search for (and contact) Members who have expressed interest in joining a Chapter <a href="/search-members-chapter-interest/">here</a>.</em></div>
				</div>
			<?php endif ?>
			<section class="entry-content">
				<div class="post-content">
					<?php the_content(); ?>
				</div>
			</section>
		</div>
	</div>
</div>

<?php get_footer(); ?>