<?php

//show_admin_bar(false);

// disable the admin bar
//add_filter('show_admin_bar', '__return_false');

/*
    Require related files
*/
include STYLESHEETPATH.'/inc/site.php';
include STYLESHEETPATH . '/inc/settings.php';

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
    $style_version = '2.0.5';
    $script_version = '1.0.1';
    wp_enqueue_script(
        'swiper',
        get_theme_file_uri( '/assets/js/swiper.js' ),
        array(),
        $script_version,
        true
    );

    wp_enqueue_script(
        'cc-commoners',
        get_theme_file_uri( '/assets/js/cc-commoners.js' ),
        array(),
        $script_version,
        true
    );
    $ajax_data = array(
        'url' => admin_url('admin-ajax.php') //only if we need to use ajax
    );
    wp_localize_script( 'cc-commoners', 'Ajax', $ajax_data );
    if (is_post_type_archive('cc_chapters')) {
        
        wp_enqueue_script(
            'cc-theme-datatable',
            get_stylesheet_directory_uri() . '/assets/js/datatables.min.js',
            array(),
            $script_version,
            true
        );
        wp_enqueue_script(
            'cc-theme-responsive-datatable',
            get_stylesheet_directory_uri() . '/assets/js/responsive.datatables.min.js',
            array('cc-theme-datatable'),
            $script_version,
            true
        );
        wp_enqueue_script(
            'cc-commoners-chapters',
            get_theme_file_uri('/assets/js/cc-commoners-chapters.js'),
            array(),
            $script_version,
            true
        );
        wp_localize_script('cc-commoners-chapters', 'Ajax', $ajax_data);
        wp_enqueue_style(
            'cc-datatables-styles',
            get_stylesheet_directory_uri() . '/assets/css/datatables.css',
            array(),
            wp_get_theme()->get('Version')
        );
        wp_enqueue_style(
            'cc-datatables-responsive-styles',
            get_stylesheet_directory_uri() . '/assets/css/responsive.datatables.min.css',
            array(),
            wp_get_theme()->get('Version')
        );
        wp_enqueue_style(
            'cc-datatables-styles-foundation',
            get_stylesheet_directory_uri() . '/assets/css/datatables.css',
            array('cc-datatables-styles'),
            wp_get_theme()->get('Version')
        );
    }
    wp_enqueue_style('dashicons');
    $parent_style = 'twentyseventeen-style';
    wp_enqueue_style(
        $parent_style,
        // We do want template_directory, as this is our parent theme's css
        get_template_directory_uri() . '/style.css'
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
        $style_version
    );
    wp_enqueue_style(
        'style-base',
        get_theme_file_uri('/assets/css/style.css'),
        array('cc-commoners'),
        $style_version
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


/*
    Disable admin bar except for administrators
 */

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
/*
    Filter gravityforms countries list to show them with country code
 */
add_filter('gform_countries', function ($countries) {
    $new_countries = array();

    foreach ($countries as $country) {
        $code = GF_Fields::get('address')->get_country_code($country);
        $new_countries[$code] = $country;
    }

    return $new_countries;
});