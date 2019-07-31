<footer class="main-footer background-gray-darker inner-space">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-justify">
            <div class="cell large-3">
                <div class="logo-alignment">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/cc.iso.logo.svg" alt="Creative Commons logo">
                    <span class="text">Network</span>
                </div>
            </div>
            <div class="cell large-4">
                <div class="footer-logos">
                    <p><img src="<?php bloginfo('template_directory') ?>/assets/img/cc.iso.logo.svg" alt=""> <img src="<?php bloginfo('template_directory') ?>/assets/img/cc.by.logo.svg" alt=""></p>
                </div>
                <p>Except where otherwise noted, content on this site is licensed under a Creative Commons Attribution 4.0 International license. Icons by The Noun Project.</p>
                <nav class="footer-navigation">
                    <?php 
                        $args = array(
                            'theme_location' => 'footer',
                            'container' => '',
                            'depth' => 2,
                            'items_wrap' => '<ul id = "%1$s" class = "menu simple %2$s">%3$s</ul>'
                        );

                        wp_nav_menu($args);
                    ?>
                </nav>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
