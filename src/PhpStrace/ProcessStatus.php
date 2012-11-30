<?php
namespace PhpStrace;
use PhpStrace\CommandLine;


class ProcessStatus implements Requirement
{

    private $cmd = 'ps';

    const PROCESS_NAME_AUTODETECT = '-1';

    /**
     * @var string
     */
    private $processName = self::PROCESS_NAME_AUTODETECT;

    /**
     * @var CommandLine
     */
    private $commandLine;

    /**
     * @var string
     */
    private $scriptName = 'php-strace';

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
     * @param string $scriptName
     */
    public function setScriptName ($scriptName)
    {
        $this->scriptName = (string) $scriptName;
    }

    /**
     * @return string
     */
    public function getScriptName ()
    {
        return $this->scriptName;
    }

    /**
     * @param string $processName
     */
    public function setProcessName ($processName)
    {
        $this->processName = (string) $processName;
    }

    /**
     * @return string
     */
    public function getProcessName ()
    {
        if ($this->processName == self::PROCESS_NAME_AUTODETECT) {
            if ($this->isProcessRunning('php5-cgi')) {
                $this->setProcessName('php5-cgi');
            } else {
                $this->setProcessName('php-fpm');
            }
        }

        return $this->processName;
    }

    /**
     * @param CommandLine $commandLine
     */
    public function __construct (CommandLine $commandLine, $scriptName = null)
    {
        $this->commandLine = $commandLine;

        if ($scriptName) {
            $this->scriptName = $scriptName;
        }
    }

    /**
     * @return Requirement\Result
     */
    public function checkRequirements ()
    {
        $result = new Requirement\Result();
        if ($this->commandLine->isToolInstalled($this->cmd)) {
            $result->setSucess(true);
        } else {
            $result->setErrorMessage('command line tool "ps" ist not installed. Please install "ps".');
        }

        return $result;
    }


    /**
     * @return array
     * @throws Exception
     */
    public function fetchPhpProcessIds ()
    {
        $result = $this->commandLine->execute($this->cmd . ' xa');

        $pids = array();
        foreach ($result->getOutput() as $line) {
            if (mb_substr_count($line, $this->getProcessName()) && mb_substr_count($line, $this->getScriptName()) == 0) {
                preg_match('/[0-9]+/', $line, $matches);
                if (false === isset($matches[0]) || false === is_numeric($matches[0]) || $matches[0] < 1) {
                    throw new Exception('faild to fetch pids');
                }

                $pids[] = (int) $matches[0];
            }
        }

        return $pids;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isProcessRunning ($name)
    {
        $result = $this->commandLine->execute($this->cmd . ' xa');
        foreach ($result->getOutput() as $line) {
            if (mb_substr_count($line, $name)) {
                return true;
            }
        }

        return false;
    }


}
