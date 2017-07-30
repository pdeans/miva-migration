<?php

namespace App\Controllers;

use App\Migrations\ProductMigration;
use App\Models\Product;
use pdeans\Miva\Provision\Manager as Provision;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class ProductController extends Controller
{
	protected $product;
	protected $log_path;
	protected $migrate;
	protected $prv;
	protected $title;
	protected $view;

	public function __construct(Twig $view, Provision $prv, Product $product, ProductMigration $migrate)
	{
		$this->product  = $product;
		$this->log_path = LOG_PATH.'/products';
		$this->migrate  = $migrate;
		$this->prv      = $prv;
		$this->title    = 'Product Migration';
		$this->view     = $view;
	}

	public function index(Request $request, Response $response)
	{
		return $this->view->render($response, 'base.twig', [
			'title'  => $this->title,
			'action' => 'Target an action to begin',
		]);
	}

	public function convert(Request $request, Response $response)
	{
		// Configure pagination params
		$params = $this->getParams($request->getQueryParams(), true);

		if (!$params['total']) {
			$params['total'] = $this->getTotalPages($this->product->count(), $params['limit']);
		}

		if ($params['page'] === 1) {
			$this->clearDir($this->log_path.'/convert/requests');
			$this->clearLog($this->log_path.'/convert/run.txt');
			$this->clearLog($this->log_path.'/convert/responses.xml');
		}

		$this->log($this->log_path.'/convert/run.txt', 'Starting migration on '.$params['page'].' of '.$params['total']);

		// Get product data
		$products = $this->product
			->offset($params['offset'])
			->take($params['limit'])
			->get();

		// Convert product data to xml provisioning
		$xml = $this->migrate->convert($products);

		$prv_res = null;

		// Provision request
		if ($xml) {
			$this->logRequest(
				$this->log_path.'/convert/requests/'.sprintf('%0'.strlen($params['total']).'d.xml', $params['page']),
				$xml,
				$params
			);

			$prv_res = $this->prv->send($xml);

			$this->logResponse($this->log_path.'/convert/responses.xml', $prv_res->getBody(), $params);
		}

		$this->log($this->log_path.'/convert/run.txt', 'Completed migration on '.$params['page'].' of '.$params['total']);

		$prog_complete = $this->getProgressComplete($params['page'], $params['total']);
		$is_redirect   = $request->getQueryParam('redirect', false);

		if ($params['page'] >= $params['total']) {
			if ($is_redirect) {
				echo 'End of product migration';
				exit;
			}

			return $this->view->render($response, 'base.twig', [
				'action'  => 'Converting Products Completed',
				'prv_res' => $prv_res,
				'title'   => $this->title,
				'progress' => [
					'page'    => $params['page'],
					'total'   => $params['total'],
					'percent' => $prog_complete,
				],
			]);
		}

		$next_url = $this->getNextUrl($request, $params);

		$this->log($this->log_path.'/convert/run.txt', 'Redir to '.$next_url);

		// Continue as redirect (generally through cli call)
		if ($is_redirect) {
			return $response->withRedirect($next_url);
		}

		// Continue via browser
		return $this->view->render($response, 'base.twig', [
			'action'   => 'Converting Products',
			'next_url' => $next_url,
			'prv_res'  => $prv_res,
			'title'    => $this->title,
			'progress' => [
				'page'    => $params['page'],
				'total'   => $params['total'],
				'percent' => $prog_complete,
			],
		]);
	}
}