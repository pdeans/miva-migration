<?php

namespace App\Services\Loggers;

/**
 * Class ApiLogger
 *
 * Logging class for Api requests and responses
 */
class ApiLogger extends Logger
{
    /**
     * Section Divider
     *
     * @var string
     */
    protected $divider;

    /**
     * Request Heading
     *
     * @var string
     */
    protected $request_heading;

    /**
     * Response Heading
     *
     * @var string
     */
    protected $response_heading;

    /**
     * Construct a new ApiLogger object.
     *
     * @param string  $log_dir
     * @param boolean $file_append
     */
    public function __construct($log_dir = '', $file_append = true)
    {
        parent::__construct($log_dir, $file_append);

        $this->divider         = PHP_EOL.PHP_EOL;
        $this->heading_divider = '#'.str_repeat('-', 78).'#';
        $this->request_heading = sprintf(
            '%s%s%s%s%s',
            $this->heading_divider,
            PHP_EOL,
            "# Request\t".date('[m/d/Y H:i:s]'),
            PHP_EOL,
            $this->heading_divider
        );
        $this->response_heading = sprintf(
            '%s%s%s%s%s',
            $this->heading_divider,
            PHP_EOL,
            "# Response\t".date('[m/d/Y H:i:s]'),
            PHP_EOL,
            $this->heading_divider
        );
    }

    /**
     * Create a request log file name.
     *
     * @param  int    $page
     * @param  int    $total_pages
     *
     * @return string
     */
    public function getRequestFilename(int $page, int $total_pages)
    {
        return sprintf('%0'.strlen($total_pages).'d.xml', $page);
    }

    /**
     * Log an Api request.
     *
     * @param string $file
     * @param string $request_body
     * @param string $response_body
     *
     * @return int
     */
    public function writeRequest(string $file, string $request_body, string $response_body = '')
    {
        $file_path = $this->getFilePath($file);

        $this->checkOrCreateDirPath(pathinfo($file_path, PATHINFO_DIRNAME));

        if ($this->file_append) {
            if ($response_body === '') {
                return file_put_contents($file_path, sprintf(
                    '%s%s%s',
                    $this->request_heading,
                    $this->divider,
                    $request_body
                ), FILE_APPEND);
            }

            return file_put_contents($file_path, sprintf(
                '%s%s%s%s%s%s%s',
                $this->request_heading,
                $this->divider,
                $request_body,
                $this->divider,
                $this->response_heading,
                $this->divider,
                $response_body
            ), FILE_APPEND);
        }

        if ($response_body === '') {
            return file_put_contents($file_path, sprintf(
                '%s%s%s',
                $this->request_heading,
                $this->divider,
                $request_body
            ));
        }

        return file_put_contents($file_path, sprintf(
            '%s%s%s%s%s%s%s',
            $this->request_heading,
            $this->divider,
            $request_body,
            $this->divider,
            $this->response_heading,
            $this->divider,
            $response_body
        ));
    }
}
