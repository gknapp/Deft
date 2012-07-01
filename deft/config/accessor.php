<?php

namespace Deft\Config;
use Deft\Config\Node as Node;

abstract class Accessor {

	protected $data;

	public function __get($name) {
		if (array_key_exists($name, $this->data)) {
			if (is_array($this->data[$name])) {
				return new Node($this->data[$name]);
			} else {
				return $this->data[$name];
			}
		}

		return null;
	}

	public function __isset($name) {
		return isset($this->data[$name]);
	}
}