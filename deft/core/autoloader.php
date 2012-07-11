<?php

namespace Deft\Core;

class Autoloader {
	
	public function __invoke($className) {
		$classFile = str_replace(
			'\\', DIRECTORY_SEPARATOR, strtolower(ltrim($className, '\\'))
		) . '.php';
		require $classFile;
	}

}