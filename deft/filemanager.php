<?php

namespace Deft;

class FileManager {
	
	public function load($file) {
		return file_get_contents($file);
	}

	public function loadAsArray($file) {
		return file($file);
	}

	public function save() {

	}

}