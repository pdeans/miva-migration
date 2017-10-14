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
			(!empty($params) ? '?'.http_build_query($params) : '');
	}

	public function getNextAction(Request $request, $uri)
	{
		return rtrim($request->getUri()->getBaseUrl(), '/').$uri;
	}

	public function getProgressComplete($completed, $total)
	{
		return  round(((float)$completed / $total) * 100, 2);
	}
}