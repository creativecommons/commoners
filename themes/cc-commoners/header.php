<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php bloginfo('stylesheet_directory') ?>/assets/images/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/favicon-16x16.png">
    <link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/favicon.ico" type="image/x-icon">
            <link rel="icon" href="<?php bloginfo('stylesheet_directory') ?>/assets/images/favicon.ico" type="image/x-icon">
        
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php bloginfo('stylesheet_directory') ?>/assets/images/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title(); ?></title>
    <!--<link rel="profile" href="http://gmpg.org/xfn/11" />-->

    <!--<link rel="pingback" href="<?php //bloginfo( 'pingback_url' ); ?>" />-->
    <?php //if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>

    <link rel='stylesheet' id='cc-fontello-css' href='https://d15omoko64skxi.cloudfront.net/wp-content/themes/cc/fonts/fontello/css/cc-fontello.css?ver=4.9.1' type='text/css' media='all' />
  </head>

  <body <?php body_class(); ?> >

    <header class="mobile-header show-for-small-only">
        <div class="grid-container">
            <div class="grid-x align-justify">
                <div class="cell small-5">
                    <a href="<?php echo site_url(); ?>" class="logo"><h1>CC Network</h1></a>
                </div>
                <div class="cell small-3 mobile-buttons">
                    <a href="#" class="open-mobile-menu"><span class="dashicons dashicons-menu"></span></a>
                    <a href="#" class="open-mobile-search"><span class="dashicons dashicons-search"></span></a>
                </div>
            </div>
        </div>
    </header>
    <div class="menu-mobile-container hide">
        <a class="close" href="#">
            <button class="close-button" aria-label="Close alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </a>
        <nav class="mobile-navigation">
        <?php 
            $args = array(
                'theme_location' => 'top',
                'container' => '',
                'depth' => 1,
                'items_wrap' => '<ul id = "%1$s" class = "menu vertical %2$s">%3$s</ul>'
            );

            wp_nav_menu($args);
            ?>
        </nav>
    </div>
    <div class="search-mobile-container show-for-small-only">
        <a class="close" href="#">
            <button class="close-button search" aria-label="Close alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </a>
        <?php get_search_form(); ?>
    </div>
    <header class="site-header hide-for-small-only" >
        <div class="grid-container">
            <div class="grid-x grid-padding-x align-center">
                <div class="cell small-3">
                    <a href="<?php echo site_url(); ?>" class="logo"><h1>CC Network</h1></a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="grid-container">
                <div class="grid-x align-center">
                    <div class="cell small-12">            
                        <?php wp_nav_menu( array(
                                    'depth'=>1,
                                    'theme_location' => 'top',
                                    'menu_id'        => 'top-menu',
                                    'items_wrap' => '<ul id = "%1$s" class = "menu align-center %2$s">%3$s</ul>'
                                ) ); ?>
                    </div>
                </div>
            </div>
        </nav>
        
        <?php get_search_form(); ?>
      </div>
    </header>


<?php if( is_page(array(24, 26, 63, 65, 55)) ) { ?>

      <div class="signup-bg">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/i/bg-form-page.jpg">
      </div>

    <?php } ?>


<?php if( is_page( array(132, 134, 136) ) || is_page_template('page-platform.php') || is_page_template('page-chapter.php') ){ ?>

      <?php

      global $post;

      ?>

      <div class="inner-section-title">
        <h1><?php
              if (is_search()) {
                  echo 'Search Results';
              } else {
                  echo get_the_title($post->ID);
              }
        ?></h1>
      </div>
    <?php } ?>


    <!-- this 'push-page-body' class will be remove when all templating structure will be defined  -->
    <div class="page-body <?php if( is_front_page() || is_page( array(4, 121, 124) ) ){ echo ' push-page-body'; } ?>">
