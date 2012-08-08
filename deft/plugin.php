<?php

namespace Deft;

use \ReflectionObject, \BadMethodCallException;

abstract class Plugin {
	
	protected $name;

	public function __construct() {}

	public function isIn($line) {
		$name = $this->getName();
		$patterns = array('{' . $name . '.', '{' . $name . '}');

		foreach ($patterns as $pattern) {
			if (false !== strpos($line, $pattern)) {
				return true;
			}
		}

		return false;
	}

	protected function getName() {
		if (empty($this->name)) {
			$class = new ReflectionObject($this);
			$name = strtolower($class->getName());
			$this->name = str_replace(
				array('\\', 'deft.plugin.'), array('.', ''), $name
			);
		}

		return $this->name;
	}

	public function interpolate($line) {
		return preg_replace_callback(
			"/\{" . $this->getName() . "(?:\.([^\}]+))?\}/", array($this, 'execute'), $line
		);
	}

	public function execute($matches) {
		if (!isset($matches[1])) {
			if (!method_exists($this, '__invoke')) {
				throw new BadMethodCallException(
					'Not implemented: ' . $this->getName() . '::__invoke()'
				);
			}
			return $this();
		} else {
			$method = $matches[1];
			if (!method_exists($this, $method)) {
				throw new BadMethodCallException(
					'Method not found: ' . $this->getName() . '::' . $method . '()'
				);
			}

			return $this->$method();
		}
	}

}
