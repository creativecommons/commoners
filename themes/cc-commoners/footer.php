</div><!-- #content -->
<footer>
  <div class="logo"><span>CC Commoners</span></div>
  <div class="footer-text-block">
    <?php if ( is_active_sidebar( 'sidebar-footer-text' ) ) {
        dynamic_sidebar( 'sidebar-footer-text' );
    } ?>
    <nav class="bottom-nav">
      <?php wp_nav_menu( array(
            'depth'=>1,
            'after'=>'<span class="sep">|</span>',
            'theme_location' => 'bottom',
            'menu_id'        => 'bottom-menu',
      ) ); ?>
    </nav>
  </div>
</footer>
</div><!-- .page-body -->
</div><!-- .site-content-contain -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
