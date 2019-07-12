<?php
/**
 * Plugin Name: Featured Custom Post Type Plugin
 * Plugin URI:
 * Description: 
 * Version: 0.1.0
 * Author:
 * Author URI:
 * License: GPL-3.0-or-later
 */

function Ccgnfeature_Post_Type_register_post_type() {
	require_once __DIR__ .'/class-ccgnfeature-post-type.php';
	Ccgnfeature_Post_Type::activate_plugin();
	require_once __DIR__ .'/class-ccgnfeature-post-type.php';
	require_once __DIR__ .'/class-ccgnfeature-post-query.php';
	require_once __DIR__ .'/class-ccgnfeature-post-object.php';
	require_once __DIR__ .'/class-ccgnfeature-metabox.php';
}

add_action('init', 'Ccgnfeature_Post_Type_register_post_type');
