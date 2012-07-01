<?php

namespace Deft;
use Deft\FileManager as FileManager;

class Layout {

	protected $fileManager;
	protected $plugins;

	public function __construct(FileManager $fileManager, array $plugins) {
		$this->fileManager = $fileManager;
		$this->registerPlugins($plugins);
	}

	protected function registerPlugins($plugins) {
		$this->handlers = array();

		foreach ($plugins as $mapping) {
			list($plugin, $handler) = each($mapping);
			$this->handlers[$plugin] = $handler;
		}
	}

	public function parseLayout($layout) {
		if (!file_exists($layout)) {
			throw new InvalidArgumentException('Layout not found: ' . $layout);
		}
		$template = $this->fileManager->loadAsArray($layout);

		foreach ($template as $line) {
			$line = $this->parseLine($line);
			if ($line->containsPlugin()) {
				$line->subtitute();
			}
		}
	}

}