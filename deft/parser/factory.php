<?php

namespace Deft\Parser;

use Deft\Config as Config;
use Deft\FileManager as FileManager;
use Deft\Parser as Parser;
use Deft\Plugin\Blog as Blog;

class Factory {
	
	private $config;

	public function __construct(Config $config) {
		$this->config = $config;
	}

	public function create($fileManager = null) {
		if (null == $fileManager) {
			$fileManager = new FileManager;
		}

		$parser = new Parser($fileManager);
		$parser->registerPlugin(new Blog($this->config->blog));
		$parser->registerMacro('year', new Macro\Year);
		$this->loadPlugins($fileManager);

		return $parser;
	}

	private function loadPlugins($fileManager) {
		$pluginDir = __DIR__ . '/../plugin';

		foreach ($fileManager->listDirectory($pluginDir, '.php') as $pluginFile) {
			$pluginName = basename($pluginFile, '.php');

			if ('blog' == $pluginName) {
				continue;
			}
			
			$pluginName = 'Deft\\Plugin\\' . ucfirst($pluginName);
			$plugin = new $pluginName;
			$parser->registerPlugin($plugin);
		}
	}

}