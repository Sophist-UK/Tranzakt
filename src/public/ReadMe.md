# The `public` Directory
The `public` directory contains the index.php file,
which is the entry point for **all** requests entering the Tranzakt application and
it starts the Composer autoloading and Laravel bootstrapping.

This directory also houses your assets such as JavaScript, CSS and media files such as images.

The `public` directory holds all files directly downloadable by a browser and
is the root directory for the webserver.

All other php files, with the exception of `index.php`,
should be held outside this directory so that they cannot be executed using a direct URL.
(.htaccess security rules may be needed to avoid use of `/../` in URLs.)

Other files which will be delivered here are:
* Tranzakt Runtime and Developer css, js etc.
* user provided files
