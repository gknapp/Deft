<?php

namespace Deft\Core\Config;

use Deft\Core\Config, Deft\Core\FileManager;
use BadFunctionCallException, RuntimeException, UnexpectedValueException;

class Yaml implements Config {

	private $fileManager;
	private $data;

	public function __construct(FileManager $fileManager) {
		if (!function_exists('yaml_parse')) {
			throw new BadFunctionCallException(
				'yaml_parse function not defined. please install YAML PECL extension'
			);
		}

		$this->fileManager = $fileManager;
	}

	public function __get($name) {
		if (array_key_exists($name, $this->data)) {
			if (is_array($this->data[$name])) {
				return (object) $this->data[$name];
			} else {
				return $this->data[$name];
			}
		}

		return null;
	}

	public function load($file) {
		$data = yaml_parse($this->fileManager->load($file));

		if (!$data) {
			throw new RuntimeException('Unable to parse config file: ' . $file);
		}

		$config = $this->inherit($data);
		$config = $this->applyOverrides($config, $data);
		$config = $this->getPlatformSection($config);
		$this->data = $config;
	}

	private function inherit($config) {
		$inherited = array();
		foreach ($config as $env => $settings) {
			if (isset($settings['extends'])) {
				$settings = $settings['extends'];
				unset($config['env']['extends']);
			}
			$inherited[$env] = $settings;
		}
		return $inherited;
	}

	private function applyOverrides($inherited, $config) {
		foreach ($config as $env => $settings) {
			foreach ($settings as $param => $children) {
				if (!isset($inherited[$env][$param]) || !is_array($inherited[$env][$param])) {
					continue;
				}
				
				$inherited[$env][$param] = array_merge($inherited[$env][$param], $children);
			}
		}
		return $inherited;
	}

	private function getPlatformSection($config) {
		$platform = 'development';

		if (!defined('DEFT_PLATFORM')) {
			if (isset($config[$platform])) {
				error_log("PLATFORM constant not defined, defaulting to '$platform'");
			} else {
				throw new UnexpectedValueException(
					"PLATFORM constant not defined, assumed '$platform' but not found in config"
				);
			}
		} else {
			$platform = DEFT_PLATFORM;
		}

		return $config[$platform];
	}

}
