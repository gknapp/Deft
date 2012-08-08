<?php

namespace Deft\Plugin;

use Deft\Plugin;
use Deft\Core\Plugin\Configurable;
use Deft\Core\Config;

class Blog extends Plugin implements Configurable {

	private $config;

	public function setConfig(Config $config) {
		$this->config = $config->blog;
	}

	public function author() {
		return $this->config->author;
	}

	public function name() {
		return $this->config->name;
	}

	public function strapline() {
		return $this->config->strapline;
	}

}
