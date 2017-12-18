<?php

namespace App\Http\Controllers;

use App\Http\Conversions\ProductConversion;
use App\Models\Products\Product;
use App\Services\Loggers\FileLogger;
use App\Services\Loggers\ProvisionLogger;
use Illuminate\Http\Request;
use pdeans\Miva\Provision\Manager as Provision;

class ProductController extends Controller
{
	protected $prv;
	protected $product;
	protected $conversion;
	protected $title;
	protected $log;
	protected $prv_log;

	public function __construct(Provision $prv, Product $product, ProductConversion $conversion)
	{
		$this->prv        = $prv;
		$this->product    = $product;
		$this->conversion = $conversion;
		$this->title      = 'Product Migration';
		$this->log        = new FileLogger(log_path().'/products');
		$this->prv_log    = new ProvisionLogger(log_path().'/products');
	}

	public function index()
	{
		return view('base', [
			'title'  => $this->title,
			'action' => 'Target an action to begin',
		]);
	}

	public function convert(Request $request)
	{
		$this->log->setDir($this->log->getDir().snake_case(__FUNCTION__));
		$this->prv_log->setDir($this->prv_log->getDir().snake_case(__FUNCTION__));

		if ($request->query('page') === null) {
			$this->log->clearFile('run.txt');
			$this->prv_log->clearDir('requests');
			$this->prv_log->clearFile('responses.xml');
		}

		// Get product data
		$products = $this->product->paginate($this->per_page);

		// Pagination data
		$page  = $products->currentPage();
		$total = $products->lastPage();

		$this->log->write('run.txt', "Starting migration on page $page of $total");

		$prv_response = null;

		// Convert product data to xml provisioning
		$xml = $this->conversion->convert($products);

		// Provision request
		if ($xml && $xml !== '') {
			$this->prv_log->writeRequest('requests/'.$this->prv_log->getRequestFilename($page, $total), $xml);

			$prv_response = $this->prv->send($xml)->getBody();

			$this->prv_log->writeResponse('responses.xml', $prv_response, [
				'page'  => $page,
				'total' => $total
			]);
		}

		$this->log->write('run.txt', "Completed migration on page $page of $total");

		$prog_complete = $this->getProgressComplete($page, $total);
		$is_redirect   = (bool)$request->query('redirect', false);

		if ($products->hasMorePages() === false) {
			if ($is_redirect) {
				echo 'End of product migration';
				exit;
			}

			return view('base', [
				'action'       => 'Converting Products Completed',
				'prv_response' => $prv_response,
				'title'        => $this->title,
				'progress'     => [
					'page'    => $page,
					'total'   => $total,
					'percent' => $prog_complete,
				],
			]);
		}

		$next_url = $products->nextPageUrl();

		// $this->log->write('run.txt', "Redir to $next_url");

		// Continue as redirect (generally through cli call)
		if ($is_redirect) {
			return redirect($next_url.'&redirect=1');
		}

		return view('base', [
			'action'       => 'Converting Products',
			'next_url'     => $next_url,
			'prv_response' => $prv_response,
			'title'        => $this->title,
			'progress'     => [
				'page'    => $page,
				'total'   => $total,
				'percent' => $prog_complete,
			],
		]);
	}
}