<?php
namespace Tests\PhpStrace;

class LinuxTest extends \PHPUnit_Framework_TestCase
{

    public function testSetGetOs ()
    {
        $linux = new \PhpStrace\Linux();
        $linux->setOS($os = 'Windows');
        $this->assertEquals($os, $linux->getOS());
    }

    public function testcheckRequirementsSuccess ()
    {
        $linux = new \PhpStrace\Linux();

        $result = $linux->checkRequirements();
        $this->assertTrue($result->getSucess());
    }

    public function testcheckRequirementsFailure ()
    {
        $linux = new \PhpStrace\Linux();
        $linux->setOS('Windows');

        $result = $linux->checkRequirements();
        $this->assertFalse($result->getSucess());
        $this->assertEquals('this program works only with linux', $result->getErrorMessage());
    }
}