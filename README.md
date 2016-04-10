
LTI / Slack Gateway
===================

This tool allows you to auto-invite all the students in a class into a Slack
Channel via LTI.  This is based on the following code:

    https://gist.github.com/Topener/8b08955e13e961d14173
    https://levels.io/slack-typeform-auto-invite-sign-ups/
    https://github.com/outsideris/slack-invite-automation



Tsugi Sample PHP Module
=======================

This provides a simple starting point for a Tsugi application. This
code depends on the main Tsugi code for database configuration,
setup, developer test harness, CASA support, Content Item Support, etc.

The idea is that when you are starting a new Tsugi application, you download 
the code for this application as your starting point and then edit from there.
It is probably a bad idea to fork this repository as you don't really want to 
track updates to this sample code.

Once you start developing Tsugi Applications, you should join the Tsugi 
Developers list so you can get announcements when things change.

    https://groups.google.com/a/apereo.org/forum/#!forum/tsugi-dev

Simple Installation
-------------------

In the simple installation scenario, you have installed and configured 
Tsugi to a folder like:

    htdocs/tsugi

Since Tsugi can be configured to discover PHP tools in lots of folders, you
could check this code out into one of several places:

    htdocs/tsugi/mod/tsugi-php-module
    htdocs/tsugi/tsugi-php-module
    htdocs/tsugi-php-module  (suggested)
    htdocs/php-intro/tools/tsugi-php-module

Once you have checked this code out, you need to make a config.php that
simply includes the `config.php` from the Tsugi directory.   For example
if you checked this code into a "peer" folder next to `tsugi`, your
`config.php` in this folder should be:

    <?php 
    require_once "../tsugi/config.php";

You will also need to inform Tsugi to search the new tool's folder
for files like `index.php`, `register.php`, and `database.php`.
To do this, edite the `$CFG->tool_folders` parameter in the 
Tsugi `config.php` file to include the relative path to this tool.

    $CFG->tool_folders = array("admin", "mod", "samples", 
        "exercises", "../tsugi-php-module");

If you checked the tool out into the `mod` folder with Tsugi - you may
not need to change this configuration value as `mod` is already searched 
to find tools.

Running The Application
-----------------------

Once you have connected this tool to a Tsugi install as described above, 
you can use the Admin/Database Upgrade feature to create / maintain database 
tables for this tool.  You can also use the Developer mode of that Tsugi to
test launch this tool.   The LTI 2.0 support, CASA Support, and Content Item
support for the controlling Tsugi will know about this tool.

LTI 1.x launches simply are directed to the index.php in this folder:

    http://localhost:8888/tsugi-php-module/grade/index.php
    key: 12345
    secret: secret

Keys and secrets are managed through the controlling Tsugi.

Advanced Installation
---------------------

If you are going to install this tool in a web server that does not
already have an installed copy of Tsugi, it is a bit trickier.  There
is no automatic connection between Tsugi developer tools and Tsugi admin 
tools won't know about this tool.   But it can run stand alone.

First install composer to include dependencies.

    http://getcomposer.org/

I just do this in the folder:

    curl -O https://getcomposer.org/composer.phar

Get a copy of the latest `composer.json` file from the Tsugi repository
or a recent Tsugi installation and copy it into this folder.

To install the dependencies into the `vendor` area, do:

    php composer.phar install

If you want to upgrade dependencies (perhaps after a `git pull`) do:

    php composer.phar update

Note that the `composer.lock` file and `vendor` folder are 
both in the `.gitignore` file and so they won't be checked into
any repo.

For advanced configuation, you need to retrieve a copy of 
`config-dist.php` from the Tsugi repo or a copy of `config.php`
from a Tsugi install and place the file in this folder.

Running (Advanced Configuration)
--------------------------------

Once it is installed and configured, you can do an LTI launch to

    http://localhost:8888/tsugi-php-module/grade/index.php
    key: 12345
    secret: secret

You can use your Tsugi installation or my test harness at:

    https://online.dr-chuck.com/sakai-api-test/lms.php

And it should work!

Upgrading the Library Code (Advanced Configuration)
---------------------------------------------------

From time to time the library code in

    https://github.com/csev/tsugi-php

Will be upgraded and pulled into Packagist:

    https://packagist.org/packages/tsugi/lib

To get the latest version from Packagist, edit `composer.json` and
update the commit hash to the latest hash on the `packagist.org` site
and run:

    php composer.phar update



