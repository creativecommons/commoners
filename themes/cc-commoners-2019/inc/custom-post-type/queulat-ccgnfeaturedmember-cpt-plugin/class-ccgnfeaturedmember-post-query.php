<?php

use Queulat\Post_Query;

class Ccgnfeaturedmember_Post_Query extends Post_Query {
	public function get_post_type() : string {
		return 'ccgnfeaturedmember';
	}
	public function get_decorator() : string {
		return Ccgnfeaturedmember_Post_Object::class;
	}
	public function get_default_args() : array {
		return [];
	}
}
