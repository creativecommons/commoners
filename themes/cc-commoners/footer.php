

</div><!-- .page-body -->


<footer>


  <div class="footer-content">


    <a href="/" class="logo"><span>CC Network</span></a>


    <div class="footer-text-block">

      <div class="license-icons">
        <a rel="license" href="https://creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International license">
          <i class="cc-icon-cc"></i>
          <i class="cc-icon-cc-by"></i>
        </a>
      </div>

      <p>Except where otherwise noted, content on this site is licensed under a Creative Commons Attribution 4.0 International license. Icons by The Noun Project.</p>

      <nav class="bottom-nav">
        <?php wp_nav_menu( array(
              'depth'=>1,
              'after'=>'<span class="sep">|</span>',
              'theme_location' => 'bottom',
              'menu_id'        => 'bottom-menu',
        ) ); ?>
      </nav>
      
      <?php /*if ( is_active_sidebar( 'sidebar-footer-text' ) ) {
          dynamic_sidebar( 'sidebar-footer-text' );
      }*/ ?>

    </div><!--/.footer-text-block-->

  </div>
  
</footer>

<?php wp_footer(); ?>
</body>
</html>