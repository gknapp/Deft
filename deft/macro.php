<?php

namespace Deft;

abstract class Macro {

	protected $name;

	abstract public function __invoke();

	public function getName() {
		if (empty($this->name)) {
			$class = new ReflectionObject($this);
			$name = strtolower($class->getName());
			$this->name = str_replace(array('\\', 'deft.macro'), array('.', ''), $name);
		}

		return $this->name;
	}
}
