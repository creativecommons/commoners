<?php

use Queulat\Post_Type;

class Ccgnfeaturedmember_Post_Type extends Post_Type {
	public function get_post_type() : string {
		return 'ccgnfeaturedmember';
	}
	public function get_post_type_args() : array {
		return [
			'label'                 => __('Featured members', 'cpt_ccgnfeaturedmember'),
			'labels'                => [
				'name'                     => __('Featured members', 'cpt_ccgnfeaturedmember'),
				'singular_name'            => __('Featured members', 'cpt_ccgnfeaturedmember'),
				'add_new'                  => __('Add New', 'cpt_ccgnfeaturedmember'),
				'add_new_item'             => __('Add New Page', 'cpt_ccgnfeaturedmember'),
				'edit_item'                => __('Edit Page', 'cpt_ccgnfeaturedmember'),
				'new_item'                 => __('New Page', 'cpt_ccgnfeaturedmember'),
				'view_item'                => __('View Page', 'cpt_ccgnfeaturedmember'),
				'view_items'               => __('View Pages', 'cpt_ccgnfeaturedmember'),
				'search_items'             => __('Search Pages', 'cpt_ccgnfeaturedmember'),
				'not_found'                => __('No pages found.', 'cpt_ccgnfeaturedmember'),
				'not_found_in_trash'       => __('No pages found in Trash.', 'cpt_ccgnfeaturedmember'),
				'parent_item_colon'        => __('Parent Page:', 'cpt_ccgnfeaturedmember'),
				'all_items'                => __('Featured members', 'cpt_ccgnfeaturedmember'),
				'archives'                 => __('Featured members', 'cpt_ccgnfeaturedmember'),
				'attributes'               => __('Page Attributes', 'cpt_ccgnfeaturedmember'),
				'insert_into_item'         => __('Insert into page', 'cpt_ccgnfeaturedmember'),
				'uploaded_to_this_item'    => __('Uploaded to this page', 'cpt_ccgnfeaturedmember'),
				'featured_image'           => __('Featured Image', 'cpt_ccgnfeaturedmember'),
				'set_featured_image'       => __('Set featured image', 'cpt_ccgnfeaturedmember'),
				'remove_featured_image'    => __('Remove featured image', 'cpt_ccgnfeaturedmember'),
				'use_featured_image'       => __('Use as featured image', 'cpt_ccgnfeaturedmember'),
				'filter_items_list'        => __('Filter pages list', 'cpt_ccgnfeaturedmember'),
				'items_list_navigation'    => __('Pages list navigation', 'cpt_ccgnfeaturedmember'),
				'items_list'               => __('Pages list', 'cpt_ccgnfeaturedmember'),
				'item_published'           => __('Page published.', 'cpt_ccgnfeaturedmember'),
				'item_published_privately' => __('Page published privately.', 'cpt_ccgnfeaturedmember'),
				'item_reverted_to_draft'   => __('Page reverted to draft.', 'cpt_ccgnfeaturedmember'),
				'item_scheduled'           => __('Page scheduled.', 'cpt_ccgnfeaturedmember'),
				'item_updated'             => __('Page updated.', 'cpt_ccgnfeaturedmember'),
				'menu_name'                => __('Featured members', 'cpt_ccgnfeaturedmember'),
				'name_admin_bar'           => __('Featured members', 'cpt_ccgnfeaturedmember'),
			],
			'description'           => __('', 'cpt_ccgnfeaturedmember'),
			'public'                => true,
			'hierarchical'          => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-awards',
			'capability_type'       => [
				0 => 'ccgnfeaturedmember',
				1 => 'ccgnfeaturedmembers',
			],
			'map_meta_cap'          => true,
			'register_meta_box_cb'  => null,
			'taxonomies'            => [],
			'has_archive'           => true,
			'query_var'             => 'ccgnfeaturedmember',
			'can_export'            => true,
			'delete_with_user'      => true,
			'rewrite'               => [
				'with_front' => true,
				'feeds'      => true,
				'pages'      => true,
				'slug'       => 'featured-member',
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
