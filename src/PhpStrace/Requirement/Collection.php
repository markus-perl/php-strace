<?php
namespace PhpStrace\Requirement;
use PhpStrace\Requirement;
use ArrayObject;

class Collection extends \ArrayObject
{

    public function add (Requirement $requirement)
    {
        $this[] = $requirement;
    }

}