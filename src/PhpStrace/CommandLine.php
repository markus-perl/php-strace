<?php
namespace PhpStrace;
use PhpStrace\CommandLine\Execute\Result;

class CommandLine implements Observerable
{

    /**
     * @var resource
     */
    private $stdout = null;

    /**
     * @var resource
     */
    private $stderr = null;

    /**
     * @var array
     */
    private $observers = array();

    /**
     * @param resource $stderr
     */
    public function setStderr ($stderr)
    {
        $this->stderr = $stderr;
    }

    /**
     * @param resource $stdout
     */
    public function setStdout ($stdout)
    {
        $this->stdout = $stdout;
    }

    /**
     * @param Observer $observer
     * @param string $eventType
     */
    public function attachObserver (Observer $observer, $eventType)
    {
        if (false === isset($this->observers[$eventType])) {
            $this->observers[$eventType] = array();
        }

        $this->observers[$eventType][] = $observer;
    }

    /**
     * @param string $eventType
     * @param array $data
     */
    public function fireEvent ($eventType, $data = array())
    {
        foreach ($this->getObservers($eventType) as $observer) {
            $observer->notify($this, $data);
        }
    }

    /**
     * @param string $eventType
     * @return array
     */
    public function getObservers ($eventType)
    {
        if (isset($this->observers[$eventType])) {
            return $this->observers[$eventType];
        }
        return array();
    }

    /**
     * Checks if a specified command line tool
     * is installed
     *
     * @param string $cmd
     * @return boolean
     */
    public function isToolInstalled ($cmd)
    {
        if ($this->execute($cmd)->getReturnVar() == 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $cmd
     * @return Result
     */
    public function execute ($cmd)
    {
        //redirect everything to stdin
        $cmd .= ' 2>&1';

        exec($cmd, $output, $returnVar);
        return new Result($returnVar, $output);
    }

    /**
     * @param $text
     */
    public function stdout ($text)
    {
        if (null == $this->stdout) {
            $this->stdout = fopen('php://stdout', 'w');
        }

        fwrite($this->stdout, $text . PHP_EOL);
        $this->fireEvent('stdout', array(
                                        'text' => $text
                                   ));
    }

    /**
     * @param $text
     */
    public function stderr ($text)
    {
        if (null == $this->stderr) {
            $this->stderr = fopen('php://stderr', 'w');
        }

        fwrite($this->stderr, $text . PHP_EOL);

        $this->fireEvent('stderr', array(
                                        'text' => $text
                                   ));
    }

    /**
     *
     */
    public function __destruct ()
    {
        if (is_resource($this->stdout)) {
            fclose($this->stdout);
        }

        if (is_resource($this->stderr)) {
            fclose($this->stderr);
        }
    }


}