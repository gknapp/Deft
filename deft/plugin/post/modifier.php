<?php

/**
 * Interface for feed plugins that need to read an HTTP feed
 * and persist the results locally (to the database).
 */

namespace Deft\Plugin\Post;

use Deft\Core\Blog\Posts;

interface Modifier {
	public function __construct(Deft\Core\Blog\Posts $posts);
}
