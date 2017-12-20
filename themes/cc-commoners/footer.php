

</div><!-- .page-body -->


<footer>


  <div class="footer-content">
  
    <a href="/" class="logo"><span>CC Network</span></a>

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

  </div>
  
</footer>

<?php wp_footer(); ?>
</body>
</html>