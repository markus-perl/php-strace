<?php
namespace PhpStrace\Requirement;


class Result
{

    /**
     * @var bool
     */
    private $sucess = false;

    /**
     * @var string
     */
    private $errorMessage = '';

    /**
     * @param $errorMessage
     */
    public function setErrorMessage ($errorMessage)
    {
        $this->errorMessage = (string) $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage ()
    {
        return $this->errorMessage;
    }

    /**
     * @param $sucess
     */
    public function setSucess ($sucess)
    {
        $this->sucess = (boolean) $sucess;
    }

    /**
     * @return bool
     */
    public function getSucess ()
    {
        return $this->sucess;
    }

}