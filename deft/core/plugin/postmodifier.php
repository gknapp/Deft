<?php

/**
 * Interface for plugins that modify blog post output
 */

namespace Deft\Plugin;

use Deft\Core\Blog\Posts;

interface PostModifier {
	public function setPosts(Deft\Core\Blog\Posts $posts);
}
