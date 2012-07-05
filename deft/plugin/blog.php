<?php

namespace Deft\Plugin;

use Deft\Plugin as Plugin;

class Blog extends Plugin {

	private $config;

	public function __construct($config) {
		$this->config = $config;
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

	public function entries() {
		return 'Welcome to my blog';
	}
}