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
                    <div id="world-map-info">
                        <h2 class="chapter-title">CC titirilquen</h2>
                        <p class="chapter-line">Founded on <span class="chapter-date">29 May, 2018</span> </p>
                        <p class="chapter-lead">Chapter Lead: <span class="chapter-lead-name">Juan Carlos bodoque</span></p>
                        <p class="chapter-buttons">
                            <a href="#" class="button warning contact">Contact</a>
                            <a href="#" class="button warning url">Web</a>
                            <a href="#" class="button warning meet">First Meet</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>