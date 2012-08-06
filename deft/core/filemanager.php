<?php

/**
 * What is the purpose of this class?
 * 
 * It provides a seam for mocking so Deft is testable
 */

namespace Deft\Core;

use DirectoryIterator, InvalidArgumentException;

class FileManager {
	
	public function load($file) {
		if (!$this->fileExists($file)) {
			throw new InvalidArgumentException('File not found: ' . $file);
		}

		return file_get_contents($file);
	}

	public function loadAsArray($file) {
		if (!$this->fileExists($file)) {
			throw new InvalidArgumentException('File not found: ' . $file);
		}

		return file($file);
	}

	public function fileExists($file) {
		return file_exists($file);
	}

	public function isReadable($file) {
		return is_readable($file);
	}

	public function listDirectory($path, $extension) {
		$list = array();
		$extension = ltrim($extension, '.');
		$dir = new DirectoryIterator($path);

		foreach ($dir as $item) {
			if ($item->isFile() && $item->getExtension() == $extension) {
				$list[] = $item->getPathname();
			}
		}

		return $list;
	}

	public function save($data, $file) {
		if (!is_writable($file)) {
			throw new InvalidArgumentException('Cannot write to file: ' . $file);
		}

		return file_put_contents($file, $data);
	}

}