php-strace
==========

php-strace helps to track down segfaults in running php processes. It starts a new strace instance for every
running php5-cgi or php-fpm process to monitor whether a segfault happened.
If a segfault occurs, it will display the strace output of the faulty process.

<p align="center">
  <img src="https://github.com/markus-perl/php-strace/blob/master/readme.files/php-strace.png?raw=true" alt="php-strace workflow"/>
</p>


Requirements
------------

* Linux
* PHP 5.3.3 or later
* strace installed
* root access


Installation and Downloads
--------------------------

Download latest version and extract it to any folder

* [php-strace-0.3.tar.gz](https://dl.dropboxusercontent.com/u/32252351/github/php-strace-0.3.tar.gz)
* [php-strace-0.2.tar.gz](https://dl.dropbox.com/u/32252351/github/php-strace-0.2.tar.gz)


Usage
----------------------

    $ sudo ./php-strace


Commandline options
-------------------

    Usage: ./php-strace [ options ]
    -h|--help               show this help
    -l|--lines <integer>    output the last N lines of a stacktrace. Default: 100
    --process-name <string> name of running php processes. Default: autodetect
    --live                  search while running for new upcoming pid's


Development
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


Contact
-------
* Github: [http://www.github.com/markus-perl/php-strace](http://www.github.com/markus-perl/php-strace)
* E-Mail: markus <at> open-mmx.de
