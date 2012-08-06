<?php

namespace Deft\Core;

use Deft\Core\FileManager, Deft\Plugin;
use BadMethodCallException;

class Parser {

	private $fileManager;
	private $plugins;

	public function __construct(FileManager $fileManager) {
		$this->fileManager = $fileManager;
		$this->plugins = array();
	}

	public function registerPlugin(Plugin $plugin) {
		$this->plugins[] = $plugin;
	}

	public function parse($template) {
		$document = '';

		foreach ($template as $line) {
			$document .= $this->runPlugins($line);
		}

		return $document;
	}

	private function runPlugins($line) {
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