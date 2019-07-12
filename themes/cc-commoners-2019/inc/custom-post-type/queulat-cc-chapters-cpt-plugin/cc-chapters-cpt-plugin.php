<?php
/**
 * Plugin Name: Chapters Custom Post Type Plugin
 * Plugin URI:
 * Description: Global Network chapters Wordpress Custom Post Type
 * Version: 0.1.0
 * Author:
 * Author URI:
 * License: GPL-3.0-or-later
 */
function Cc_Chapters_Post_Type_register_post_type() {
	require_once __DIR__ .'/class-cc-chapters-post-type.php';
	Cc_Chapters_Post_Type::activate_plugin();
	require_once __DIR__ .'/class-cc-chapters-post-type.php';
	require_once __DIR__ .'/class-cc-chapters-post-query.php';
	require_once __DIR__ .'/class-cc-chapters-post-object.php';
	require_once __DIR__ . '/class-cc-chapters-metabox.php';
}

add_action('init', 'Cc_Chapters_Post_Type_register_post_type');
