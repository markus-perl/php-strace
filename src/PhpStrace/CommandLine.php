<?php
namespace PhpStrace;
use PhpStrace\CommandLine\Execute\Result;

class CommandLine
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
    }

    /**
     *
     */
    public function __destruct ()
    {
        if (null !== $this->stdout) {
            fclose($this->stdout);
        }

        if (null !== $this->stderr) {
            fclose($this->stderr);
        }
    }


}