<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title(); ?></title>
    <!--<link rel="profile" href="http://gmpg.org/xfn/11" />-->

    <!--<link rel="pingback" href="<?php //bloginfo( 'pingback_url' ); ?>" />-->
    <?php //if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?> >


    <header class="site-header" >
      
      <a href="/" class="logo"><h1>CC Network</h1></a>

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
    </header>


    <?php if( is_page(array(26, 63, 65)) ) { ?>

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


    <?php if( is_page( array(132, 134, 149, 136, 4) ) || is_page_template('page-platform.php') || is_page_template('page-chapter.php') ){ ?>

      <?php

      global $post

      ?>

      <div class="inner-section-title">
        <h1><?php echo get_the_title($post->ID); ?></h1>
      </div>
    <?php } ?>


    <!-- this 'push-page-body' class will be remove when all templating structure will be defined  -->
    <div class="page-body <?php if( is_front_page() || is_page( array(4, 104, 121, 124) ) ){ echo ' push-page-body'; } ?>">