<?php

namespace Deft;

interface Config {
	public function load($file);
	public function __get($name);
	public function __isset($name);
}