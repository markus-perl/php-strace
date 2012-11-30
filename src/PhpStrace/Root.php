<?php
namespace PhpStrace;
use PhpStrace\CommandLine;


class Root implements Requirement
{


    /**
     * @return Requirement\Result
     */
    public function checkRequirements ()
    {
        $result = new Requirement\Result();

        if (function_exists('posix_getuid')) {
            $result->setSucess(true);
        } else {
            $result->setSucess(false);
            $result->setErrorMessage('posix extension missing. please install php posix extension');
            return $result;
        }

        if (posix_getuid() == 0) {
            $result->setSucess(true);
        } else {
            $result->setSucess(false);
            $result->setErrorMessage('root access required. please execute this script as root.');

        }

        return $result;
    }
}