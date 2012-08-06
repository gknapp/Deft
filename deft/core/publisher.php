<?php

namespace Deft\Core;

use \RuntimeException;

class Publisher {
	
	private $parser;

	public function __construct(FileManager $fileManager, Parser $parser) {
		$this->fileManager = $fileManager;
		$this->parser = $parser;
	}

	public function publish() {
		$layout = $this->loadDefaultLayout();
		$document = $this->parser->parse($layout);
		echo $document;
	}

	private function loadDefaultLayout() {
		$layoutFile = dirname(__DIR__) . '/../templates/layout/default.html';

		if (!$this->fileManager->isReadable($layoutFile)) {
			throw new RuntimeException('Layout not found: ' . $layoutFile);
		}

		$layout = $this->fileManager->loadAsArray($layoutFile);
		return $layout;
	}

}
