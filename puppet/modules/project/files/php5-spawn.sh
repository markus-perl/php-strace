#!/bin/sh
killall -s SIGKILL /usr/bin/php5-cgi
sleep 1
/usr/bin/spawn-fcgi  -C 3 -a 127.0.0.1 -p 9000 -u vagrant -f /usr/bin/php5-cgi