<?php

namespace App\Controllers;

use App\Migrations\CategoryMigration;
use App\Models\Categories\Category;
use App\Utilities\Loggers\FileLogger;
use pdeans\Miva\Provision\Manager as Provision;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Interfaces\RouterInterface as Router;
use Slim\Views\Twig;

class CategoryController extends Controller
{
	protected $category;
	protected $logger;
	protected $migrate;
	protected $prv;
	protected $router;
	protected $title;
	protected $view;

	public function __construct(
		Router $router,
		FileLogger $logger,
		Twig $view,
		Provision $prv,
		Category $category,
		CategoryMigration $migrate
	)
	{
		$this->category = $category;
		$this->migrate  = $migrate;
		$this->prv      = $prv;
		$this->router   = $router;
		$this->title    = 'Category Migration';
		$this->view     = $view;

		$this->logger = $logger;
		$this->logger->setBaseDir('categories');
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
		$this->logger->setDir(__FUNCTION__);

		// Configure pagination params
		$params = $this->getParams($request->getQueryParams(), true);

		if (!$params['total']) {
			$params['total'] = $this->getTotalPages($this->category->count(), $params['limit']);
		}

		if ($params['page'] === 1) {
			$this->logger->clearDir('requests');
			$this->logger->clearFile('run.txt');
			$this->logger->clearFile('responses.xml');
		}

		$this->logger->write('run.txt', 'Starting migration on page '.$params['page'].' of '.$params['total']);

		// Get category data
		$categories = $this->category
			->offset($params['offset'])
			->take($params['limit'])
			->get();

		// Convert category data to xml provisioning
		$xml = $this->migrate->convert($categories);

		$prv_res = null;

		// Provision request
		if ($xml !== '') {
			$this->logger->writeRequest(
				'requests/'.sprintf('%0'.strlen($params['total']).'d.xml', $params['page']),
				$xml,
				$params
			);

			$prv_res = $this->prv->send($xml);

			$this->logger->writeResponse('responses.xml', $prv_res->getBody(), $params);
		}

		$this->logger->write('run.txt', 'Completed migration on page '.$params['page'].' of '.$params['total']);

		$prog_complete = $this->getProgressComplete($params['page'], $params['total']);
		$is_redirect   = (bool)$request->getQueryParam('redirect', false);

		if ($params['page'] >= $params['total']) {
			if ($is_redirect) {
				echo 'End of category migration';
				exit;
			}

			return $this->view->render($response, 'base.twig', [
				'action'  => 'Converting Categories Completed',
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

		$this->logger->write('run.txt', 'Redir to '.$next_url);

		// Continue as redirect (generally through cli call)
		if ($is_redirect) {
			return $response->withRedirect($next_url);
		}

		// Continue via browser
		return $this->view->render($response, 'base.twig', [
			'action'   => 'Converting Categories',
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