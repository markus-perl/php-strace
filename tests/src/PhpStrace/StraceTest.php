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

    public function testWatchOutputExitCode1 ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result;
        $result->setReturnVar(1);
        $result->setOutput(array('error message'));

        $commandLine = $this->getMock('\PhpStrace\CommandLine', array(
                                                                     'stdout',
                                                                     'execute'
                                                                ));
        $commandLine->expects($this->exactly(3))->method('stdout');
        $commandLine->expects($this->any())->method('execute')->will($this->returnValue($result));
        $commandLine->expects($this->at(3))->method('stdout')->with('error message');

        $strace = new \PhpStrace\Strace($commandLine);

        $strace->watch(123456, true);
    }

    public function testWatchOutputExitCode0 ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result;
        $result->setReturnVar(0);
        $result->setOutput(array('some output'));

        $commandLine = $this->getMock('\PhpStrace\CommandLine', array(
                                                                     'stdout',
                                                                     'execute'
                                                                ));
        $commandLine->expects($this->exactly(2))->method('stdout');
        $commandLine->expects($this->any())->method('execute')->will($this->returnValue($result));

        $strace = new \PhpStrace\Strace($commandLine);

        $strace->watch(123456, true);
    }

    public function testWatchOutputSegfault ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result;
        $result->setReturnVar(0);
        $result->setOutput(array(
                                'some output',
                                \PhpStrace\Strace::SIGSEGV
                           ));

        $commandLine = $this->getMock('\PhpStrace\CommandLine', array(
                                                                     'stdout',
                                                                     'execute'
                                                                ));
        $commandLine->expects($this->exactly(4))->method('stdout');
        $commandLine->expects($this->any())->method('execute')->will($this->returnValue($result));
        $commandLine->expects($this->at(3))->method('stdout')->with('some output');
        $commandLine->expects($this->at(4))->method('stdout')->with(\PhpStrace\Strace::SIGSEGV);


        $strace = new \PhpStrace\Strace($commandLine);

        $strace->watch(123456, true);
    }
}