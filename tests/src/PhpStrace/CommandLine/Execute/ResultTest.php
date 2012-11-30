<?php
namespace Tests\PhpStrace\CommandLine\Execute;

class ResultTest extends \PHPUnit_Framework_TestCase
{

    public function testSetGetOutput ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result();
        $result->setOutput($output = array(
            'abc',
            'def'
        ));
        $this->assertEquals($output, $result->getOutput());
    }

    public function testSetGetReturnVar ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result();
        $result->setReturnVar($var = 1);
        $this->assertEquals($var, $result->getReturnVar());
    }

    public function testConstruct ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result($returnVar = 111, $output = array(
            'test'
        ));

        $this->assertEquals($returnVar, $result->getReturnVar());
        $this->assertEquals($output, $result->getOutput());
    }
}