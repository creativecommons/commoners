<?php 
    get_header();
    get_post();
?>
<section class="main-content space-top">
    <div class="grid-container">
        <div class="grid-x grid-padding-x">
            <div class="cell large-12">
                <div class="entry-header">
                    <h1 class="entry-title">Chapters</h1>
                </div>
                <div class="world-map">
                    <?php get_template_part('template-parts/maps/world','map'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>