

</div><!-- .page-body -->


<footer class="main-footer">
    <div class="footer-content">
    <div class="grid-container">
        <div class="grid-x grid-padding-x align-justify">
            <div class="large-4 cell">
                <a href="/" class="logo"><span>CC Network</span></a>
            </div>
            <div class="large-5 cell">
                <div class="footer-text-block">
                    <div class="license-icons">
                        <a rel="license" href="https://creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International license">
                        <i class="cc-icon-cc"></i>
                        <i class="cc-icon-cc-by"></i>
                        </a>
                    </div>

                    <p>Except where otherwise <a href="https://creativecommons.org/policies#license">noted</a>, content on this site is licensed under a <a href="https://creativecommons.org/licenses/by/4.0/">Creative Commons Attribution 4.0 International license</a>. <a href="https://creativecommons.org/website-icons">Icons</a> by The Noun Project.</p>

                    <nav class="bottom-nav">
                        <?php wp_nav_menu(array(
                            'depth' => 1,
                            'theme_location' => 'bottom',
                            'menu_id' => 'bottom-menu',
                            'items_wrap' => '<ul id = "%1$s" class = "menu align-right %2$s">%3$s</ul>'
                        )); ?>
                    </nav>
                </div><!--/.footer-text-block-->
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>