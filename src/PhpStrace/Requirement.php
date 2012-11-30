<?php
namespace PhpStrace;
use PhpStrace\Requirement\Result;

interface Requirement
{
    /**
     * @return Result
     */
    public function checkRequirements();

}