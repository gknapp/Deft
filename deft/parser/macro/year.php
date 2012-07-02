<?php

namespace Deft\Parser\Macro;

class Year {
	
	public function __invoke() {
		return date('Y');
	}

}