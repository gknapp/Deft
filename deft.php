<?php

define('DIR_SEP', DIRECTORY_SEPARATOR);

include_once 'deft' . DIR_SEP . 'autoloader.php';

use Deft\FileManager as FileManager;
use Deft\Config as Config;
use Deft\Config\Yaml as YamlConfig;
use Deft\Autoloader as Autoloader;
use Deft\Parser\Factory as ParserFactory;

class Deft {
	
	protected $config;
	protected $fileManager;

	public function __construct($config = null, $fileManager = null) {
		$this->prependIncludePath();
		$this->registerAutoloader();

		if (null == $fileManager) {
			$fileManager = new FileManager;
		}

		if (!$config instanceof Config) {
			$config = new YamlConfig($fileManager);
			$config->load(__DIR__ . DIR_SEP . 'config.yml');
		}

		$this->fileManager = $fileManager;
		$this->config = $config;
	}

	private function prependIncludePath() {
		$paths = explode(PATH_SEPARATOR, get_include_path());

		if (!in_array(__DIR__, $paths)) {
			array_unshift($paths, __DIR__);
			set_include_path(join(PATH_SEPARATOR, $paths));
		}
	}

	private function registerAutoloader() {
		spl_autoload_register(new Autoloader);
	}

	public function publish($parserFactory = null) {
		try {
			$this->attemptPublish($parserFactory);
		} catch (Exception $ex) {
			error_log($ex->getMessage());
		}
	}

	private function attemptPublish($parserFactory) {
		$layoutFile = $this->config->layout->path . DIR_SEP . 'default.html';
		$layout = $this->fileManager->loadAsArray($layoutFile);

		if (!is_readable($layoutFile)) {
			throw new RuntimeException('Layout not found: ' . $layoutFile);
		}

		if (null == $parserFactory) {
			$parserFactory = new ParserFactory($this->config);
		}

		$parser = $parserFactory->create($this->fileManager);
		$document = $parser->parse($layout);
		// $this->fileManager->save($document, '');
		echo $document;
	}

}
