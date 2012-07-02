<?php

namespace Deft\Parser;

use Deft\Config as Config;
use Deft\FileManager as FileManager;
use Deft\Parser as Parser;

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
		$parser->registerMacro('year', new Macro\Year);

		foreach ($this->config->layout->plugins as $plugin) {
			$parser->registerPlugin($plugin);
		}

		return $parser;
	}

}