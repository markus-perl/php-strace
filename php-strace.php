<?php
require_once __DIR__ . '/src/Bootstrap.php';
$runner = new PhpStrace\Runner();
$runner->run($argv);
