<?php

namespace Deft\Core\Connection;

use \UnexpectedValueException;

class Curl {
	
	private $handle;

	public function __construct() {
		$this->handle = curl_init();
		$this->returnTransfer(true);
		$this->connectionTimeout(10);
	}

	public function __destruct() {
		if (is_resource($this->handle)) {
			curl_close($this->handle);
		}
	}

	public function setUrl($url) {
		$scheme = parse_url($url, PHP_URL_SCHEME);

		if (empty($scheme)) {
			throw new UnexpectedValueException('Invalid URL specified');
		}

		if ($scheme == 'https') {
			$this->verifyPeerCert(false);
		}

		$this->setOption(CURLOPT_URL, $url);
	}

	public function setOption($option, $value) {
		if (!curl_setopt($this->handle, $option, $value)) {
			throw new UnexpectedValueException("Failed to set $option to $value");
		}
	}

	public function verifyPeerCert($bool = true) {
		$this->setOption(CURLOPT_SSL_VERIFYPEER, $bool);
	}

	public function includeHeader($bool = true) {
		$this->setOption(CURLOPT_HEADER, $bool);
	}

	public function returnTransfer($bool = true) {
		$this->setOption(CURLOPT_RETURNTRANSFER, $bool);
	}

	public function convertLineEndings($bool = true) {
		$this->setOption(CURLOPT_CRLF, $bool);
	}

	public function connectionTimeout($secs) {
		$this->setOption(CURLOPT_CONNECTTIMEOUT, $secs);
	}

	public function get($url) {
		$this->setOption(CURLOPT_HTTPGET, true);
		return $this->request($url);
	}

	public function request($url) {
		$this->setUrl($url);
		$response = curl_exec($this->handle);

		if (false == $response) {
			throw new UnexpectedValueException(
				curl_error($this->handle), curl_errno($this->handle)
			);
		}

		return $response;
	}

	public function post($url) {
		$this->setOption(CURLOPT_POST, true);
		return $this->request($url);
	}

}
