= Setup for XAMPP on Windows

Use admin terminal for commands! Right click CMD icon, run as Administrator.

First, fork the repo into your own account (e.g. myuser). You should
then have a repo like this:

https://github.com/myuser/bitvest

Then, clone it as bitvest-repo.

cd C:\xampp\htdocs\
git clone myuser@https://github.com/myuser/bitvest.git bitvest-repo

Create a symlink:

mklink /D bitvest bitvest-repo/public

Fix the bootstrap symlink:

cd C:\xampp\htdocs\bitvest-repo\public
del bootstrap
mklink /D bootstrap bootstrap-2.3.2

Copy the config file:

cd C:\xampp\htdocs\bitvest-repo
copy config.php-example config.php

Copy the htaccess file:

cd C:\xampp\htdocs\bitvest-repo\public
copy .htaccess-example .htaccess

Then you should be able to browse to: http://localhost:8080/bitvest/ and see the welcome page.

