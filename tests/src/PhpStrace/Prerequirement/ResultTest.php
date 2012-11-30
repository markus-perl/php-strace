<?php
namespace Tests\PhpStrace\Requirement;

class ResultTest extends \PHPUnit_Framework_TestCase
{

    public function testSetGetSuccess ()
    {
        $result = new \PhpStrace\Requirement\Result();
        $result->setSucess(true);
        $this->assertTrue($result->getSucess());
    }

    public function testSetGetMessage ()
    {
        $result = new \PhpStrace\Requirement\Result();
        $result->setErrorMessage($msg = 'something failed');
        $this->assertEquals($msg, $result->getErrorMessage());
    }

}