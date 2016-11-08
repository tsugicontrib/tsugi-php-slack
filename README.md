
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

This expects to be checked into a folder that contains one or more
Tsugi modules like:

    htdocs/py4e/mod

This code expects there to be a `config.php` in the parent folder.

You will also need to inform Tsugi to search the new tool's parent 
folder for files like `index.php`, `register.php`, and `database.php`.
To do this, edit the `$CFG->tool_folders` parameter in the 
Tsugi `config.php` file to include the relative path to this tool.

    $CFG->tool_folders = array("admin", "mod", "samples", 
        "exercises", "../tsugi-php-slack");

If you checked the tool out into the `mod` folder with Tsugi - you may
not need to change this configuration value as `mod` and its chid folders
are already searched to find tools.

Running The Application
-----------------------

Once you have connected this tool to a Tsugi install as described above, 
you can use the Admin/Database Upgrade feature to create / maintain database 
tables for this tool.  You can also use the Developer mode of that Tsugi to
test launch this tool.   The LTI 2.0 support, CASA Support, and Content Item
support for the controlling Tsugi will know about this tool.

LTI 1.x launches simply are directed to the index.php in this folder:

    http://localhost:8888/py4e/mod/tsugi-php-slack/index.php
    key: 12345
    secret: secret

Keys and secrets are managed through the controlling Tsugi.
