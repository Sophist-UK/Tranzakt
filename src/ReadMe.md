# The `src` Directory
The `src` directory holds all the code that is deployed with Tranzakt.

Typically non-functional code (such as Unit tests etc.) are held outside of this
directory in e.g. a sibling `tests` directory,
however in Tranzakt tests are so integral that they are held with the rest of the code.

When creating packages for downloading by users, careful selection of directories
will be needed so as to exclude these files.

`src` contains the following sub-directories:
* `core` containing Tranzakt Runtime
* `admin` containing Tranzakt Developer
* `public` containing files publicly downloadable by the browser
e.g. html, css, js and media files,
as well as Tranzakt's index.php bootstrap file and other files such as .htaccess.

In a web site using Tranzakt, the following additional sibling directories may also exist:
* `pages` containing template files for normal web pages served bt Tranzakt Runtime running standalone.
* `storage` containing log files created specifically by Tranzakt Runtime to log exceptions etc.
* `user` possibly containing Tranzakt metadata compiled into directly executable php code (future)
