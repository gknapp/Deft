<?php

namespace Deft\Core;

use Deft\Core\Blog\Post;
use dflydev\markdown\MarkdownParser;
use \RuntimeException;

class Publisher {
	
	private $parser;

	public function __construct(FileManager $fileManager, Parser $parser) {
		$this->fileManager = $fileManager;
		$this->parser = $parser;
	}

	public function publish() {
		$layout = $this->loadDefaultLayout();
		$posts = $this->loadPosts();

		$document = $this->parser->parse($layout);
		// echo $document;
	}

	private function loadPosts() {
		$files = $this->findPosts(dirname(__DIR__) . '/../posts');
		$posts = array();

		try {
			foreach ($files as $file) {
				$data = $this->fileManager->load($file);
				$post = new Post(new MarkdownParser);
				$post->parse($data);
				echo $post->getTitle() . PHP_EOL;
				echo $post->getIntro() . PHP_EOL;
				$posts[] = $post;
			}
		} catch (RuntimeException $ex) {
			error_log($ex->getMessage() . ' in: ' . basename($file));
		}

		return $posts;
	}

	private function findPosts($dir) {
		if (!$this->fileManager->fileExists($dir)) {
			throw new RuntimeException('posts directory not found: ' . $dir);
		}

		$posts = $this->fileManager->listDirectory($dir, '.md');

		if (!empty($posts)) {
			sort($posts);
		}

		return $posts;
	}

	private function loadDefaultLayout() {
		$layoutFile = dirname(__DIR__) . '/../templates/layout/default.html';

		if (!is_readable($layoutFile)) {
			throw new RuntimeException('Layout not found: ' . $layoutFile);
		}

		$layout = $this->fileManager->loadAsArray($layoutFile);
		return $layout;
	}

}
