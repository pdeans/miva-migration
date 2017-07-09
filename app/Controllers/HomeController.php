<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class HomeController extends Controller
{
	protected $view;

	public function __construct(Twig $view)
	{
		$this->view = $view;
	}

	public function index(Request $request, Response $response)
	{
		return $this->view->render($response, 'base.twig', [
			'title'  => 'Migration',
			'action' => '<b>Migration:</b> Set an action to begin migration',
		]);
	}
}