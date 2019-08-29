<?php

use Queulat\Post_Query;

class Ccgnevents_Post_Query extends Post_Query {
	public function get_post_type() : string {
		return 'ccgnevents';
	}
	public function get_decorator() : string {
		return Ccgnevents_Post_Object::class;
	}
	public function get_default_args() : array {
		return [];
	}
}
