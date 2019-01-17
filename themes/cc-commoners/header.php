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


    <header class="site-header" >
        <div class="grid-container">
            <div class="cell grid-x grid-padding-x align-center">
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

      <!--
        <div class="container-top-message">
          
          <div class="top-message form-top-text">
            
            <p>If you would like to join the Creative Commons Global Network as an individual human being, here’s where you can do so.
            <br>
            <br>
            To join the Creative Commons Global network you will need:
            - A CCID login account. If you don’t have one don’t worry, it only takes a minute to <a href="#signup-now">sign up here</a>.
            <br>
            Two people who are already part of the network and know you well enough to vouch for you. You can find them on this site.
            <br>
            - A name that people know you by. This doesn’t have to be the name on your birth certificate but should be one that you are known by in the community.
            <br>
            Once you have completed the sign-up process:
            <br>
            <br>
            You will have agreed to and be bound by the Global Network Code of Conduct and the usage policy for this site.
            <br>
            Your application will have been viewed by the people you have nominated to vouch for you, and by the Global Council.
            <br>
            Your photograph, username, and the social media account names you provide during sign-up will be published and publicly visible on this site.</p>
            
          </div>
          
        </div>
      -->

      <div class="signup-bg">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/i/bg-form-page.jpg">
      </div>

    <?php } ?>


<?php if( is_page( array(132, 134, 136) ) || is_page_template('page-platform.php') || is_page_template('page-chapter.php') || is_search() ){ ?>

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
    <div class="page-body <?php if( is_front_page() || is_page( array(4, 104, 121, 124) ) ){ echo ' push-page-body'; } ?>">
