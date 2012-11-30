<?php
namespace Tests\PhpStrace;

class ProcessStatusTest extends \PHPUnit_Framework_TestCase
{

    public function testSetGetScriptName ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $ps->setScriptName($name = 'php-strace');
        $this->assertEquals($name, $ps->getScriptName());
    }

    public function testSetGetProcessName ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $ps->setProcessName($name = 'php53-cgi');
        $this->assertEquals($name, $ps->getProcessName());
    }


    public function testSetGetCmd ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine');
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $ps->setCmd($cmd = 'ps2');
        $this->assertEquals($cmd, $ps->getCmd());
    }

    public function testcheckRequirementsSuccess ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('isToolInstalled'));
        $commandLine->expects($this->once())->method('isToolInstalled')->will($this->returnValue(true));
        $strace = new \PhpStrace\ProcessStatus($commandLine);
        $result = $strace->checkRequirements();
        $this->assertTrue($result->getSucess());
    }

    public function testcheckRequirementsFailure ()
    {
        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('isToolInstalled'));
        $commandLine->expects($this->once())->method('isToolInstalled')->will($this->returnValue(false));
        $strace = new \PhpStrace\ProcessStatus($commandLine);
        $result = $strace->checkRequirements();
        $this->assertFalse($result->getSucess());
        $this->assertEquals('command line tool "ps" ist not installed. Please install "ps".', $result->getErrorMessage());
    }

    public function testFetchPhpProcessIdsSuccess ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result(0, array(
                                                                    '3460 ?        S      0:01 sshd: vagrant@pts/1',
                                                                    '12192 ?        Ss     0:00 /usr/bin/php5-cgi',
                                                                    ' 12193 ?        S      0:00 /usr/bin/php5-cgi'
                                                               ));


        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('execute'));
        $commandLine->expects($this->once())->method('execute')->will($this->returnValue($result));
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $ps->setProcessName('php5-cgi');
        $result = $ps->fetchPhpProcessIds();

        $this->assertEquals(array(
                                 12192,
                                 12193
                            ), $result);
    }

    /**
     * @expectedException \PhpStrace\Exception
     */
    public function testFetchPhpProcessIdsFailure ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result(0, array(
                                                                    ' ?        Ss     0:00 /usr/bin/php5-cgi',
                                                                    '  ?        S      0:00 /usr/bin/php5-cgi'
                                                               ));


        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('execute'));
        $commandLine->expects($this->once())->method('execute')->will($this->returnValue($result));
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $ps->setProcessName('php5-cgi');

        $ps->fetchPhpProcessIds();
    }

    public function testIsProcessRunningPhp5Cgi ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result(0, array(
                                                                    '2398 ?        Ss     0:00 /usr/bin/php5-cgi',
                                                                    '25398  ?        S      0:00 /usr/bin/php5-cgi'
                                                               ));

        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('execute'));
        $commandLine->expects($this->exactly(2))->method('execute')->will($this->returnValue($result));
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $this->assertTrue($ps->isProcessRunning('php5-cgi'));
        $this->assertFalse($ps->isProcessRunning('php-fpm'));
    }

    public function testIsProcessRunningPhpFpm ()
    {
        $result = new \PhpStrace\CommandLine\Execute\Result(0, array(
                                                                    '22398 ?        S      0:08 php-fpm: pool www',
                                                                    '22399 ?        S      0:08 php-fpm: pool www  '
                                                               ));

        $commandLine = $this->getMock('\PhpStrace\CommandLine', array('execute'));
        $commandLine->expects($this->exactly(2))->method('execute')->will($this->returnValue($result));
        $ps = new \PhpStrace\ProcessStatus($commandLine);
        $this->assertFalse($ps->isProcessRunning('php5-cgi'));
        $this->assertTrue($ps->isProcessRunning('php-fpm'));
    }

}