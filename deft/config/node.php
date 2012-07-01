<?php

namespace Deft\Config;
use Deft\Config\Accessor as Accessor;
use \Iterator as Iterator;

class Node extends Accessor implements Iterator {

	public function __construct(array $data) {
		$this->data = $data;
	}

	public function rewind() {
		reset($this->data);
	}

	public function current() {
		return current($this->data);
	}

	public function key() {
		return key($this->data);
	}

	public function next() {
		return each($this->data);
	}

	public function valid() {
		$valid = (false !== $this->next());

		if ($valid) {
			// undo each() advance of array pointer
			prev($this->data);
		}
		return $valid;
	}

	public function __toString() {
		return print_r($this->data, true);
	}

}
