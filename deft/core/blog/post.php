<?php

namespace Deft\Core\Blog;

use dflydev\markdown\MarkdownParser as Parser;

class Post {

	const INTRO_TOKEN = '--more--';

	private $parser;
	private $title;
	private $intro;
	private $body;

	public function __construct(Parser $parser) {
		$this->parser = $parser;
		$this->title = '';
		$this->intro = '';
		$this->body = '';
	}

	public function parse($data) {
		$data = str_replace("\r\n", "\n", $data);

		if (false === strpos($data, "\n---\n")) {
			throw new \RuntimeException('No header found in blog post');
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
			throw new \UnexpectedValueException('No title specified');
		}

		$this->title = $attrs['title'];
	}

	private function parseBody($body) {
		if ($this->hasIntro($body)) {
			$intro = substr($body, 0, strpos($body, self::INTRO_TOKEN));
			$body = str_replace(self::INTRO_TOKEN, '', $body);
			$this->intro = $this->parseMarkdown($intro);
		}

		$this->body = $this->parseMarkdown($body);
	}

	private function hasIntro($body) {
		return strpos($body, self::INTRO_TOKEN) !== false;
	}

	private function parseMarkdown($markdown) {
		return $this->parser->transformMarkdown(trim($markdown));
	}

	public function getTitle() {
		return $this->title;
	}

	public function getIntro() {
		return $this->intro;
	}

	public function getBody() {
		return $this->body;
	}

}
