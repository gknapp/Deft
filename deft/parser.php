<?php

namespace Deft;
use Deft\FileManager as FileManager;

class Parser {

	private $fileManager;
	private $plugins;

	public function __construct(FileManager $fileManager) {
		$this->fileManager = $fileManager;
		$this->plugins = array();
	}

	public function registerPlugin($plugin) {
		list($name, $handler) = each($plugin);
		$this->plugins[$name] = $handler;
	}

	public function registerMacro($name, $handler) {
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
		return $line;
	}

}