<?php

namespace Deft\Core\Connection;

use Deft\Core\Connection\Curl;

class Http {
	
	private $connection;

	public function __construct(Curl $curl) {
		$this->connection = $curl;
		$this->connection->includeHeader(false);
		$this->connection->convertLineEndings();
	}

	public function get($url) {
		$response = $this->connection->get($url);
		return $response;
	}

	public function post($url) {
		$response = $this->connection->post($url);
		return $response;
	}

}