<?php
namespace Tests\PhpStrace;

class StraceTest extends \PHPUnit_Framework_TestCase
{

    public function testSetGetLines ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $strace = new \PhpStrace\Strace($commandLine);
        $strace->setLines($lines = 555);

        $this->assertEquals($lines, $strace->getLines());
    }

    public function testSetGetCmd ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $strace = new \PhpStrace\Strace($commandLine);
        $strace->setCmd($cmd = 'ps');

        $this->assertEquals($cmd, $strace->getCmd());
    }

    public function testcheckRequirementsSuccess ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $strace = new \PhpStrace\Strace($commandLine);

        $result = $strace->checkRequirements();

        $this->assertTrue($result->getSucess());
    }

    public function testcheckRequirementsFailure ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $strace = new \PhpStrace\Strace($commandLine);
        $strace->setCmd('invalidCmd');

        $result = $strace->checkRequirements();

        $this->assertFalse($result->getSucess());
        $this->assertEquals('command line tool "strace" ist not installed. Please install "strace".', $result->getErrorMessage());
    }

    public function testWatch ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine', array(
                                                                     'stdout',
                                                                     'execute'
                                                                ));
        $commandLine->expects($this->any())->method('stdout');
        $commandLine->expects($this->any())->method('execute')->will($this->returnValue(new \PhpStrace\CommandLine\Execute\Result));

        $strace = new \PhpStrace\Strace($commandLine);

        //stop child
        if (false === $strace->watch(123456)) {
            exit;
        }
    }
}