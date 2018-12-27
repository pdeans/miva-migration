<?php

namespace App\Http\Controllers;

use App\Miva\Conversions\OrderConversion;
use App\Models\Order;
use App\Services\Loggers\ApiLogger;
use App\Services\Loggers\FileLogger;
use Illuminate\Http\Request;
use pdeans\Miva\Api\Manager as Api;

class OrderController extends Controller
{
    protected $api;
    protected $order;
    protected $conversion;
    protected $title;
    protected $log;
    protected $api_log;

    public function __construct(Api $api, Order $order, OrderConversion $conversion)
    {
        $this->api        = $api;
        $this->order      = $order;
        $this->conversion = $conversion;
        $this->title      = 'Order Migration';
        $this->log        = new FileLogger(log_path().'/orders');
        $this->api_log    = new ApiLogger(log_path().'/orders');
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
        $this->api_log->setDir($this->api_log->getDir().snake_case(__FUNCTION__).'/requests');

        if ($request->query('page') === null) {
            $this->log->clearFile('run.log');
            $this->api_log->clearDir();
        }

        // Get order data
        $orders = $this->order->paginate($this->per_page);

        // Pagination data
        $page  = $orders->currentPage();
        $total = $orders->lastPage();

        $this->log->write('run.log', "Starting migration on page $page of $total");

        // Convert order data to api request
        $api_request       = $this->conversion->convert($orders);
        $func_list_count   = ($api_request ? count($api_request->getFunctionList()) : 0);
        $formatted_api_req = null;
        $formatted_api_res = null;

        // Api request
        if ($func_list_count > 0) {
            $this->log->write('run.log', sprintf(
                'Attempting Api request with %d function%s.',
                $func_list_count,
                ($func_list_count === 1 ? '' : 's')
            ));

            $api_response = $api_request->send();

            $this->log->write('run.log', 'Api request completed.');

            $formatted_api_req = json_encode(
                json_decode((string)$api_request->getLastRequest()->getBody()),
                JSON_PRETTY_PRINT
            );

            $formatted_api_res = json_encode(
                json_decode((string)$api_response->getBody()),
                JSON_PRETTY_PRINT
            );

            $this->api_log->writeRequest(
                $this->api_log->getRequestFilename($page, $total),
                $formatted_api_req,
                $formatted_api_res
            );
        }

        $this->log->write('run.log', "Completed migration on page $page of $total");

        $prog_complete = $this->getProgressComplete($page, $total);
        $is_redirect   = (bool)$request->query('redirect', false);

        if ($orders->hasMorePages() === false) {
            if ($is_redirect) {
                echo 'End of order migration';
                exit;
            }

            return view('base', [
                'action'       => 'Converting Orders Completed',
                'api_response' => $formatted_api_res,
                'title'        => $this->title,
                'progress'     => [
                    'page'    => $page,
                    'total'   => $total,
                    'percent' => $prog_complete,
                ],
            ]);
        }

        $next_url = $orders->nextPageUrl();

        // $this->log->write('run.log', "Redir to $next_url");

        // Continue as redirect (generally through cli call)
        if ($is_redirect) {
            return redirect($next_url.'&redirect=1');
        }

        return view('base', [
            'action'       => 'Converting Orders',
            'next_url'     => $next_url,
            'api_response' => $formatted_api_res,
            'title'        => $this->title,
            'progress'     => [
                'page'    => $page,
                'total'   => $total,
                'percent' => $prog_complete,
            ],
        ]);
    }
}
