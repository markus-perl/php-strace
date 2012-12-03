<?php
namespace Tests\PhpStrace;

class CommandLineTest extends \PHPUnit_Framework_TestCase
{

    public function testStdOut ()
    {
        $cl = new \PhpStrace\CommandLine();

        $tmpFile = tmpfile();
        $cl->setStdout($tmpFile);
        $cl->stdout('test');

        fseek($tmpFile, 0);
        $this->assertEquals('test', fread($tmpFile, 4));
    }

    public function testStdErr ()
    {
        $cl = new \PhpStrace\CommandLine();

        $tmpFile = tmpfile();
        $cl->setStderr($tmpFile);
        $cl->stderr('test');

        fseek($tmpFile, 0);
        $this->assertEquals('test', fread($tmpFile, 4));
    }

    public function testIsToolInstalledTrue ()
    {
        $cl = new \PhpStrace\CommandLine();
        $this->assertTrue($cl->isToolInstalled('ls'));
    }

    public function testIsToolInstalledFalse ()
    {
        $cl = new \PhpStrace\CommandLine();
        $this->assertFalse($cl->isToolInstalled('foobar'));
    }

    public function testExectute ()
    {
        $cl = new \PhpStrace\CommandLine();
        $result = $cl->execute('/vagrant/php-strace -h');

        $this->assertEquals(0, $result->getReturnVar());

        $output = $result->getOutput();
        $this->assertContains('http://www.github.com/markus-perl/php-strace', $output[0]);
    }

    public function testAttachObserver ()
    {
        $cl = new \PhpStrace\CommandLine();

        $tmpFile = tmpfile();
        $cl->setStdout($tmpFile);
        $cl->setStderr($tmpFile);

        $observer = $this->getMock('\PhpStrace\FileOutput', array(), array('/tmp/file'));
        $observer->expects($this->exactly(2))->method('notify')->with($cl, array('text' => 'test'));
        $cl->attachObserver($observer, 'stdout');
        $cl->attachObserver($observer, 'stderr');
        $cl->stdout('test');
        $cl->stderr('test');
    }
}