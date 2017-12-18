<?php

namespace App\Miva;

class Store
{
	protected $store_url;
	protected $store_code;
	protected $store_root;
	protected $store_graphics;

	public function __construct(array $settings)
	{
		$this->config($settings);
	}

	public function config(array $settings)
	{
		if (isset($settings['url'])) {
			$this->setUrl($settings['url']);
		}

		if (isset($settings['code'])) {
			$this->setCode($settings['code']);
		}

		if (isset($settings['root'])) {
			$this->setRoot($settings['root']);
		}

		if (isset($settings['graphics'])) {
			$this->setGraphics($settings['graphics']);
		}
	}

	public function url()
	{
		return $this->store_url;
	}

	public function setUrl($store_url)
	{
		$this->store_url = $store_url;
	}

	public function code()
	{
		return $this->store_code;
	}

	public function setCode($store_code)
	{
		$this->store_code = $store_code;
	}

	public function root()
	{
		return $this->store_root;
	}

	public function setRoot($store_root)
	{
		$this->store_root = $store_root;
	}

	public function graphics()
	{
		return $this->store_graphics;
	}

	public function setGraphics($store_graphics)
	{
		$this->store_graphics = $store_graphics;
	}
}