<?php

namespace Deft\Plugin;

use Deft\Plugin;

class Posts extends Plugin {

	private $posts;

	public function setPosts(array $posts) {
		$this->posts = $posts;
	}

	public function last($num = 1) {
		// get last # posts
	}

}