<?php

namespace Deft;

abstract class Plugin {
	
	protected $name;

	public function isIn($line) {
		return (false !== strpos($line, '{' . $this->getPluginName() . '.'));
	}

	public function interpolate($line) {
		$plugin = $this;
		return preg_replace_callback(
			"/\{" . $this->getPluginName() . "\.([^\}]+)\}/",
			function ($matches) use ($plugin) {
				$method = $matches[1];
				if (!method_exists($plugin, $method)) {
					throw new BadMethodCallException(
						"{$plugin->name}::{$method}(): method not found"
					);
				}
				return $plugin->$method();
			},
			$line
		);
	}

	protected function getPluginName() {
		if (empty($this->name)) {
			$class = new \ReflectionObject($this);
			$this->name = strtolower($class->getShortName());
		}

		return $this->name;
	}

}