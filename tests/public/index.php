<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
    <title>php-strace</title>
</head>

<body>
<?php

if ($_SERVER['SERVER_PORT'] == '80') {
    echo '<h1>php5-cgi</h1>';
    echo '<p><a href="http://' . str_replace('8080', '8081', $_SERVER['HTTP_HOST']) . '">switch to fpm</a></p>';
}

if ($_SERVER['SERVER_PORT'] == '81') {
    echo '<h1>php-fpm</h1>';
    echo '<p><a href="http://' . str_replace('8081', '8080', $_SERVER['HTTP_HOST']) . '">switch to cgi</a></p>';
}

echo '<p><a href="segfault.php">segfault me!</a></p>';
?>
</body>
</html>