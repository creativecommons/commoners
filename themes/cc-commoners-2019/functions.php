
<?php

/**
* Functions: list
* @version 1.0
* @package wp-theme-cc-sotc
*/

/* Theme Constants (to speed up some common things) ------*/
define('HOME_URI', get_bloginfo( 'url' ));
define('PRE_HOME_URI',get_bloginfo('url').'/wp-content/themes');
define('SITE_NAME', get_bloginfo( 'name' ));
define('THEME_URI', get_template_directory_uri());
define('THEME_IMG', THEME_URI . '/assets/img');
define('THEME_CSS', THEME_URI . '/assets/css');
define('THEME_FONTS', THEME_URI . '/assets/fonts');
define('THEME_JS', THEME_URI. '/assets/js');

define ('MEMBERS_PAGE_ID', 4);
/*
	calling related files
*/
	include TEMPLATEPATH . '/inc/site.php';
	include TEMPLATEPATH . '/inc/render.php';
	include TEMPLATEPATH . '/inc/widgets.php';
	include TEMPLATEPATH . '/inc/helpers.php';
	include TEMPLATEPATH . '/inc/metaboxes.php';
	include TEMPLATEPATH . '/inc/bp-integration.php';
	include TEMPLATEPATH . '/inc/search.php';
	include TEMPLATEPATH . '/inc/settings.php';
	include TEMPLATEPATH . '/inc/filters.php';
	// include TEMPLATEPATH . '/inc/multilang.php';
	// include TEMPLATEPATH . '/inc/shortcodes.php';
    //include TEMPLATEPATH. '/inc/taxonomies.php';

//Custom Post type files
include TEMPLATEPATH . '/inc/custom-post-type/queulat-cc-chapters-cpt-plugin/cc-chapters-cpt-plugin.php';
include TEMPLATEPATH . '/inc/custom-post-type/queulat-ccgnfeature-cpt-plugin/ccgnfeature-cpt-plugin.php';
include TEMPLATEPATH . '/inc/custom-post-type/queulat-ccgnevents-cpt-plugin/ccgnevents-cpt-plugin.php';
include TEMPLATEPATH . '/inc/custom-post-type/queulat-ccgnfeaturedmember-cpt-plugin/ccgnfeaturedmember-cpt-plugin.php';

/* AVATAR MAX CROP SIZING */
define ( 'BP_AVATAR_THUMB_WIDTH', 150 );
define ( 'BP_AVATAR_THUMB_HEIGHT', 150 );
define( 'BP_AVATAR_FULL_WIDTH', 300 );
define( 'BP_AVATAR_FULL_HEIGHT', 300 );

/**
 * Images
 * ------
 * */
// Add theme suppor for post thumbnails
add_theme_support( 'post-thumbnails' );
// Define the default post thumbnail size

// set_post_thumbnail_size( 200, 130, true );

// Define custom thumbnail sizes
// add_image_size( $name, $width, $height, $crop );
add_image_size( 'squared', 300, 300, true );
add_image_size( 'landscape-medium', 740, 416, true );
add_image_size( 'landscape-featured', 2000, 700, true );
/*
	REGISTER SIDEBARS
*/
/*
	Theme sidebars
*/
$mandatory_sidebars = array(
	
	'Single' => array(
		'name' => 'single',
	),
	// Not logged in
    'Not logged in - Home - first row' => array(
        'name' => 'home-not-logged-first'
    ),
	'Not logged in - Home - second row' => array(
        'name' => 'home-not-logged-second'
    ),
	'Not logged in - Home - Third row' => array(
        'name' => 'home-not-logged-third'
    ),
	'Not logged in - Home - fourth row' => array(
        'name' => 'home-not-logged-fourth'
    ),
	'Not logged in - Home - fifth row' => array(
        'name' => 'home-not-logged-fifth'
    ),
	'Not logged in - Home - sixth row' => array(
        'name' => 'home-not-logged-sixth'
    ),
	// logged in but not approved yet
    'Logged inactive - Home - first row' => array(
        'name' => 'home-logged-inactive-first'
    ),
	'Logged inactive - Home - second row' => array(
        'name' => 'home-logged-inactive-second'
    ),
	'Logged inactive (no form yet) - Home - second row' => array(
        'name' => 'home-logged-inactive-no-form-second'
    ),
	'Logged inactive - Home - third row' => array(
        'name' => 'home-logged-inactive-third'
    ),
	'Logged inactive - Home - fourth row' => array(
        'name' => 'home-logged-inactive-fourth'
    ),
	'Logged inactive - Home - fifth row' => array(
        'name' => 'home-logged-inactive-fifth'
    ),
	'Logged inactive - Home - sixth row' => array(
        'name' => 'home-logged-inactive-sixth'
    ),
	// logged in and approved
    'logged in active - Home - first row' => array(
        'name' => 'home-logged-active-first'
    ),
	'logged in active - Home - second row' => array(
        'name' => 'home-logged-active-second'
    ),
	'logged in active - Home - third row' => array(
        'name' => 'home-logged-active-third'
    ),
	'logged in active - Home - forth row' => array(
        'name' => 'home-logged-active-fourth'
    ),
	'logged in active - Home - fifth row' => array(
        'name' => 'home-logged-active-fifth'
    ),
	'logged in active - Home - sixth row' => array(
        'name' => 'home-logged-active-sixth'
    ),
);
$mandatory_sidebars = apply_filters('sotc_base_mandatory_sidebars',$mandatory_sidebars);
foreach ( $mandatory_sidebars as $sidebar => $id_sidebar ) {
	register_sidebar( array(
		'name'          => $sidebar,
		'id'			=> $id_sidebar['name'],
		'before_widget' => '<section id="%1$s" class="widget %2$s">'."\n",
		'after_widget'  => '</section>',
		'before_title'  => '<header class="widget-header"><h3 class="widget-title">',
		'after_title'   => '</h3></header>'
	) );
}

