<?php

/**
 * Interface for plugins that need to persist data in a database
 */

namespace Deft\Plugin;

interface Feed {
	public function setDb(\Pdo $db);
}
