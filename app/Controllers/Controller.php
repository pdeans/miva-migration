<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Controller
{
	protected $params = [];

	public function setParams(array $params)
	{
		$this->params = $params;

		if (!isset($this->params['page'])) {
			$this->params['page'] = 1;
		}

		if (!isset($this->params['total'])) {
			$this->params['total'] = 0;
		}

		if (!isset($this->params['offset'])) {
			$this->params['offset'] = 0;
		}

		if (!isset($this->params['limit'])) {
			$this->params['limit'] = 25;
		}
	}

	public function getParams(array $query_params, $set_params = false)
	{
		if ($set_params) {
			$this->setParams($query_params);
		}

		return $this->params;
	}

	public function getTotalPages($total, $limit)
	{
		return ceil((float)$total / $limit);
	}

	public function getNextUrl(Request $request, array $params)
	{
		$uri = $request->getUri();

		$params['page']   = (int)$params['page'] + 1;
		$params['offset'] = (int)$params['offset'] + (int)$params['limit'];

		return $uri->getScheme().'://'.$request->getServerParam('HTTP_HOST').
			($uri->getBasePath() ? rtrim($uri->getBasePath(), '/') : '').
			'/'.ltrim($uri->getPath(), '/').
			'?'.http_build_query($params);
	}

	public function getProgressComplete($completed, $total)
	{
		return  round(((float)$completed / $total) * 100, 2);
	}

	public function log($file_path, $message, $append = true)
	{
		// Create the directory if it does not exist
		$this->checkOrCreateDirPath($file_path);

		if ($append) {
			file_put_contents($file_path, date('[m/d/Y H:i:s]')."\t$message".PHP_EOL, FILE_APPEND);
		}
		else {
			file_put_contents($file_path, date('[m/d/Y H:i:s]')."\t$message".PHP_EOL);
		}
	}

	public function logRequest($file_path, $request, $page, $total)
	{
		// Create the directory if it does not exist
		$this->checkOrCreateDirPath($file_path);

		file_put_contents($file_path, $request);
	}

	public function logResponse($file_path, $response, $page, $total)
	{
		// Create the directory if it does not exist
		$this->checkOrCreateDirPath($file_path);

		file_put_contents($file_path, "$page / $total".$response.PHP_EOL, FILE_APPEND);
	}

	public function clearDir($dir_path)
	{
		// Create the directory if it does not exist
		$this->checkOrCreateDirPath($dir_path);

		$files = glob(rtrim($dir_path, '/').'/*');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	public function clearLog($file_path)
	{
		// Create the directory if it does not exist
		$this->checkOrCreateDirPath($file_path);

		file_put_contents($file_path, '');
	}

	protected function checkOrCreateDirPath($path)
	{
		$check_path = (pathinfo($path, PATHINFO_EXTENSION) ? dirname($path) : $path);

		if (!file_exists($check_path)) {
			mkdir($check_path, '0777', true);
		}
	}
}