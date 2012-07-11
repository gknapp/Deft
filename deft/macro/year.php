<?php

namespace Deft\Macro;

use Deft\Macro;

class Year implements Macro {
	
	public function __invoke() {
		return date('Y');
	}

}