/**
 * Theme specific stuff
 * --------------------
 * */

/**
 * Theme singleton class
 * ---------------------
 * Stores various theme and site specific info and groups custom methods
 **/
class site {
	private static $instance;

	protected $settings;
	public $show_welcome = true;

	const id = __CLASS__;
	const theme_ver = '20190415';
	const theme_settings_permissions = 'edit_theme_options';
	private function __construct(){
		/**
		 * Get our custom theme options so we can easily access them
		 * on templates or other admin pages
		 * */
		// $this->settings = get_option( __CLASS__ .'_theme_settings' );

		$this->actions_manager();

	}
	public function __get($key){
		return isset($this->$key) ? $this->$key : null;
	}
	public function __isset($key){
		return isset($this->$key);
	}
	public static function get_instance(){
		if ( !isset(self::$instance) ){
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	public function __clone(){
		trigger_error( 'Clone is not allowed.', E_USER_ERROR );
	}
	/**
	 * Setup theme actions, both in the front and back end
	 * */
	public function actions_manager(){
		add_action( 'after_setup_theme', array($this, 'setup_theme') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'enqueue_scripts', array($this, 'enqueue_scripts') );
		//add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
		add_action('init', array($this, 'init_functions') );
		add_action('init', array($this,'register_menus_locations') );
	}
	public function init_functions() {
		add_post_type_support( 'page', 'excerpt' );
	}
	/**
	 * Enable theme extra supports
	 * @return void
	 */
	public function setup_theme(){
		add_theme_support('post-formats', array('gallery', 'image', 'video'));
		add_theme_support('post-thumbnails');
		add_theme_support('menus');
	}

	public function register_menus_locations(){
		register_nav_menus(array(
			'main-menu' => 'Main menu',
            'main-menu-mobile' => 'Main menu mobile',
			'secondary' => 'Secondary menu',
			'footer' => 'Footer menu'
		));
	}

	public function get_post_thumbnail_url( $postid = null, $size = 'landscape-medium' ){
		if ( is_null($postid) ){
			global $post;
			$postid = $post->ID;
		}
		$thumb_id = get_post_thumbnail_id( $postid );
		$img_src  = wp_get_attachment_image_src( $thumb_id, $size );
		return $img_src ? current( $img_src ) : '';
	}

	public function enqueue_styles(){
		// Front-end styles
		wp_enqueue_style( 'Gfonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Source+Sans+Pro:400,400i,600" rel="stylesheet');
		wp_enqueue_style( 'dependencies', THEME_CSS .'/dependencies.css', array(), self::theme_ver );
        wp_enqueue_style( 'commoners_style', THEME_CSS .'/style.css', array( 'dependencies' ), self::theme_ver );
		wp_enqueue_style( 'dashicons' );
	}

	function admin_enqueue_scripts(){

		// admin scripts
	}

	function enqueue_scripts(){
		// front-end scripts
		wp_enqueue_script( 'jquery' , true);
		wp_enqueue_script( 'dependencies', THEME_JS .'/dependencies.js', array('jquery'), self::theme_ver, true );
		wp_enqueue_script( 'commoners_script', THEME_JS .'/script.js', array('jquery'), self::theme_ver, true );
		//attach data to script.js
		$ajax_data = array(
			'url' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'commoners_script', 'Ajax', $ajax_data );

		if (is_post_type_archive('cc_chapters')) {
			wp_enqueue_script( 'cc-theme-datatable', THEME_JS . '/datatables.min.js', array('jquery'), self::theme_ver, true );
			wp_enqueue_script( 'cc-theme-responsive-datatable', THEME_JS . '/responsive.datatables.min.js', array('cc-theme-datatable'), self::theme_ver, true );
			wp_enqueue_script( 'cc-commoners-chapters-panzoom',  THEME_JS . '/svgpanzoom.js', array('jquery'), self::theme_ver, true );
			wp_enqueue_script( 'cc-commoners-chapters',  THEME_JS . '/commoners-chapters.js', array('jquery'), self::theme_ver, true );
			wp_localize_script('cc-commoners-chapters', 'Ajax', $ajax_data);
			
			wp_enqueue_style( 'cc-datatables-styles', THEME_CSS . '/datatables.css', array(), self::theme_ver );
			wp_enqueue_style( 'cc-datatables-responsive-styles', THEME_CSS . '/responsive.datatables.min.css', array(), self::theme_ver );
			wp_enqueue_style( 'cc-datatables-styles-foundation', THEME_CSS . '/datatables.css', array('cc-datatables-styles'), self::theme_ver );
    	}
	}
}

/**
 * Instantiate the class object
 * */

$_s = site::get_instance();