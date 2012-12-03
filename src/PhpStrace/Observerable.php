<?php
namespace PhpStrace;

interface Observerable
{

    /**
     * @param Observer $observer
     * @param string $eventType
     */
    public function attachObserver (Observer $observer, $eventType);

    /**
     * @param string $eventType
     */
    public function fireEvent ($eventType, $data = array());

}