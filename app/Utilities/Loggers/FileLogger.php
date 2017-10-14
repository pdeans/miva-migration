<?php

namespace App\Utilities\Loggers;

use Exception;

class FileLogger
{
	protected $base_log_path;
	protected $base_log_dir;
	protected $log_dir;
	protected $file_append;

	public function __construct($base_log_path)
	{
		$this->base_log_path = rtrim($base_log_path, '/\\');
		$this->base_log_dir  = '';
		$this->log_dir       = '';
		$this->file_append   = true;
	}

	public function getBaseDirPath()
	{
		return $this->base_log_path.$this->base_log_dir;
	}

	public function setBaseDir($dir)
	{
		$this->base_log_dir = DIRECTORY_SEPARATOR.trim($dir, '/\\').DIRECTORY_SEPARATOR;

		$this->checkOrCreateDirPath($this->getBaseDirPath());

		return $this;
	}

	public function getDirPath()
	{
		return $this->base_log_path.$this->base_log_dir.ltrim($this->log_dir, '/\\');
	}

	public function setDir($dir)
	{
		$this->log_dir = DIRECTORY_SEPARATOR.trim($dir, '/\\').DIRECTORY_SEPARATOR;

		$this->checkOrCreateDirPath($this->getDirPath());

		return $this;
	}

	public function clearDir($dir_path)
	{
		$dir_path = $this->getDirPath().ltrim(str_replace($this->getDirPath(), '', $dir_path), '/\\');
		$files    = glob(rtrim($dir_path, '/\\').DIRECTORY_SEPARATOR.'*');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}

		return $this;
	}

	public function getFullFilePath($file_path)
	{
		return $this->getDirPath().ltrim(str_replace($this->getDirPath(), '', $file_path), '/\\');
	}

	public function clearFile($file_path)
	{
		$file_path = $this->getFullFilePath($file_path);

		if (!is_file($file_path)) {
			return false;
		}

		file_put_contents($file_path, '');

		return $this;
	}

	public function append($file_append)
	{
		$this->file_append = (bool)$file_append;

		return $this;
	}

	public function write($file, $message)
	{
		$file_path = $this->getFullFilePath($file);

		$this->checkOrCreateDirPath(pathinfo($file_path, PATHINFO_DIRNAME));

		if ($this->file_append) {
			return file_put_contents($file_path, date('[m/d/Y H:i:s]')."\t$message".PHP_EOL, FILE_APPEND);
		}

		return file_put_contents($file_path, date('[m/d/Y H:i:s]')."\t$message".PHP_EOL);
	}

	public function writeRequest($file, $request_data, array $params)
	{
		return $this->writePrv('request', $this->getFullFilePath($file), $request_data, $params);
	}

	public function writeResponse($file, $response_data, array $params)
	{
		return $this->writePrv('response', $this->getFullFilePath($file), $response_data, $params);
	}

	protected function writePrv($http_type, $file_path, $data, array $params)
	{
		$this->checkOrCreateDirPath(pathinfo($file_path, PATHINFO_DIRNAME));

		if ($http_type === 'request') {
			return file_put_contents(
				$file_path,
				'<!-- '.http_build_query($params).' -->'.PHP_EOL.$data
			);
		}
		else if ($http_type === 'response') {
			return file_put_contents(
				$file_path,
				'<!-- '.http_build_query($params).' -->'.PHP_EOL.$data.PHP_EOL,
				FILE_APPEND
			);
		}

		throw new Exception('Invalid http type. Hint: Must specify `request` or `response`.');
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

		return true;
	}
}