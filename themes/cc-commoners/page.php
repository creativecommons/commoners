<?php 
	get_header();
	the_post();
?>
    <div class="grid-container space-top">
        <div class="grid-x align-center">
            <div class="large-9 cell">
                <div class="signup-form-body" id="signup-now">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>