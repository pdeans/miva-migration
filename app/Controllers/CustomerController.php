<?php

namespace App\Controllers;

use App\Migrations\CustomerMigration;
use App\Models\Customer;
use pdeans\Miva\Provision\Manager as Provision;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class CustomerController extends Controller
{
	protected $customer;
	protected $log_path;
	protected $migrate;
	protected $prv;
	protected $title;
	protected $view;

	public function __construct(Twig $view, Provision $prv, Customer $customer, CustomerMigration $migrate)
	{
		$this->customer = $customer;
		$this->log_path = LOG_PATH.'/customers';
		$this->migrate  = $migrate;
		$this->prv      = $prv;
		$this->title    = 'Customer Migration';
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
			$params['total'] = $this->getTotalPages($this->customer->count(), $params['limit']);
		}

		if ($params['page'] === 1) {
			$this->clearDir($this->log_path.'/convert/requests');
			$this->clearLog($this->log_path.'/convert/run.txt');
			$this->clearLog($this->log_path.'/convert/responses.xml');
		}

		$this->log($this->log_path.'/convert/run.txt', 'Starting migration on '.$params['page'].' of '.$params['total']);

		// Get customer data
		$customers = $this->customer
			->offset($params['offset'])
			->take($params['limit'])
			->get();

		// Convert customer data to xml provisioning
		$xml = $this->migrate->convert($customers);

		$prv_res = null;

		// Provision request
		if ($xml) {
			$this->logRequest(
				$this->log_path.'/convert/requests/'.sprintf('%0'.strlen($params['total']).'d.xml',
				$params['page']), $xml, $params['page'], $params['total']
			);

			$prv_res = $this->prv->send($xml);

			$this->logResponse($this->log_path.'/convert/responses.xml', $response, $params['page'], $params['total']);
		}

		$this->log($this->log_path.'/convert/run.txt', 'Completed migration on '.$params['page'].' of '.$params['total']);

		$prog_complete = $this->getProgressComplete($params['page'], $params['total']);
		$is_redirect   = $request->getQueryParam('redirect', false);

		if ($params['page'] >= $params['total']) {
			if ($is_redirect) {
				echo 'End of customer migration';
				exit;
			}

			return $this->view->render($response, 'base.twig', [
				'action'  => 'Converting Customers Completed',
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
			'action'   => 'Converting Customers',
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