<?php

use Queulat\Post_Type;

class Ccgnevents_Post_Type extends Post_Type {
	public function get_post_type() : string {
		return 'ccgnevents';
	}
	public function get_post_type_args() : array {
		return [
			'label'                 => __('Events', 'cpt_ccgnevents'),
			'labels'                => [
				'name'                     => __('Events', 'cpt_ccgnevents'),
				'singular_name'            => __('Events', 'cpt_ccgnevents'),
				'add_new'                  => __('Add New', 'cpt_ccgnevents'),
				'add_new_item'             => __('Add New Page', 'cpt_ccgnevents'),
				'edit_item'                => __('Edit Page', 'cpt_ccgnevents'),
				'new_item'                 => __('New Page', 'cpt_ccgnevents'),
				'view_item'                => __('View Page', 'cpt_ccgnevents'),
				'view_items'               => __('View Pages', 'cpt_ccgnevents'),
				'search_items'             => __('Search Pages', 'cpt_ccgnevents'),
				'not_found'                => __('No pages found.', 'cpt_ccgnevents'),
				'not_found_in_trash'       => __('No pages found in Trash.', 'cpt_ccgnevents'),
				'parent_item_colon'        => __('Parent Page:', 'cpt_ccgnevents'),
				'all_items'                => __('Events', 'cpt_ccgnevents'),
				'archives'                 => __('Events', 'cpt_ccgnevents'),
				'attributes'               => __('Page Attributes', 'cpt_ccgnevents'),
				'insert_into_item'         => __('Insert into page', 'cpt_ccgnevents'),
				'uploaded_to_this_item'    => __('Uploaded to this page', 'cpt_ccgnevents'),
				'featured_image'           => __('Featured Image', 'cpt_ccgnevents'),
				'set_featured_image'       => __('Set featured image', 'cpt_ccgnevents'),
				'remove_featured_image'    => __('Remove featured image', 'cpt_ccgnevents'),
				'use_featured_image'       => __('Use as featured image', 'cpt_ccgnevents'),
				'filter_items_list'        => __('Filter pages list', 'cpt_ccgnevents'),
				'items_list_navigation'    => __('Pages list navigation', 'cpt_ccgnevents'),
				'items_list'               => __('Pages list', 'cpt_ccgnevents'),
				'item_published'           => __('Page published.', 'cpt_ccgnevents'),
				'item_published_privately' => __('Page published privately.', 'cpt_ccgnevents'),
				'item_reverted_to_draft'   => __('Page reverted to draft.', 'cpt_ccgnevents'),
				'item_scheduled'           => __('Page scheduled.', 'cpt_ccgnevents'),
				'item_updated'             => __('Page updated.', 'cpt_ccgnevents'),
				'menu_name'                => __('Events', 'cpt_ccgnevents'),
				'name_admin_bar'           => __('Events', 'cpt_ccgnevents'),
			],
			'description'           => __('', 'cpt_ccgnevents'),
			'public'                => true,
			'hierarchical'          => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-calendar-alt',
			'capability_type'       => [
				0 => 'ccgnevent',
				1 => 'ccgnevents',
			],
			'map_meta_cap'          => true,
			'register_meta_box_cb'  => null,
			'taxonomies'            => [],
			'has_archive'           => true,
			'query_var'             => 'ccgnevents',
			'can_export'            => true,
			'delete_with_user'      => true,
			'rewrite'               => [
				'with_front' => true,
				'feeds'      => true,
				'pages'      => true,
				'slug'       => 'ccgnevents',
				'ep_mask'    => 1,
			],
			'supports'              => [
				0 => 'title',
				1 => 'editor',
				2 => 'thumbnail',
			],
			'show_in_rest'          => true,
			'rest_base'             => false,
			'rest_controller_class' => false
		];
	}
}
