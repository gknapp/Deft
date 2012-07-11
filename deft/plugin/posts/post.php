<?php

namespace Deft\Plugin\Posts;

use dflydev\markdown\MarkdownParser as Parser;
use \UnexpectedValueException;

class Post {

	private $parser;
	private $title = '';
	private $body;

	public function __construct(Parser $parser) {
		$this->parser = $parser;
	}

	public function parse($data) {
		$data = str_replace("\r\n", "\n", $data);

		if (false === strpos($data, "\n---\n")) {
			throw new RuntimeException('No header found in blog post');
		}

		list($header, $body) = explode('---', $data, 2);
		$this->parseHeader($header);
		$this->parseBody($body);
	}

	private function parseHeader($header) {
		$attrs = array();

		foreach (explode("\n", trim($header)) as $line) {
			list($attr, $value) = explode(':', $line);
			$attrs[$attr] = ltrim($value);
		}

		if (!isset($attrs['title'])) {
			throw new UnexpectedValueException('No title specified');
		}

		$this->title = $attrs['title'];
	}

	private function parseBody($markdown) {
		$markdown = trim($markdown);
		$this->body = $this->parser->transformMarkdown($markdown);
	}

	public function getTitle() {
		return $this->title;
	}

	public function getBody() {
		return $this->body;
	}

}