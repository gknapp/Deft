<?php

define('DIR_SEP', DIRECTORY_SEPARATOR);

require 'deft' . DIR_SEP . 'core' . DIR_SEP . 'autoloader.php';
require 'vendor' . DIR_SEP . 'autoload.php';

use Deft\Core\Autoloader, Deft\Core\FileManager, Deft\Core\Publisher;
use Deft\Core\Config\Yaml as YamlConfig;
use Deft\Core\Parser\Factory as ParserFactory;
use \RuntimeException;

// deft factory needed

class Deft {
	
	private $config;
	private $fileManager;
	private $pluginManager;
	private $macroManager;

	public function __construct(FileManager $fileManager = null) {
		$this->prependIncludePath();
		spl_autoload_register(new Autoloader);

		if (null == $fileManager) {
			$fileManager = new FileManager;
		}

		$this->fileManager = $fileManager;
		$this->config = $this->loadYamlConfig();
	}

	private function prependIncludePath() {
		$paths = explode(PATH_SEPARATOR, get_include_path());

		if (!in_array(__DIR__, $paths)) {
			array_unshift($paths, __DIR__);
			set_include_path(join(PATH_SEPARATOR, $paths));
		}
	}

	private function loadYamlConfig() {
		$cfgFile = __DIR__ . DIR_SEP . 'config.yml';

		if (!$this->fileManager->fileExists($cfgFile)) {
			throw new RuntimeException('Config file not found: ' . $cfgFile);
		}

		$config = new YamlConfig($this->fileManager);
		$config->load($cfgFile);
		return $config;
	}

	public function publish($publisher = null) {
		if (null == $publisher) {
			$factory = new ParserFactory($this->fileManager);
			$publisher = new Publisher($this->fileManager, $factory->create());
		}

		try {
			$publisher->publish();
		} catch (Exception $ex) {
			error_log($ex->getMessage());
		}
	}

}
