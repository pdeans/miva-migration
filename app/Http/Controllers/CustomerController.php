<?php

namespace App\Http\Controllers;

use App\Miva\Conversions\CustomerConversion;
use App\Models\Customer;
use App\Services\Loggers\ApiLogger;
use App\Services\Loggers\FileLogger;
use Illuminate\Http\Request;
use pdeans\Miva\Api\Manager as Api;

class CustomerController extends Controller
{
    protected $api;
    protected $customer;
    protected $conversion;
    protected $title;
    protected $log;
    protected $api_log;

    public function __construct(Api $api, Customer $customer, CustomerConversion $conversion)
    {
        $this->api        = $api;
        $this->customer   = $customer;
        $this->conversion = $conversion;
        $this->title      = 'Customer Migration';
        $this->log        = new FileLogger(log_path().'/customers');
        $this->api_log    = new ApiLogger(log_path().'/customers');
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

        // Get customer data
        $customers = $this->customer->paginate($this->per_page);

        // Pagination data
        $page  = $customers->currentPage();
        $total = $customers->lastPage();

        $this->log->write('run.log', "Starting migration on page $page of $total");

        // Convert customer data to api request
        $api_request       = $this->conversion->convert($customers);
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

        if ($customers->hasMorePages() === false) {
            if ($is_redirect) {
                echo 'End of customer migration';
                exit;
            }

            return view('base', [
                'action'       => 'Converting Customers Completed',
                'api_response' => $formatted_api_res,
                'title'        => $this->title,
                'progress'     => [
                    'page'    => $page,
                    'total'   => $total,
                    'percent' => $prog_complete,
                ],
            ]);
        }

        $next_url = $customers->nextPageUrl();

        // $this->log->write('run.log', "Redir to $next_url");

        // Continue as redirect (generally through cli call)
        if ($is_redirect) {
            return redirect($next_url.'&redirect=1');
        }

        return view('base', [
            'action'       => 'Converting Customers',
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
