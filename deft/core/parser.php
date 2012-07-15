<?php

namespace Deft\Core;

use Deft\Core\FileManager, Deft\Plugin, Deft\Macro;
use BadMethodCallException;

class Parser {

	private $fileManager;
	private $plugins;
	private $macros;

	public function __construct(FileManager $fileManager) {
		$this->fileManager = $fileManager;
		$this->plugins = array();
		$this->macros = array();
	}

	public function registerPlugin(Plugin $plugin) {
		$this->plugins[] = $plugin;
	}

	public function registerMacro($name, Macro $handler) {
		$this->macros[$name] = $handler;
	}

	public function parse($template) {
		$document = '';

		foreach ($template as $line) {
			$line = $this->parseMacros($line);
			$line = $this->parsePlugins($line);
			$document .= $line;
		}

		return $document;
	}

	private function parseMacros($line) {
		foreach ($this->macros as $name => $macro) {
			$line = str_replace('{{' . $name . '}}', $macro(), $line);
		}
		return $line;
	}

	private function parsePlugins($line) {
		foreach ($this->plugins as $plugin) {
			try {
				if ($plugin->isIn($line)) {
					$line = $plugin->interpolate($line);
				}
			} catch (BadMethodCallException $ex) {
				error_log($ex->getMessage());
			}
		}
		return $line;
	}

}