<?php
/**
 * Plugin Name: Featured members Custom Post Type Plugin
 * Plugin URI:
 * Description: 
 * Version: 0.1.0
 * Author:
 * Author URI:
 * License: GPL-3.0-or-later
 */

function Ccgnfeaturedmember_Post_Type_register_post_type() {
	require_once __DIR__ .'/class-ccgnfeaturedmember-post-type.php';
	Ccgnfeaturedmember_Post_Type::activate_plugin();
	require_once __DIR__ .'/class-ccgnfeaturedmember-post-type.php';
	require_once __DIR__ .'/class-ccgnfeaturedmember-post-query.php';
	require_once __DIR__ .'/class-ccgnfeaturedmember-post-object.php';
	require_once __DIR__ .'/class-ccgnfeaturedmember-metabox.php';
}

add_action('init', 'Ccgnfeaturedmember_Post_Type_register_post_type');
