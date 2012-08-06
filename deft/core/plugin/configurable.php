<?php

/**
 * Interface for plugins that need to read Deft's config.yml
 */

namespace Deft\Core\Plugin;

use Deft\Core\Config;

interface Configurable {
	public function setConfig(Config $config);
}
