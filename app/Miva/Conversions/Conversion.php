<?php

namespace App\Miva\Conversions;

use App\Miva\Store;
use Exception;
use pdeans\Http\Client;

abstract class Conversion
{
	protected $store = null;

	public function setStore(Store $store)
	{
		$this->store = $store;
	}

	public function getStore()
	{
		return $this->store;
	}

	public function formatNum($num, $precision = 2)
	{
		return number_format((float)$num, $precision, '.', '');
	}

	public function remoteFileExists($url)
	{
		try {
			return (new Client)->head($url)->getStatusCode() === 200;
		}
		catch (Exception $e) {
			return false;
		}

		return false;
	}

	public function downloadFile($url, $download_path)
	{
		return file_put_contents(
			$download_path,
			file_get_contents($url, false, stream_context_create([
				'http' => [
					'header' => "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36\r\n",
				],
			]))
		);
	}
}
