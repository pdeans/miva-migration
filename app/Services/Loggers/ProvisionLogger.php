<?php

namespace App\Services\Loggers;

use Exception;

class ProvisionLogger extends Logger
{
	public function writeRequest($file, $request_data, array $params = [])
	{
		return $this->writePrv('request', $file, $request_data, $params);
	}

	public function writeResponse($file, $response_data, array $params = [])
	{
		return $this->writePrv('response', $file, $response_data, $params);
	}

	public function getRequestFilename($page, $total_pages)
	{
		return sprintf('%0'.strlen($total_pages).'d.xml', $page);
	}

	protected function writePrv($http_type, $file, $data, array $params)
	{
		$file_path = $this->getFilePath($file);

		$this->checkOrCreateDirPath(pathinfo($file_path, PATHINFO_DIRNAME));

		$message = (!empty($params) ?
			'<!-- '.http_build_query($params).' -->'.PHP_EOL.$data :
			$data
		);

		if ($http_type === 'request') {
			return file_put_contents($file_path, $message);
		}
		else if ($http_type === 'response') {
			return file_put_contents($file_path, $message.PHP_EOL, FILE_APPEND);
		}

		throw new Exception('Invalid http type. Hint: Must specify `request` or `response`.');
	}
}