<?php

namespace Deft\Plugin;

use Deft\Plugin;

class Year extends Plugin {

	public function __invoke() {
		return date('Y');
	}

}
