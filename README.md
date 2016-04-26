
LTI / Slack Gateway
===================

This is a component of the [Tsugi PHP Project](https://github.com/csev/tsugi).

This tool allows you to auto-invite all the students in a class into a Slack
Channel via LTI.  This is based on the following code:

    https://gist.github.com/Topener/8b08955e13e961d14173
    https://levels.io/slack-typeform-auto-invite-sign-ups/
    https://github.com/outsideris/slack-invite-automation


Setup Notes
-----------

Make a slack channel a get a token here:

    https://api.slack.com/docs/oauth-test-tokens

A token looks like this:

    xoxp-38475772738-44897347937-84398938794-2832f83888

Make sure the token is created for the owner of the slack channel.

Simple Installation
-------------------

In the simple installation scenario, you have installed and configured 
Tsugi to a folder like:

    htdocs/tsugi

Since Tsugi can be configured to discover PHP tools in lots of folders, you
could check this code out into one of several places:

    htdocs/tsugi/mod/tsugi-php-slack
    htdocs/tsugi/tsugi-php-slack
    htdocs/tsugi-php-slack  (suggested)
    htdocs/php-intro/tools/tsugi-php-slack

Once you have checked this code out, you need to make a config.php that
simply includes the `config.php` from the Tsugi directory.   For example
if you checked this code into a "peer" folder next to `tsugi`, your
`config.php` in this folder should be:

    <?php 
    require_once "../tsugi/config.php";

You will also need to inform Tsugi to search the new tool's folder
for files like `index.php`, `register.php`, and `database.php`.
To do this, edit the `$CFG->tool_folders` parameter in the 
Tsugi `config.php` file to include the relative path to this tool.

    $CFG->tool_folders = array("admin", "mod", "samples", 
        "exercises", "../tsugi-php-slack");

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

    http://localhost:8888/tsugi-php-slack/index.php
    key: 12345
    secret: secret

Keys and secrets are managed through the controlling Tsugi.
