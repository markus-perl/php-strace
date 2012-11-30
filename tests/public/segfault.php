<?php
//https://bugs.php.net/bug.php?id=61920&edit=1
mb_regex_encoding("UTF-8");
mb_internal_encoding("UTF-8");
echo mb_eregi_replace("[^\xfe]", "?", "\xfe ");