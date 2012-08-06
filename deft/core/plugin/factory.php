<?php

namespace Deft\Core\Plugin;

use Deft\Plugin;
use Deft\Core\FileManager, Deft\Core\Config;
use Deft\Core\Connection\Curl, Deft\Core\Connection\Http;
use \Pdo, \RuntimeException, \UnexpectedValueException;

class Factory {

	private $fileManager;
	private $config;
	private $posts;

	public function __construct(FileManager $fileManager, Config $config, array $posts) {
		$this->fileManager = $fileManager;
		$this->config = $config;
		$this->posts = $posts;
	}

	public function loadPlugins() {
		$pluginDir = dirname(__DIR__) . '/../plugin';

		if (!$this->fileManager->fileExists($pluginDir)) {
			throw new RuntimeException('Plugin directory not found: ' . $pluginDir);
		}

		$plugins = array();

		foreach ($this->fileManager->listDirectory($pluginDir, '.php') as $pluginFile) {
			$pluginName = basename($pluginFile, '.php');
			$pluginName = 'Deft\\Plugin\\' . ucfirst($pluginName);
			$plugins[] = $this->satisfyPluginDependencies(new $pluginName);
		}

		return $plugins;
	}

	private function satisfyPluginDependencies(Plugin $plugin) {
		if ($plugin instanceof Configurable) {
			$plugin->setConfig($this->config);
		}

		if ($plugin instanceof Feed) {
			$plugin->setHttpConnection($this->createHttp());
		}

		if ($plugin instanceof Persistent) {
			$plugin->setDb($this->createDb());
		}

		if ($plugin instanceof PostModifier) {
			$plugin->setPosts($this->posts);
		}

		return $plugin;
	}

	public function createHttp() {
		return new Http(new Curl);
	}

	public function createDb() {
		$db = $this->config->database;
		$type = substr($db->dsn, 0, strpos($db->dsn, ':'));

		switch ($type) {
			case 'mysql':
				$dbh = new Pdo($db->dsn, $db->username, $db->password);
				break;
			case 'pgsql':
			case 'sqlite':
				$dbh = new Pdo($db->dsn);
				break;
			default:
				throw new UnexpectedValueException('Database type not implemented');
		}

		return $dbh;
	}

}
