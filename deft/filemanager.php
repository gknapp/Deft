<?php

namespace Deft;

use \DirectoryIterator as DirectoryIterator;

class FileManager {
	
	public function load($file) {
		return file_get_contents($file);
	}

	public function loadAsArray($file) {
		return file($file);
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

	public function save() {

	}

}