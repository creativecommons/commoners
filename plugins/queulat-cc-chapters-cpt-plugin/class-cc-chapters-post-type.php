<?php

use Queulat\Post_Type;

class Cc_Chapters_Post_Type extends Post_Type {
	public function get_post_type() : string {
		return 'cc_chapters';
	}
	public function get_post_type_args() : array {
		return [
			'label'                 => __('Chapters', 'cpt_cc_chapters'),
			'labels'                => [
				'name'                     => __('Chapters', 'cpt_cc_chapters'),
				'singular_name'            => __('Chapters', 'cpt_cc_chapters'),
				'add_new'                  => __('Add New', 'cpt_cc_chapters'),
				'add_new_item'             => __('Add New Page', 'cpt_cc_chapters'),
				'edit_item'                => __('Edit Page', 'cpt_cc_chapters'),
				'new_item'                 => __('New Page', 'cpt_cc_chapters'),
				'view_item'                => __('View Page', 'cpt_cc_chapters'),
				'view_items'               => __('View Pages', 'cpt_cc_chapters'),
				'search_items'             => __('Search Pages', 'cpt_cc_chapters'),
				'not_found'                => __('No pages found.', 'cpt_cc_chapters'),
				'not_found_in_trash'       => __('No pages found in Trash.', 'cpt_cc_chapters'),
				'parent_item_colon'        => __('Parent Page:', 'cpt_cc_chapters'),
				'all_items'                => __('Chapters', 'cpt_cc_chapters'),
				'archives'                 => __('Chapters', 'cpt_cc_chapters'),
				'attributes'               => __('Page Attributes', 'cpt_cc_chapters'),
				'insert_into_item'         => __('Insert into page', 'cpt_cc_chapters'),
				'uploaded_to_this_item'    => __('Uploaded to this page', 'cpt_cc_chapters'),
				'featured_image'           => __('Featured Image', 'cpt_cc_chapters'),
				'set_featured_image'       => __('Set featured image', 'cpt_cc_chapters'),
				'remove_featured_image'    => __('Remove featured image', 'cpt_cc_chapters'),
				'use_featured_image'       => __('Use as featured image', 'cpt_cc_chapters'),
				'filter_items_list'        => __('Filter pages list', 'cpt_cc_chapters'),
				'items_list_navigation'    => __('Pages list navigation', 'cpt_cc_chapters'),
				'items_list'               => __('Pages list', 'cpt_cc_chapters'),
				'item_published'           => __('Page published.', 'cpt_cc_chapters'),
				'item_published_privately' => __('Page published privately.', 'cpt_cc_chapters'),
				'item_reverted_to_draft'   => __('Page reverted to draft.', 'cpt_cc_chapters'),
				'item_scheduled'           => __('Page scheduled.', 'cpt_cc_chapters'),
				'item_updated'             => __('Page updated.', 'cpt_cc_chapters'),
				'menu_name'                => __('Chapters', 'cpt_cc_chapters'),
				'name_admin_bar'           => __('Chapters', 'cpt_cc_chapters'),
			],
			'description'           => __('Global Network chapters Wordpress Custom Post Type', 'cpt_cc_chapters'),
			'public'                => true,
			'hierarchical'          => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-location',
			'capability_type'       => [
				0 => 'cc_chapter',
				1 => 'cc_chapters',
			],
			'map_meta_cap'          => true,
			'register_meta_box_cb'  => null,
			'taxonomies'            => [],
			'has_archive'           => true,
			'query_var'             => 'cc_chapters',
			'can_export'            => true,
			'delete_with_user'      => true,
			'rewrite'               => [
				'with_front' => true,
				'feeds'      => true,
				'pages'      => true,
				'slug'       => 'cc_chapters',
				'ep_mask'    => 1,
			],
			'supports'              => [
				0 => 'title',
				1 => 'editor',
				2 => 'thumbnail',
				3 => 'excerpt',
			],
			'show_in_rest'          => true,
			'rest_base'             => false,
			'rest_controller_class' => false
		];
	}
}
