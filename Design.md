# Directory structure
* **admin** -
    contains code for the development environment which is **not** installed for a run-time environment.
    Will typically mimic most of the **core** Tranzakt-specific directories in order to hold all the Tranzakt
    development-only code. Bootstrap code will determine whether the URL/route is for admin and if so will
    bootstrap both the core & admin environments.
* **core** -
    contains all code necessary to run a predefined Tranzakt website
  * **laravel** -
      contains Laravel itself - used by both core and admin - and all additional pre-written laravel packages.
      The following directories are moved outside the Laravel directory in order to provide separation of
      Tranzakt code and Laravel code - and they are duplicated in the admin directory -
      but Laravel names are kept for the purpose of familiarity.
  * **app**
  * **bootstrap**
  * **config**
  * **database**
  * **lang**
  * **pages** -
      holds static page definitions when Tranzakt is used stand-alone rather than RPC from a CMS
  * **resources**
  * **routes**
  * **storage**
  * **tests** -
      unit, functional and regression tests for Tranzakt.
* **files** -
    optional directory to hold user uploaded files that for security reasons should not be accessible by other users.
    These files will need to be specially served by Tranzakt since they cannot be directly downloaded by the browser.
* **public** -
    the root webspace directory for the webserver - holds all publicly accessible static files
    separate from php files which for security reasons are not directly accessible from the webserver
  * **admin** -
      if needed (TBD) holds admin-specific static files that need to be served
  * **css**
  * **js**
  * **media** -
      images, sounds, videos etc. provided by the developer
  * **files** -
      user uploaded files which are publicly downloadable (if you know the URL)
