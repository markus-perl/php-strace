<?php
$basePath = realpath(__DIR__ . '/..');
date_default_timezone_set('America/Los_Angeles');

set_include_path(get_include_path() . PATH_SEPARATOR . $basePath);
$autoloder = function ($dir, $className) use ($basePath) {
    $filename = $dir . '/' . str_replace('\\', '/', $className) . ".php";
    if (file_exists($basePath . '/' . $filename)) {
        include_once $filename;
        return true;
    } else {
        return false;
    }
};

spl_autoload_register(function ($className) use ($autoloder) {
    $autoloder('src', $className);
});

require_once $basePath . '/vendor/autoload.php';