# The `core` Directory
The `core` directory holds all the code for Tranzakt runtime, and is essentially a Laravel
root directory.

The only file in this directory is .env containing core settings for Laravel.


`src` contains the following sub-directories:
* `core` containing Tranzakt runtime
* `admin` containing Tranzakt developer
* `public` containing files publicly downloadable by the browser
e.g. html, css, js and media files,
as well as Tranzakt's index.php bootstrap file and other files such as .htaccess.

In a web site using Tranzakt, the following additional sibling directories may also exist:
* `pages` containing template files for normal web pages served bt Tranzakt runtime running standalone.
* `storage` containing log files created specifically by Tranzakt runtime to log exceptions etc.
* `user` possibly containing Tranzakt metadata compiled into directly executable php code (future)
