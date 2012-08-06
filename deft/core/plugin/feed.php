<?php

/**
 * Interface for feed plugins that need to read an HTTP feed
 */

namespace Deft\Plugin;

use Deft\Connection;

interface Feed {
	public function setHttpConnection(Deft\Http $connection);
}
