<?php
if (have_posts()):
    while (have_posts()):
        the_post();

        /*the_title()*/

        the_content();
    endwhile;
else: // no posts found
?>
<p>No posts found matching your criteria.</p>
<?php
endif; // done checking for posts
?>