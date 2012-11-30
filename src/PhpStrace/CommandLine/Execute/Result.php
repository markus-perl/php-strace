<?php
namespace PhpStrace\CommandLine\Execute;
use PhpStrace\Exception;

class Result
{

    /**
     * @var int
     */
    private $returnVar = null;

    /**
     * @var array[]string
     */
    private $output = array();

    /**
     * @param int|null $returnVar
     * @param array $output
     */
    public function __construct ($returnVar = null, $output = array())
    {
        $this->setReturnVar($returnVar);
        $this->setOutput($output);
    }

    /**
     * @param array $output
     */
    public function setOutput (array $output)
    {
        foreach ($output as $line) {
            if (false === is_string($line)) {
                throw new \Exception('output array elements must be of string. ' . gettype($line) . ' given.');
            }
        }

        $this->output = $output;
    }

    /**
     * @return array
     */
    public function getOutput ()
    {
        return $this->output;
    }

    /**
     * @param int $returnVar
     */
    public function setReturnVar ($returnVar)
    {
        if ($returnVar !== null && false === is_int($returnVar)) {
            throw new \Exception('returnVar must be of type int or null. ' . gettype($returnVar) . ' given.');
        }

        $this->returnVar = $returnVar;
    }

    /**
     * @return int
     */
    public function getReturnVar ()
    {
        return $this->returnVar;
    }

}