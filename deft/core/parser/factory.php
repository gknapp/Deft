<?php

namespace Deft\Core\Parser;

use Deft\Core\Config, Deft\Core\FileManager, Deft\Core\Parser;
use \RuntimeException;

class Factory {
	
	private $fileManager;

	public function __construct(FileManager $fileManager) {
		$this->fileManager = $fileManager;
	}

	public function create() {
		$parser = new Parser($this->fileManager);

		foreach ($this->loadPlugins($this->fileManager) as $plugin) {
			$parser->registerPlugin($plugin);
		}

		return $parser;
	}

	private function loadPlugins($fileManager) {
		$pluginDir = dirname(__DIR__) . '/../plugin';
		$plugins = array();

		if (!file_exists($pluginDir)) {
			throw new RuntimeException('Plugin directory not found: ' . $pluginDir);
		}

		foreach ($fileManager->listDirectory($pluginDir, '.php') as $pluginFile) {
			$pluginName = basename($pluginFile, '.php');

			if ('blog' == $pluginName) {
				continue;
			}
			
			$pluginName = 'Deft\\Plugin\\' . ucfirst($pluginName);
			$plugins[] = new $pluginName;
		}

		return $plugins;
	}

}