<?php

namespace App\Services\Loggers;

/**
 * Logger class
 */
abstract class Logger
{
    /**
     * File append flag
     *
     * @var bool
     */
    protected $file_append;

    /**
     * Log directory
     *
     * @var string
     */
    protected $log_dir;

    /**
     * Create new logger object. Note, class is abstract, as such it should be
     * used primarily as a base class for other loggers to extend.
     *
     * @param string  $log_dir
     * @param boolean $file_append
     */
    public function __construct(string $log_dir = '', bool $file_append = true)
    {
        $this->setDir($log_dir);

        $this->file_append = $file_append;
    }

    /**
     * Get log directory
     *
     * @return string
     */
    public function getDir()
    {
        return $this->log_dir;
    }

    /**
     * Set log directory
     *
     * @param string $dir
     *
     * @return self
     */
    public function setDir(string $dir)
    {
        $this->log_dir = rtrim($dir, '/\\').'/';

        $this->checkOrCreateDirPath($this->log_dir);

        return $this;
    }

    /**
     * Clear all directory files
     *
     * @param string $dir
     *
     * @return self
     */
    public function clearDir(string $dir = '')
    {
        $full_path = $this->log_dir.ltrim($dir, '/\\');
        $files     = glob(rtrim($full_path, '/\\').'/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return $this;
    }

    /**
     * Clear the contents of a file or remove file from server
     *
     * @param  string  $file
     * @param  boolean $delete_file
     *
     * @return self
     */
    public function clearFile(string $file, $delete_file = false)
    {
        $full_path = $this->log_dir.ltrim($file, '/\\');

        if (!is_file($full_path)) {
            return false;
        }

        if ($delete_file) {
            unlink($full_path);
        }
        else {
            file_put_contents($full_path, '');
        }

        return $this;
    }

    /**
     * Get the full file path
     *
     * @param  string $file_path
     *
     * @return self
     */
    public function getFilePath(string $file_path)
    {
        return $this->log_dir.ltrim(str_replace($this->log_dir, '', $file_path), '/\\');
    }

    /**
     * Set the file append flag property
     *
     * @param boolean $file_append
     *
     * @return self
     */
    public function setFileAppend(bool $file_append)
    {
        $this->file_append = (bool)$file_append;

        return $this;
    }

    /**
     * Create a directory if it does not exist
     *
     * @param string $dir_path
     *
     * @return boolean
     */
    public function checkOrCreateDirPath(string $dir_path)
    {
        $dir_names = explode('/', str_replace('\\', '/', $dir_path));
        $dir_str   = '';

        foreach ($dir_names as $dir_name) {
            $dir_str .= $dir_name.'/';

            if ($dir_str && !is_dir($dir_str)) {
                mkdir($dir_str, 0755);
            }
        }

        return true;
    }
}
