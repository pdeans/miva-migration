<?php

namespace App\Migrations;

use App\Store;
use pdeans\Http\Client;

abstract class Migration
{
	protected $store = null;
	public static $MAX_CODE_LENGTH = 50;

	public function setStore(Store $store)
	{
		$this->store = $store;
	}

	public function getStore()
	{
		return $this->store;
	}

	public function generateCode($value, $separator = '-')
	{
		$value = trim($value);

		$find = [
			'/[^\w\-]+/',
			'/[\-]{2,}/',
			'/[\_]{2,}/',
			'/\_\-\_/',
			'/\-\_\-/',
		];

		$replace = [
			$separator,
			'-',
			'_',
			$separator,
			$separator,
		];

		$code = preg_replace($find, $replace, $value);

		return trim(substr($code, 0, self::$MAX_CODE_LENGTH), '-_');
	}

	public function formatNum($num, $precision = 2)
	{
		return number_format((float)$num, $precision, '.', '');
	}

	public function remoteFileExists($url)
	{
		return ((new Client)->head($url)->getStatusCode() === 200);
	}

	public function downloadFile($url, $download_path)
	{
		return file_put_contents(
			$download_path,
			file_get_contents($url, false, stream_context_create([
				'http' => [
					'header' => "User-Agent:MyAgent/1.0\r\n",
				],
			]))
		);
	}
}