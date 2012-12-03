<?php
namespace PhpStrace;
use PhpStrace\ProcessStatus;
use Zend\Console;


class Runner
{

    /**
     * @var CommandLine
     */
    private $commandLine = null;

    /**
     * @var ProcessStatus
     */
    private $processStatus = null;

    /**
     * @var Strace
     */
    private $strace = null;

    /**
     * @var int
     */
    private $memoryLimit = 512;

    /**
     * @var string
     */
    private $scriptName = 'php-strace';

    /**
     * @var bool
     */
    private $live = false;

    /**
     * @param boolean $live
     */
    public function setLive ($live)
    {
        $this->live = (boolean) $live;
    }

    /**
     * @return boolean
     */
    public function getLive ()
    {
        return $this->live;
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
     *
     */
    public function run (array $argv)
    {
        try {
            $this->setScriptName($argv[0]);
            $this->showWelcomeMessage();
            $this->parseGetOpt($argv);
            $this->checkRequirements();
            $this->bootstrap();
            $this->watchPids();
        } catch (Console\Exception\RuntimeException $e) {
            $this->getCommandLine()->stdout('php-strace starts a new strace instance for every running php5-cgi process and displays any segfault occurrence.');
            $this->getCommandLine()->stdout($e->getUsageMessage());
        } catch (ExitException $e) {
            exit($e->getCode());
        } catch (\Exception $e) {
            $this->getCommandLine()->stderr('Line ' . $e->getLine() . ' ' . $e->getFile() . ' - ' . $e->getMessage());
        }
    }

    /**
     *
     */
    public function bootstrap ()
    {
        set_time_limit(-1);
        ini_set('memory_limit', $this->memoryLimit . 'M');
    }

    /**
     *
     */
    public function showWelcomeMessage ()
    {
        $this->getCommandLine()->stdout('php-strace ' . Version::ID . ' by Markus Perl (http://www.github.com/markus-perl/php-strace)');
        $this->getCommandLine()->stdout('');
    }

    /**
     * @param array $argv
     * @throws \Zend\Console\Exception\RuntimeException
     */
    public function parseGetOpt (array $argv)
    {

        $rules = array(
            'h|help' => 'show this help',
            'm|memory=i' => 'memory limit in MB. Default: 512, min: 16, max 2048',
            'l|lines=i' => 'output the last N lines of a stacktrace. Default: 100',
            'process-name=s' => 'name of running php processes. Default: autodetect',
            'live' => 'search while running for new upcoming pid\'s',
        );

        $opts = new Console\Getopt($rules, $argv);

        $opts->parse();

        if ($opts->help) {
            throw new Console\Exception\RuntimeException('', $opts->getUsageMessage());
        }

        if ($opts->getOption('memory')) {
            $limit = $opts->getOption('memory');
            if ($limit >= 16 && $limit <= 2048) {
                $this->setMemoryLimit($limit);
            }
        }

        if ($opts->getOption('lines')) {
            $this->getStrace()->setLines(min(1000, max(1, $opts->getOption('lines'))));
        }

        if ($opts->getOption('process-name')) {
            $this->getProcessStatus()->setProcessName($opts->getOption('process-name'));
        }

        if ($opts->getOption('live')) {
            $this->setLive(true);
        }
    }

    /**
     * @return CommandLine
     */
    public function getCommandLine ()
    {
        if (null === $this->commandLine) {
            $this->commandLine = new CommandLine();
        }

        return $this->commandLine;
    }

    /**
     * @param CommandLine $commandLine
     */
    public function setCommandLine (CommandLine $commandLine = null)
    {
        $this->commandLine = $commandLine;
    }

    /**
     * @return ProcessStatus
     */
    public function getProcessStatus ()
    {
        if (null === $this->processStatus) {
            $this->processStatus = new ProcessStatus($this->getCommandLine(), $this->getScriptName());
        }

        return $this->processStatus;
    }

    /**
     * @return Strace
     */
    public function getStrace ()
    {
        if (null === $this->strace) {
            $this->strace = new Strace($this->getCommandLine());
        }

        return $this->strace;
    }

    /**
     *
     */
    public function checkRequirements ()
    {
        $collection = new Requirement\Collection;
        $collection->add(new Linux());
        $collection->add(new Root());
        $collection->add($this->getProcessStatus());
        $collection->add($this->getStrace());

        $errorMessages = array();
        /* @var Requirement $Requirement */
        foreach ($collection as $Requirement) {
            $result = $Requirement->checkRequirements();

            if (false === $result->getSucess()) {
                $errorMessages[] = $result->getErrorMessage();
            }
        }

        if (count($errorMessages)) {
            $this->getCommandLine()->stderr('The following Requirements did not met:');
            foreach ($errorMessages as $message) {
                $this->getCommandLine()->stderr($message);
            }

            throw new ExitException('Requirements not met', 1);
        }
    }

    /**
     * @return array
     */
    private function fetchPids ()
    {
        $pids = $this->getProcessStatus()->fetchPhpProcessIds();

        if (0 == count($pids)) {
            $this->getCommandLine()->stderr('No running php processes found');
            throw new ExitException('no running processes', 1);
        }

        return $pids;
    }

    private function startStrace ($pid)
    {
        $pidWatching = $this->getStrace()->watch($pid);
        if (false == $pidWatching) {
            throw new ExitException('', 0);
        }

        return $pidWatching;
    }

    /**
     * @param array $pids
     */
    public function watchPids ()
    {
        $pids = $this->fetchPids();

        $pidsWatching = array();
        foreach ($pids as $pid) {
            $pidWatching = $this->startStrace($pid);
            $pidsWatching[] = $pidWatching;
        }

        usleep(100000);
        $this->getCommandLine()->stdout('Startup completed. If any segfault will happen, you will see it here. Press ctrl+c to exit.');

        while (count($pidsWatching)) {
            $pid = pcntl_wait($status, WNOHANG);
            if ($pid) {
                $pidsWatching = array_diff($pidsWatching, array($pid));
                $this->getCommandLine()->stdout('child with pid ' . $pid . ' terminated');
            }

            if ($this->getLive()) {
                $currentPids = $this->fetchPids();
                $diff = array_diff($currentPids, $pids);
                if (count($diff)) {
                    foreach ($diff as $pid) {
                        $pidWatching = $this->startStrace($pid);
                        $pidsWatching[] = $pidWatching;
                    }
                    $pids = $currentPids;
                }
            }

            sleep(1);
        }
    }

    /**
     * @param int $memoryLimit
     */
    public function setMemoryLimit ($memoryLimit)
    {
        $this->memoryLimit = (int) $memoryLimit;
    }

    /**
     * @return int
     */
    public function getMemoryLimit ()
    {
        return $this->memoryLimit;
    }


}