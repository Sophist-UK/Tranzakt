# The `Tests` Directory
The `tests` directory contains automated tests for Tranzakt.

phpUnit is used by Laravel & Tranzakt for unit testing php code.

Laravel Dusk will be used for automated browser regression testing
of front-end web functionality as seen by the user.

Each test class should be suffixed with the word Test.

Github actions should run tests using the `phpunit` or `php vendor/bin/phpunit` commands.

When running interactively, a more detailed and beautiful representation of your test results
can be obtained by running using the `php artisan test` Artisan command.

Example PHPUnit unit tests and feature tests are provided out of the box.
