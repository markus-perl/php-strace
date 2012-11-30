<?php
namespace Tests\PhpStrace;

class RunnerTest extends \PHPUnit_Framework_TestCase
{


    public function testBootstrap ()
    {
        $runner = new \PhpStrace\Runner();
        $runner->bootstrap();

        $this->assertEquals('512M', ini_get('memory_limit'));
        $this->assertEquals('-1', ini_get('max_execution_time'));
    }

    public function testShowWelcomeMessage ()
    {
        $runner = new \PhpStrace\Runner();
        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('stdout'));
        $commandLine->expects($this->at(0))->method('stdout')->with('php-strace ' . \PhpStrace\Version::ID . ' by Markus Perl (http://www.github.com)');
        $commandLine->expects($this->at(1))->method('stdout')->with('');

        $runner->setCommandLine($commandLine);
        $runner->showWelcomeMessage();
    }

    /**
     * @expectedException \Zend\Console\Exception\RuntimeException
     */
    public function testParseGetOptHelp ()
    {
        $argv = array(
            'php-strace',
            '-h'
        );

        $runner = new \PhpStrace\Runner();
        $runner->parseGetOpt($argv);
    }

    public function testParseGetOptMemory ()
    {
        $argv = array(
            'php-strace',
            '-m',
            '256'
        );

        $runner = new \PhpStrace\Runner();
        $runner->parseGetOpt($argv);
        $this->assertEquals(256, $runner->getMemoryLimit());
    }

    public function testParseGetOptLines ()
    {
        $argv = array(
            'php-strace',
            '-l',
            '200'
        );

        $runner = new \PhpStrace\Runner();
        $runner->parseGetOpt($argv);
        $this->assertEquals(200, $runner->getStrace()->getLines());
    }

    public function testParseGetOptProcessName ()
    {
        $argv = array(
            'php-strace',
            '--process-name',
            'php54-cgi'
        );

        $runner = new \PhpStrace\Runner();
        $runner->parseGetOpt($argv);
        $this->assertEquals('php54-cgi', $runner->getProcessStatus()->getProcessName());
    }

    public function testSetGetCommandLine ()
    {
        $runner = new \PhpStrace\Runner();
        $this->assertInstanceOf('\PhpStrace\CommandLine', $runner->getCommandLine());

        $commandLine = new \PhpStrace\CommandLine();
        $runner->setCommandLine($commandLine);

        $this->assertEquals($commandLine, $runner->getCommandLine());
    }

    public function testGetProcessStatus ()
    {
        $runner = new \PhpStrace\Runner();
        $this->assertInstanceOf('\PhpStrace\ProcessStatus', $runner->getProcessStatus());
    }

    public function testGetStrace ()
    {
        $runner = new \PhpStrace\Runner();
        $this->assertInstanceOf('\PhpStrace\Strace', $runner->getStrace());
    }

    /**
     * @expectedException PhpStrace\ExitException
     */
    public function testcheckRequirements ()
    {
        $runner = new \PhpStrace\Runner();

        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('stderr'));
        $commandLine->expects($this->at(0))->method('stderr')->with('The following Requirements did not met:');
        $commandLine->expects($this->at(1))->method('stderr')->with('root access required. please execute this script as root.');
        $runner->setCommandLine($commandLine);

        $runner->checkRequirements();
    }

    public function testGetSetMemoryLimit ()
    {
        $runner = new \PhpStrace\Runner();

        $runner->setMemoryLimit($memoryLimit = 128);
        $this->assertEquals($memoryLimit, $runner->getMemoryLimit());
    }

}