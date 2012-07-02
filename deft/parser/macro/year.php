<?php

namespace Deft\Parser\Macro;

use Deft\Parser\Macro as Macro;

class Year implements Macro {
	
	public function __invoke() {
		return date('Y');
	}

}