<?php

show_admin_bar(false);

// disable the admin bar
add_filter('show_admin_bar', '__return_false');

function cc_commoners_theme_setup () {
    register_nav_menus(
        array(
            'top'    => __( 'Top Menu', 'cc-commoners' ),
            'bottom' => __( 'Bottom Menu', 'cc-commoners' )
        )
    );
}

add_action( 'after_setup_theme', 'cc_commoners_theme_setup' );

function cc_commoners_widgets () {
    unregister_sidebar( 'sidebar-2' );
    unregister_sidebar( 'sidebar-3' );
    register_sidebar(
        array(
            'name'          => __( 'Footer Text', 'cc-commoners' ),
            'id'            => 'sidebar-footer-text',
            'description'   => __( 'Add widgets here to appear in your footer.', 'cc-commoners' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        )
    );
}

add_action( 'widgets_init', 'cc_commoners_widgets', 11 );

function cc_commoners_theme_scripts () {

    wp_enqueue_script(
        'swiper',
        get_theme_file_uri( '/assets/js/swiper.js' ),
        array(),
        '1.0',
        true
    );

    wp_enqueue_script(
        'cc-commoners',
        get_theme_file_uri( '/assets/js/cc-commoners.js' ),
        array(),
        '1.0',
        true
    );
    wp_enqueue_style('dashicons');
    $parent_style = 'twentyseventeen-style';
    wp_enqueue_style(
        $parent_style,
        // We do want template_directory, as this is our parent theme's css
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'foundation-base',
        get_stylesheet_directory_uri() . '/assets/css/foundation.min.css',
        array($parent_style)
    );

    wp_enqueue_style(
        'cc-commoners-gf',
        get_stylesheet_directory_uri() . '/assets/css/swiper.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style( 'cc-commoners',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_style(
        'cc-commoners-style-extra',
        get_theme_file_uri( '/assets/css/extra.css' ),
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'load-font-awesome',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'
    );
    wp_enqueue_style(
        'load-roboto-condensed',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i|Roboto+Condensed'
    );
}


add_action( 'wp_enqueue_scripts', 'cc_commoners_theme_scripts', 100);

function cc_commoners_replace_last_nav_item ( $items, $args ) {
  return substr_replace(
      $items,
      '',
      strrpos( $items, $args->after ),
      strlen( $args->after )
  );
}

add_filter( 'wp_nav_menu', 'cc_commoners_replace_last_nav_item', 100, 2 );
