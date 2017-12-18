<?php

namespace App\Http\Controllers;

use App\Http\Conversions\OrderConversion;
use App\Models\Orders\Order;
use App\Services\Loggers\FileLogger;
use App\Services\Loggers\ProvisionLogger;
use Illuminate\Http\Request;
use pdeans\Miva\Provision\Manager as Provision;

class OrderController extends Controller
{
	protected $prv;
	protected $order;
	protected $conversion;
	protected $title;
	protected $log;
	protected $prv_log;

	public function __construct(Provision $prv, Order $order, OrderConversion $conversion)
	{
		$this->prv        = $prv;
		$this->order      = $order;
		$this->conversion = $conversion;
		$this->title      = 'Order Migration';
		$this->log        = new FileLogger(log_path().'/orders');
		$this->prv_log    = new ProvisionLogger(log_path().'/orders');
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

		// Get order data
		$orders = $this->order->paginate($this->per_page);

		// Pagination data
		$page  = $orders->currentPage();
		$total = $orders->lastPage();

		$this->log->write('run.txt', "Starting migration on page $page of $total");

		$prv_response = null;

		// Convert order data to xml provisioning
		$xml = $this->conversion->convert($orders);

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

		if ($orders->hasMorePages() === false) {
			if ($is_redirect) {
				echo 'End of order migration';
				exit;
			}

			return view('base', [
				'action'       => 'Converting Orders Completed',
				'prv_response' => $prv_response,
				'title'        => $this->title,
				'progress'     => [
					'page'    => $page,
					'total'   => $total,
					'percent' => $prog_complete,
				],
			]);
		}

		$next_url = $orders->nextPageUrl();

		// $this->log->write('run.txt', "Redir to $next_url");

		// Continue as redirect (generally through cli call)
		if ($is_redirect) {
			return redirect($next_url.'&redirect=1');
		}

		return view('base', [
			'action'       => 'Converting Orders',
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