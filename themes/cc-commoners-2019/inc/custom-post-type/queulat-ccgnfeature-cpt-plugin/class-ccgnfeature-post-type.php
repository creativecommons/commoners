<?php

use Queulat\Post_Type;

class Ccgnfeature_Post_Type extends Post_Type {
	public function get_post_type() : string {
		return 'ccgnfeature';
	}
	public function get_post_type_args() : array {
		return [
			'label'                 => __('Featured', 'cpt_ccgnfeature'),
			'labels'                => [
				'name'                     => __('Featured', 'cpt_ccgnfeature'),
				'singular_name'            => __('Featured', 'cpt_ccgnfeature'),
				'add_new'                  => __('Add New', 'cpt_ccgnfeature'),
				'add_new_item'             => __('Add New Page', 'cpt_ccgnfeature'),
				'edit_item'                => __('Edit Page', 'cpt_ccgnfeature'),
				'new_item'                 => __('New Page', 'cpt_ccgnfeature'),
				'view_item'                => __('View Page', 'cpt_ccgnfeature'),
				'view_items'               => __('View Pages', 'cpt_ccgnfeature'),
				'search_items'             => __('Search Pages', 'cpt_ccgnfeature'),
				'not_found'                => __('No pages found.', 'cpt_ccgnfeature'),
				'not_found_in_trash'       => __('No pages found in Trash.', 'cpt_ccgnfeature'),
				'parent_item_colon'        => __('Parent Page:', 'cpt_ccgnfeature'),
				'all_items'                => __('Featured', 'cpt_ccgnfeature'),
				'archives'                 => __('Featured', 'cpt_ccgnfeature'),
				'attributes'               => __('Page Attributes', 'cpt_ccgnfeature'),
				'insert_into_item'         => __('Insert into page', 'cpt_ccgnfeature'),
				'uploaded_to_this_item'    => __('Uploaded to this page', 'cpt_ccgnfeature'),
				'featured_image'           => __('Featured Image', 'cpt_ccgnfeature'),
				'set_featured_image'       => __('Set featured image', 'cpt_ccgnfeature'),
				'remove_featured_image'    => __('Remove featured image', 'cpt_ccgnfeature'),
				'use_featured_image'       => __('Use as featured image', 'cpt_ccgnfeature'),
				'filter_items_list'        => __('Filter pages list', 'cpt_ccgnfeature'),
				'items_list_navigation'    => __('Pages list navigation', 'cpt_ccgnfeature'),
				'items_list'               => __('Pages list', 'cpt_ccgnfeature'),
				'item_published'           => __('Page published.', 'cpt_ccgnfeature'),
				'item_published_privately' => __('Page published privately.', 'cpt_ccgnfeature'),
				'item_reverted_to_draft'   => __('Page reverted to draft.', 'cpt_ccgnfeature'),
				'item_scheduled'           => __('Page scheduled.', 'cpt_ccgnfeature'),
				'item_updated'             => __('Page updated.', 'cpt_ccgnfeature'),
				'menu_name'                => __('Featured', 'cpt_ccgnfeature'),
				'name_admin_bar'           => __('Featured', 'cpt_ccgnfeature'),
			],
			'description'           => __('', 'cpt_ccgnfeature'),
			'public'                => true,
			'hierarchical'          => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-format-image',
			'capability_type'       => [
				0 => 'ccgnfeature',
				1 => 'ccgnfeatures',
			],
			'map_meta_cap'          => true,
			'register_meta_box_cb'  => null,
			'taxonomies'            => [],
			'has_archive'           => true,
			'query_var'             => 'ccgnfeature',
			'can_export'            => true,
			'delete_with_user'      => true,
			'rewrite'               => [
				'with_front' => true,
				'feeds'      => true,
				'pages'      => true,
				'slug'       => 'ccgnfeature',
				'ep_mask'    => 1,
			],
			'supports'              => [
				0 => 'title',
			],
			'show_in_rest'          => true,
			'rest_base'             => false,
			'rest_controller_class' => false
		];
	}
}
