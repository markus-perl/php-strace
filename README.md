php-strace
==========

php-strace helps to track down segfaults in running php processes. It starts for every
running php5-cgi or php-fpm process a new strace instance to monitor if
a segfault happens. If a segfault occurs it will display the strace output of the faulty process.


Requirements
------------

* Linux
* PHP 5.3.3 or later
* strace installed
* root access


Installation
------------

* Download latest version and extract it to any folder


Usage
----------------------

    $ sudo ./php-strace


Commandline options
-------------------

    Usage: ./php-strace [ options ]
    -h|--help               show this help
    -m|--memory <integer>   memory limit in MB. Default: 512, min: 16, max 2048
    -l|--lines <integer>    output the last N lines of a stacktrace. Default: 100
    --process-name <string> name of running php processes. Default: autodetect
    --live                  search while running for new upcoming pid's


Developing
----------

* Checkout repository
* Install vagrant and then run

    $ vagrant up
    $ vagrant ssh
    $ ./php-strace


Testing
-------

To run the tests ssh to your vagrant machine and enter:

    $ /vagrant/scripts/phpunit
