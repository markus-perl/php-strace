<?php
namespace PhpStrace;

interface Requirement
{
    /**
     * @return Result
     */
    public function checkRequirements();

}