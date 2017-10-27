<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title(); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <!--<link rel="pingback" href="<?php //bloginfo( 'pingback_url' ); ?>" />-->
    <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?> >

    <div id="page" class="site">
      <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyseventeen' ); ?></a>

    <header id="masthead" class="site-header" role="banner">
      <div class="logo"><h1>CC Commoners</h1></div>
      <nav class="main-nav">
        <?php wp_nav_menu( array(
                     'depth'=>1,
                     'after'=>'<span class="sep">|</span>',
                     'theme_location' => 'top',
                     'menu_id'        => 'top-menu',
               ) ); ?>
      </nav>
      <form method="post" action="/" class="header-search">
        <input type="text" name="s">
          <i class="fa fa-search" aria-hidden="true"></i>
      </form>
    </header><!-- #masthead -->

      <?php
      /*
       * If a regular post or page, and not the front page, show the featured image.
       * Using get_queried_object_id() here since the $post global may not be set before a call to the_post().
       */
       if ( ( is_single() || ( is_page() && ! twentyseventeen_is_frontpage() ) ) && has_post_thumbnail( get_queried_object_id() ) ) :
         echo '<div class="single-featured-image-header">';
         echo get_the_post_thumbnail( get_queried_object_id(), 'twentyseventeen-featured-image' );
         echo '</div><!-- .single-featured-image-header -->';
       endif;
      ?>

     <div class="page-body">
       <div class="site-content-contain">
         <div id="content" class="site-content">
