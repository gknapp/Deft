<?php

require_once 'deft/core/autoloader.php';
require_once 'vendor/autoload.php';

use Deft\Core\Autoloader, Deft\Core\FileManager;
use Deft\Core\Parser, Deft\Core\Publisher;
use Deft\Core\Plugin\Factory as PluginFactory;
use Deft\Core\Config\Yaml as YamlConfig;
use Deft\Core\Blog\Post;
use dflydev\markdown\MarkdownParser;
use \RuntimeException;

class Deft {
	
	public function __construct() {
		$this->prependIncludePath();
		spl_autoload_register(new Autoloader);
	}

	private function prependIncludePath() {
		$paths = explode(PATH_SEPARATOR, get_include_path());

		if (!in_array(__DIR__, $paths)) {
			array_unshift($paths, __DIR__);
			set_include_path(join(PATH_SEPARATOR, $paths));
		}
	}

	public function publish() {
		try {
			$publisher = $this->createPublisher();
			$publisher->publish();
		} catch (Exception $ex) {
			error_log($ex->getMessage());
		}
	}

	private function createPublisher() {
		$fileManager = new FileManager;
		$parser = new Parser($fileManager);
		$plugins = $this->loadPlugins($fileManager);

		foreach ($plugins as $plugin) {
			$parser->registerPlugin($plugin);
		}

		return new Publisher($fileManager, $parser);
	}

	private function loadPlugins($fileManager) {
		$pluginFactory = new PluginFactory(
			$fileManager,
			$this->loadConfig($fileManager),
			$this->loadPosts($fileManager)
		);
		return $pluginFactory->loadPlugins();
	}

	private function loadConfig($fileManager) {
		$cfgFile = __DIR__ . '/config.yml';

		if (!$fileManager->fileExists($cfgFile)) {
			throw new RuntimeException('Config file not found: ' . $cfgFile);
		}

		$config = new YamlConfig($fileManager);
		$config->load($cfgFile);
		return $config;
	}

	private function loadPosts($fileManager) {
		$files = $this->findPosts($fileManager, __DIR__ . '/posts');
		$posts = array();

		foreach ($files as $file) {
			try {
				$data = $fileManager->load($file);
				$post = new Post(new MarkdownParser);
				$post->parse($data);
				$posts[] = $post;
			} catch (RuntimeException $ex) {
				error_log($ex->getMessage() . ' in: ' . basename($file));
			}
		}

		return $posts;
	}

	private function findPosts($fileManager, $dir) {
		if (!$fileManager->fileExists($dir)) {
			throw new RuntimeException('posts directory not found: ' . $dir);
		}

		$posts = $fileManager->listDirectory($dir, '.md');

		if (!empty($posts)) {
			sort($posts);
		}

		return $posts;
	}

}
