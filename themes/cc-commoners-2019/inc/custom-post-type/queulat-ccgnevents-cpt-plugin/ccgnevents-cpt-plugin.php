<?php
/**
 * Plugin Name: Events Custom Post Type Plugin
 * Plugin URI:
 * Description: 
 * Version: 0.1.0
 * Author:
 * Author URI:
 * License: GPL-3.0-or-later
 */
function Ccgnevents_Post_Type_register_post_type() {
	require_once __DIR__ .'/class-ccgnevents-post-type.php';
	Ccgnevents_Post_Type::activate_plugin();
	require_once __DIR__ .'/class-ccgnevents-post-type.php';
	require_once __DIR__ .'/class-ccgnevents-post-query.php';
	require_once __DIR__ .'/class-ccgnevents-post-object.php';
	require_once __DIR__ .'/class-ccgnevents-post-metabox.php';
}

add_action('init', 'Ccgnevents_Post_Type_register_post_type');
