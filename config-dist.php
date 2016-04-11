<?php

// This is set up to be installed in a peer folder to tusgi
// This require needs to point to a properly configured tsugi
// instance.  COpy this file to config.php and edit accordingly
// if necessary.

require_once "../tsugi/config.php";

// It is possible to set this up when it is not running on the same
// server as tsugi.   In that case, take the config-dist.php from Tsugi and
// set up the configuration in this folder.   You will need to manually
// set up the database tables and do administration tasks manually as well.
//
// There is some documentation on this more complex installation setup at
//
// https://github.com/csev/tsugi-php-module/blob/master/README.md
