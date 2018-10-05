<?php

namespace App\Services\Loggers;

abstract class Logger
{
	protected $log_dir;
	protected $file_append;

	public function __construct($log_dir = '', $file_append = true)
	{
		$this->setDir($log_dir);

		$this->file_append = $file_append;
	}

	public function getDir()
	{
		return $this->log_dir;
	}

	public function setDir($dir)
	{
		$this->log_dir = rtrim($dir, '/\\').'/';

		$this->checkOrCreateDirPath($this->log_dir);

		return $this;
	}

	public function clearDir($dir = null)
	{
		$full_path = $this->log_dir.ltrim($dir, '/\\');
		$files     = glob(rtrim($full_path, '/\\').'/*');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}

		return $this;
	}

	public function clearFile($file, $delete_file = false)
	{
		$full_path = $this->log_dir.ltrim($file, '/\\');

		if (!is_file($full_path)) {
			return false;
		}

		if ($delete_file) {
			unlink($full_path);
		}
		else {
			file_put_contents($full_path, '');
		}

		return $this;
	}

	public function getFilePath($file_path)
	{
		return $this->log_dir.ltrim(str_replace($this->log_dir, '', $file_path), '/\\');
	}

	public function setFileAppend($file_append)
	{
		$this->file_append = (bool)$file_append;

		return $this;
	}

	public function checkOrCreateDirPath($dir_path)
	{
		$dir_names = explode('/', str_replace('\\', '/', $dir_path));
		$dir_str   = '';

		foreach ($dir_names as $dir_name) {
			$dir_str .= $dir_name.'/';

			if ($dir_str && !is_dir($dir_str)) {
				mkdir($dir_str, 0755);
			}
		}

		return true;
	}
}