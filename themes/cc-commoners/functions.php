<?php

function cc_commoners_theme_setup () {
    register_nav_menus(
        array(
            'top'    => __( 'Top Menu', 'cc_commoners' ),
            'bottom' => __( 'Bottom Menu', 'cc_commoners' )
        )
    );
}

add_action( 'after_setup_theme', 'cc_commoners_theme_setup' );

function cc_commoners_theme_scripts () {
    wp_enqueue_script(
        'cc-commoners',
        get_theme_file_uri( '/assets/js/cc-commoners.js' ),
        array(),
        '1.0',
        true
    );
    // Theme stylesheet
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_dequeue_style( 'cc-commoners' );
    wp_enqueue_style( 'cc-commoners-style-extra',
                      get_theme_file_uri( '/assets/css/extra.css' )
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

add_action( 'wp_enqueue_scripts', 'cc_commoners_theme_scripts' );