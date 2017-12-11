<?php
if (have_posts()):
	while (have_posts()):
        the_post(); ?>

    	<?php //the_title() ?>

    	<?php the_content() ?>

<?php endwhile; ?>

<?php
else: // no posts found
?>
<p>No posts found matching your criteria.</p>
<?php
endif; // done checking for posts
?>