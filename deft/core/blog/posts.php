<?php

namespace Deft\Core\Blog;

class Posts {

	private $posts;

	public function setPosts(array $posts) {
		$this->posts = $posts;
	}

	public function last($num = 1) {
		// get last # posts
	}

}