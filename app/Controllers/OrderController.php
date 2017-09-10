<?php

namespace App\Controllers;

use App\Migrations\OrderMigration;
use App\Models\Order;
use pdeans\Miva\Provision\Manager as Provision;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouterInterface as Router;
use Slim\Views\Twig;

class OrderController extends Controller
{
	protected $order;
	protected $log_path;
	protected $migrate;
	protected $prv;
	protected $router;
	protected $title;
	protected $view;

	public function __construct(
		Router $router,
		Twig $view,
		Provision $prv,
		Order $order,
		OrderMigration $migrate
	)
	{
		$this->order    = $order;
		$this->log_path = LOG_PATH.'/orders';
		$this->migrate  = $migrate;
		$this->prv      = $prv;
		$this->router   = $router;
		$this->title    = 'Order Migration';
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
			$params['total'] = $this->getTotalPages($this->order->count(), $params['limit']);
		}

		if ($params['page'] === 1) {
			$this->clearDir($this->log_path.'/convert/requests');
			$this->clearLog($this->log_path.'/convert/run.txt');
			$this->clearLog($this->log_path.'/convert/responses.xml');
		}

		$this->log($this->log_path.'/convert/run.txt', 'Starting migration on '.$params['page'].' of '.$params['total']);

		// Get order data
		$orders = $this->order
			->offset($params['offset'])
			->take($params['limit'])
			->get();

		// Convert order data to xml provisioning
		$xml = $this->migrate->convert($orders);

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
				echo 'End of order migration';
				exit;
			}

			return $this->view->render($response, 'base.twig', [
				'action'  => 'Converting Orders Completed',
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
			'action'   => 'Converting Orders',
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