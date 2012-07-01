<?php

class Deft {
	
	protected $config;
	protected $fileManager;

	public function __construct($config = null, $autoloader = null, $fileManager = null) {
		$this->prependIncludePath();
		$this->registerAutoloader($autoloader);

		if (null == $fileManager) {
			$fileManager = new Deft\FileManager;
		}

		if (!$config instanceof Deft\Config) {
			$config = new Deft\Config\Yaml($fileManager);
			$config->load(__DIR__ . DIRECTORY_SEPARATOR . 'config.yaml');
		}

		$this->config = $config;
		$this->fileManager = $fileManager;
	}

	private function prependIncludePath() {
		$paths = explode(PATH_SEPARATOR, get_include_path());

		if (!in_array(__DIR__, $paths)) {
			array_unshift($paths, __DIR__);
			set_include_path(join(PATH_SEPARATOR, $paths));
		}
	}

	private function registerAutoloader($autoloader) {
		include_once 'deft' . DIRECTORY_SEPARATOR . 'autoloader.php';

		if (!is_callable($autoloader)) {
			$loader = new Deft\Autoloader;
			$autoloader = array($loader, 'load');
		}

		spl_autoload_register($autoloader);
	}

	public function publish() {
		try {
			$this->attemptPublish();
		} catch (Exception $ex) {
			error_log($ex->getMessage());
		}
	}

	public function attemptPublish() {
		$layoutFile = __DIR__ . DIRECTORY_SEPARATOR
					. $config->layout->path . DIRECTORY_SEPARATOR
					. $config->layout->name . '.html';
		$layout = new Deft\Layout(
			$this->fileManager,
			$this->config->layout->plugins
		);
		$layout->parse($layoutFile);
	}

}
