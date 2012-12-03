<?php
namespace PhpStrace;
use PhpStrace\CommandLine;


class Strace implements Requirement
{

    const SIGSEGV = 'SIGSEGV (Segmentation fault)';

    private $cmd = 'strace';

    /**
     * @var CommandLine
     */
    private $commandLine;

    /**
     * @var int
     */
    private $lines = 100;

    /**
     * @param string $cmd
     */
    public function setCmd ($cmd)
    {
        $this->cmd = (string) $cmd;
    }

    public function getCmd ()
    {
        return $this->cmd;
    }

    /**
     * @param int $numLines
     */
    public function setLines ($numLines)
    {
        $numLines = (int) $numLines;

        if ($numLines < 1 || $numLines > 10000) {
            throw new Exception('invalid line count');
        }

        $this->lines = $numLines;
    }

    /**
     * @return int
     */
    public function getLines ()
    {
        return $this->lines;
    }


    /**
     * @param CommandLine $commandLine
     */
    public function __construct (CommandLine $commandLine)
    {
        $this->commandLine = $commandLine;
    }

    /**
     * @return Requirement\Result
     */
    public function checkRequirements ()
    {
        $commandLine = new CommandLine();

        $result = new Requirement\Result();
        if ($commandLine->isToolInstalled($this->cmd . ' -h')) {
            $result->setSucess(true);
        } else {
            $result->setErrorMessage('command line tool "strace" ist not installed. Please install "strace".');
        }

        if ($result->getSucess()) {
            if (false === $commandLine->isToolInstalled('tail --help')) {
                $result->setSucess(false);
                $result->setErrorMessage('command line tool "tail" is not installed. please install "tail".');
            }
        }

        if ($result->getSucess()) {
            if (false === function_exists('pcntl_fork')) {
                $result->setSucess(false);
                $result->setErrorMessage('PCNTL extension not found. Please install PHP PCNTL extension.');
            }
        }

        return $result;
    }


    /**
     * @param int $phpPid
     * @param bool testing
     * @return bool isChild
     */
    public function watch ($phpPid, $testing = false)
    {
        $pid = null;
        if ($testing == false) {
            $pid = pcntl_fork();
        }

        if ($pid === -1) {
            throw new Exception('could not create fork for pid ' . $pid);
        }

        if ($pid && $testing == false) {
            pcntl_waitpid($pid, $status, WNOHANG);
            return $pid;
        } else {
            // child process runs what is here
            $this->commandLine->stdout('starting strace on pid ' . $phpPid . '.');

            $result = $this->commandLine->execute($this->cmd . ' -q -s 256 -p ' . escapeshellarg($phpPid) . ' 2>&1 | tail -' . $this->lines);

            $this->commandLine->stdout('pid ' . $phpPid . ' finished running with exit code ' . $result->getReturnVar() . '.');


            if ($result->getReturnVar() != 0 || substr_count(implode(' ', $result->getOutput()), self::SIGSEGV)) {
                foreach ($result->getOutput() as $line) {
                    $this->commandLine->stdout($line);
                }
            }

            return false;
        }
    }


}
