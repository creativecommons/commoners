<?php 
    get_header(); 
    the_post();
?>
<section class="main-content">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-center">
            <div class="cell large-8 medium-8">
                <header class="entry-header page-header">
                    <h1 class="entry-title"><?php the_title() ?></h1>
                </header>
                <section class="entry-content page-content">
                    <?php the_content(); ?>
                </section>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>