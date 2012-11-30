<?php
namespace Tests\PhpStrace;

class RootTest extends \PHPUnit_Framework_TestCase
{


    public function testcheckRequirementsFailure ()
    {
        $linux = new \PhpStrace\Root();

        $result = $linux->checkRequirements();
        $this->assertFalse($result->getSucess());
        $this->assertEquals('root access required. please execute this script as root.', $result->getErrorMessage());
    }
}