<?php
namespace Tests\PhpStrace;

class FileOutputTest extends \PHPUnit_Framework_TestCase
{

    public function testNotify ()
    {
        $tmpFile = tmpfile();
        $fo = new \PhpStrace\FileOutput($tmpFile);
        $fo->setTime(1354537347);

        $cl = new \PhpStrace\CommandLine();

        $fo->notify($cl, array('text' => 'test'));

        fseek($tmpFile, 0);
        $this->assertEquals('2012-12-03 04:22:27 - test' . PHP_EOL, fread($tmpFile, 64));
    }

}   