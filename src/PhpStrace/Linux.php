<?php
namespace PhpStrace;
use PhpStrace\CommandLine;


class Linux implements Requirement
{
    /**
     * @var string
     */
    private $os = PHP_OS;

    /**
     * @param string $os
     */
    public function setOS ($os)
    {
        $this->os = $os;
    }

    /**
     * @return string
     */
    public function getOS ()
    {
        return $this->os;
    }

    /**
     * @return Requirement\Result
     */
    public function checkRequirements ()
    {
        $result = new Requirement\Result();
        if ($this->getOS() == 'Linux') {
            $result->setSucess(true);
        } else {
            $result->setErrorMessage('this program works only with linux');
        }

        return $result;
    }
}