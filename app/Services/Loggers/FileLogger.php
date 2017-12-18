<?php

namespace App\Services\Loggers;

class FileLogger extends Logger
{
	public function write($file, $message)
	{
		$file_path = $this->getFilePath($file);

		$this->checkOrCreateDirPath(pathinfo($file_path, PATHINFO_DIRNAME));

		if ($this->file_append) {
			return file_put_contents($file_path, date('[m/d/Y H:i:s]')."\t$message".PHP_EOL, FILE_APPEND);
		}

		return file_put_contents($file_path, date('[m/d/Y H:i:s]')."\t$message".PHP_EOL);
	}
}