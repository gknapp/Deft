<?php

/**
 * Interface for plugins that modify blog post output
 */

namespace Deft\Plugin\Post;

use Deft\Core\Blog\Posts;

interface Modifier {
	public function __construct(Deft\Core\Blog\Posts $posts);
}
