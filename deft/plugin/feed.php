<?php

/**
 * Interface for feed plugins that need to read an HTTP feed
 * and persist the results locally (to the database).
 */

namespace Deft\Plugin;

use Deft\Connection, Deft\Database;

interface Feed {
	public function __construct(Deft\Http $connection, Deft\Database $db);
}
