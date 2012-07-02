<?php

namespace Deft\Config;

use Deft\Config\Accessor as Accessor;

class Node extends Accessor {

	public function __construct(array $data) {
		$this->data = $data;
	}

	public function __toString() {
		return print_r($this->data, true);
	}

}
