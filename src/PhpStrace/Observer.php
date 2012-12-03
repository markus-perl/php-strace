<?php
namespace PhpStrace;

interface Observer
{
    /**
     * @return Result
     */
    public function notify (Observerable $observerable, $data = array());

}