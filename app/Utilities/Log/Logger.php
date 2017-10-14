<?php

namespace App\Utilities\Log;

use Exception;

class Logger
{
	protected $base_log_path = LOG_PATH;
	protected $log_dir = '';
	protected $log_file = '';
	protected $file_append = true;

	public function __construct($base_log_path)
	{
		$this->base_log_path = rtrim($base_log_path, '/\\');
		$this->log_dir       = '';
		$this->log_file      = '';
		$this->file_append   = true;
	}

	public function getDirPath()
	{
		return $this->base_log_path.$this->log_dir;
	}

	public function setDir($dir)
	{
		$this->log_dir = DIRECTORY_SEPARATOR.trim($dir, '/\\').DIRECTORY_SEPARATOR;

		$this->checkOrCreateDirPath($this->getDirPath());

		return $this;
	}

	public function file($file)
	{
		$this->log_file = ltrim($file, '/\\');

		return $this;
	}

	public function getFilePath()
	{
		if (!$this->log_file) {
			throw new Exception('Log file is not set');
		}

		return $this->getDirPath().$this->log_file;
	}

	public function append($file_append)
	{
		$this->file_append = (bool)$file_append;

		return $this;
	}

	public function write($message)
	{
		$this->checkOrCreateDirPath(pathinfo($this->getFilePath(), PATHINFO_DIRNAME));

		if ($this->file_append) {
			return file_put_contents($this->getFilePath(), date('[m/d/Y H:i:s]')."\t$message".PHP_EOL, FILE_APPEND);
		}

		return file_put_contents($this->getFilePath(), date('[m/d/Y H:i:s]')."\t$message".PHP_EOL);
	}

	public function writeRequest($request_data, array $params)
	{
		return $this->writePrv('request', $request_data, $params);
	}

	public function writeResponse($response_data, array $params)
	{
		return $this->writePrv('response', $response_data, $params);
	}

	protected function writePrv($http_type, $data, array $params)
	{
		$this->checkOrCreateDirPath(pathinfo($this->getFilePath(), PATHINFO_DIRNAME));

		if ($http_type === 'request') {
			return file_put_contents(
				$this->getFilePath(),
				'<!-- '.http_build_query($params).' -->'.PHP_EOL.$data
			);
		}
		else if ($http_type === 'response') {
			return file_put_contents(
				$this->getFilePath(),
				'<!-- '.http_build_query($params).' -->'.PHP_EOL.$data.PHP_EOL,
				FILE_APPEND
			);
		}

		throw new Exception('Invalid http type. Hint: Must specify `request` or `response`.');
	}

	public function clearDir($dir_path)
	{
		if (strpos($this->getDirPath(), $dir_path) === false) {
			$dir_path = $this->getDirPath().ltrim($dir_path, '/\\');
		}

		$files = glob(rtrim($dir_path, '/\\').DIRECTORY_SEPARATOR.'*');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}

		return $this;
	}

	public function clearFile($file_path)
	{
		if (strpos($this->getDirPath(), pathinfo($file_path, PATHINFO_DIRNAME)) === false) {
			$file_path = $this->getDirPath().ltrim($file_path, '/\\');
		}

		if (!is_file($file_path)) {
			return false;
		}

		file_put_contents($file_path, '');

		return $this;
	}

	protected function checkOrCreateDirPath($dir_path)
	{
		$dir_names = explode('/', $dir_path);
		$dir_str   = '';

		foreach ($dir_names as $dir_name) {
			$dir_str .= $dir_name.DIRECTORY_SEPARATOR;

			if ($dir_str && !is_dir($dir_str)) {
				mkdir($dir_str, 0755);
			}
		}
	}
}