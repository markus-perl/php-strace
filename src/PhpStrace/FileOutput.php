<?php
namespace PhpStrace;

class FileOutput implements Observer
{

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var resource
     */
    private $fileHandle = null;

    /**
     * @var int Timestamp
     */
    private $time = null;

    /**
     * @param int $time Timestamp
     */
    public function setTime ($time)
    {
        $this->time = (int) $time;
    }

    /**
     * @return int
     */
    public function getTime ()
    {
        if (null === $this->time) {
            return time();
        }

        return $this->time;
    }

    /**
     * @return string
     */
    public function getFilePath ()
    {
        return $this->filePath;
    }

    /**
     * @param string|resource $filePath
     */
    public function __construct ($filePath = null)
    {
        if (is_string($filePath)) {
            $this->filePath = $filePath;
        } elseif (is_resource($filePath)) {
            $this->fileHandle = $filePath;
        }

        if (false === is_resource($this->fileHandle)) {

            if (null === $this->filePath) {
                throw new Exception('path to logfile not set');
            }

            $this->fileHandle = @fopen($this->filePath, 'a');

            if (false == $this->fileHandle) {
                throw new ExitException('cannot open file ' . $this->filePath . ' for writing.');
            }
        }
    }

    public function notify (Observerable $observerable, $data = array())
    {
        if (isset($data['text'])) {
            $formatted = sprintf('%s - %s' . PHP_EOL, date('Y-m-d h:m:s', $this->getTime()), $data['text']);

            $writeResult = fwrite($this->fileHandle, $formatted);
        }
    }

    public function __destruct ()
    {
        if (is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }
    }
}
