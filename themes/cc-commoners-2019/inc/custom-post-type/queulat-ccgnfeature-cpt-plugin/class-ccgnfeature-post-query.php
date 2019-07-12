<?php

use Queulat\Post_Query;

class Ccgnfeature_Post_Query extends Post_Query {
	public function get_post_type() : string {
		return 'ccgnfeature';
	}
	public function get_decorator() : string {
		return Ccgnfeature_Post_Object::class;
	}
	public function get_default_args() : array {
		return [];
	}
}
